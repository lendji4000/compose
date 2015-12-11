/**
 * Service Constructor
 *
 * @param system
 * @param firefoxPath
 * @param token
 * @param projectRef
 * @param projectId
 * @param projectName
 * @param profileRef
 * @param profileId
 * @param profileName
 * @param port
 *
 * @constructor
 */
function EiService(system, firefoxPath, token, projectRef, projectId, projectName, profileRef, profileId, profileName, port){
    this.baseUrl = "http://localhost";
    this.port = typeof port == "undefined" ? 11296:port;

    this.system = system;
    this.firefoxPath = firefoxPath;
    this.token = token;
    this.projectRef = projectRef;
    this.projectId = projectId;
    this.projectName = projectName;
    this.profileRef = profileRef;
    this.profileId = profileId;
    this.profileName = profileName;
}

/**
 * Definition of all callable services.
 *
 * @type {{SERVICE_CONFIG: string, SERVICE_EXCEL: string, SERVICE_CHECK_EXCEL: string, SERVICE_PLAY: string, SERVICE_RECORD: string, SERVICE_DEBUG: string, SERVICE_CAMPAIGN: string}}
 */
EiService.Services = {
    SERVICE_CONFIG: "/config",
    SERVICE_EXCEL: "/excel",
    SERVICE_CHECK_EXCEL: "/check_excel",
    SERVICE_PLAY: "/play",
    SERVICE_RECORD: "/record",
    SERVICE_DEBUG: "/debug",
    SERVICE_CAMPAIGN: "/campaign"
};

EiService.prototype = {

    /**
     *
     * @returns {string}
     */
    getBaseUrl: function () {
        return this.baseUrl + ":" + this.port;
    },

    /**
     *
     * @param service
     * @returns {string}
     */
    getServiceUrl: function(service){
        return this.getBaseUrl() + service;
    },

    /**
     *
     * @returns {{profileRef: *, profileId: *, profileName: *, projectRef: *, projectId: *, projectName: *, system: *, firefox_path: *, token: *}}
     */
    getPreSetDatas: function(datas){
        return $.extend({}, {
            profile_ref: this.profileRef,
            profile_id: this.profileId,
            profile_name: this.profileName,
            project_ref: this.projectRef,
            project_id: this.projectId,
            project_name: this.projectName,
            referer: this.system,
            firefox_path: this.firefoxPath,
            token: this.token,
            browser_type: "firefox",
            driver_type: "selenium_ide"
        }, datas);
    },

    /**
     * Method which permit to determine if the service exists or not.
     *
     * @param callback
     */
    isServiceAvailable: function(callback){
        this.makeHeadRequest(
            this.getServiceUrl(EiService.Services.SERVICE_CONFIG),
            callback
        );
    },

    /**
     * Check if Excel exists on the desktop.
     *
     * @param callback
     */
    isExcelAvailable: function(callback){
        this.makeGetRequest(
            this.getServiceUrl(EiService.Services.SERVICE_CHECK_EXCEL),
            callback
        );
    },

    /**
     * Open Excel App.
     *
     * @param callback
     */
    openExcel: function(callback){
        var self = this;

        this.isExcelAvailable(function(result, datas){
            if( result == true ){
                self.makeGetRequest(
                    self.getServiceUrl(EiService.Services.SERVICE_EXCEL)
                );
            }
        });
    },

    /**
     *
     * @param service
     * @param datas
     * @param callback
     */
    launchInIDE: function(service, datas, callback){
        this.makePostRequest(service, this.getPreSetDatas(datas), callback);
    },

    /**
     *
     * @param scenarioName
     * @param scenarioId
     * @param jdd
     * @param callback
     */
    play: function(scenarioName, scenarioId, jdd, callback){
        this.launchInIDE(this.getServiceUrl(EiService.Services.SERVICE_PLAY), {
            "scenario_name": scenarioName,
            "scenario_id": scenarioId,
            "jdd_id": jdd == "" ? 0:jdd
        }, callback);
    },

    /**
     *
     * @param scenarioName
     * @param scenarioId
     * @param jdd
     * @param callback
     */
    record: function(scenarioName, scenarioId, jdd, callback){
        this.launchInIDE(this.getServiceUrl(EiService.Services.SERVICE_RECORD), {
            "scenario_name": scenarioName,
            "scenario_id": scenarioId,
            "jdd_id": jdd == "" ? 0:jdd
        }, callback);
    },

    /**
     *
     * @param scenarioName
     * @param scenarioId
     * @param jdd
     * @param callback
     */
    debug: function(scenarioName, scenarioId, jdd, callback){
        this.launchInIDE(this.getServiceUrl(EiService.Services.SERVICE_DEBUG), {
            "scenario_name": scenarioName,
            "scenario_id": scenarioId,
            "jdd_id": jdd == "" ? 0:jdd
        }, callback);
    },

    /**
     *
     * @param campaignId
     * @param startPos
     * @param endPos
     * @param callback
     */
    launchCampaign: function(campaignId, startPos, endPos, callback){
        var self = this;

        // Création de l'exécution.
        execution = createExecutionCampaign(this.profileName, this.projectName, campaignId, function(execution){
            if( typeof execution.id != "undefined" ){
                // Envoi des informations au service.
                self.makePostRequest(
                    self.getServiceUrl(EiService.Services.SERVICE_CAMPAIGN),
                    self.getPreSetDatas({
                        "execution_id": execution.id,
                        "campaign_id": campaignId,
                        "start_pos": startPos,
                        "end_pos": endPos
                    }),
                    function(){

                        if( typeof callback != "undefined"){
                            callback();
                        }

                        callbackPlayIDE(execution.id, execution.url);
                    }
                );
            }
        });
    },

    /**
     *
     * @param service
     * @param callback
     */
    makeHeadRequest: function(service, callback){
        this.makeGetRequest(service, callback, true);
    },

    /**
     *
     * @param service
     * @param callback
     * @param isHeadRequest
     * @returns {*}
     */
    makeGetRequest: function(service, callback, isHeadRequest){
        var result = null;
        isHeadRequest = typeof isHeadRequest != "undefined";

        $.ajax({
            type: isHeadRequest == true ? "HEAD":"GET",
            url: service,
            async: true,
            crossDomain: true,
            success: function(data){
                result = isHeadRequest == true ? true:data.success;

                if( typeof callback != "undefined" ){
                    callback(result, data);
                }
            },
            error: function(data){
                if( typeof callback != "undefined" ){
                    callback(false, data);
                }
            }
        });
    },

    /**
     *
     * @param service
     * @param datas
     * @param callback
     * @returns {*}
     */
    makePostRequest: function(service, datas, callback){
        var result = null;

        $.ajax({
            type: "POST",
            url: service,
            async: true,
            crossDomain: true,
            dataType: "json",
            data: {
                params: JSON.stringify(datas)
            },
            success: function(data){
                result = data.success;

                if( result == false ){
                    alert("An error occured while starting execution : " + data.error);
                }

                if( typeof callback != "undefined" ){
                    callback(result, data);
                }
            },
            error: function(data){
                if( typeof callback != "undefined" ){
                    callback(false, data);
                }
            }
        });
    }
};