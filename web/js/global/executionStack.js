$(document).ready(function(){
    $(document).delegate("#btnSwitchExecutionStackPane, #btnCloseExecutionStackPanel", "click", function(event){
        event.preventDefault();
        var elt = $("#btnSwitchExecutionStackPane");

        // Fermeture.
        if( elt.hasClass("open") ){
            closeExecutionStackSlidePanel();
        }
        else{
            openExecutionStackSlidePanel();
        }
    });

    $(window).resize(function() {
        adaptExecutionStackSlidePanel();
    });
});

function adaptExecutionStackSlidePanel(){
    if($("#usage").length){
       var diff = $("#usage").position().top - parseInt($("#header1Part1").css("height"));

        $("#executionStackPanel").css("height", diff+"px"); 
    }
    
}

function openExecutionStackSlidePanel(){
    adaptExecutionStackSlidePanel();

    $("#executionStackPanel").show("slide", { direction: "right" }, 1000);
    $("#btnSwitchExecutionStackPane").addClass("open");
    $("#btnSwitchExecutionStackPaneContainer").addClass("open");

    refreshExecutionStackSlidePanel();
}

function closeExecutionStackSlidePanel(){
    $("#executionStackPanel").hide("slide", { direction: "right" }, 1000);
    $("#btnSwitchExecutionStackPane").removeClass("open");
    $("#btnSwitchExecutionStackPaneContainer").removeClass("open");
}

function refreshExecutionStackSlidePanel(){
    $.ajax({
        type: 'GET',
        url: $("#executionStackPanel").attr("data-url"),
        async: true,
        beforeSend: function() {
            $("#executionStackPanel .content").hide();
            $("#loaderExecutionStack").show();
        },
        success: function(response) {
            $("#executionStackPanel .content").html(response);
        },
        error: function(response) {
            alert('Error ! Problem when processing');
        },
        complete: function(){
            $("#loaderExecutionStack").hide();
            $("#executionStackPanel .content").fadeIn(500);
        }
    });
}

/**
 * Fonction permettant d'ajouter un scénario + jdd à la file d'attente.
 *
 * @param scenarioId
 * @param jddId
 */
function addToExecutionStack(scenarioId, jddId, deviceId, driverId, browserId, date){
    var deviceManager = $("#blockDeviceManager");

    if( typeof deviceManager != "undefined" && deviceManager.length > 0 ){
        targetUrl = deviceManager.attr("data-url");

        if( typeof targetUrl != "undefined" && targetUrl.length > 0 ){
            targetUrl = targetUrl.replace("jddId", jddId.length == 0 ? 0:jddId);
            targetUrl = targetUrl.replace("deviceId", deviceId.length == 0 ? 0:deviceId);
            targetUrl = targetUrl.replace("driverId", driverId.length == 0 ? 0:driverId);
            targetUrl = targetUrl.replace("browserId", browserId.length == 0 ? 0:browserId);
            targetUrl = targetUrl.replace("date", date.length == 0 ? 0:date);
            $.ajax({
                type: 'POST',
                url: targetUrl,
                async: false,
                beforeSend: function() {},
                success: function(response) {
                },
                error: function(response) {
                    alert('Error ! Problem when processing');
                }
            });
        }
    }
}

/**
 *
 * @param id
 * @param start
 * @param end
 */
function addCampaignToExecutionStack(id, start, end, deviceId, driverId, browserId, date){
    var deviceManager = $("#blockDeviceManager");

    if( typeof deviceManager != "undefined" && deviceManager.length > 0 ){
        targetUrl = deviceManager.attr("data-url");

        if( typeof targetUrl != "undefined" && targetUrl.length > 0 ){
            targetUrl = targetUrl.replace("campId", id).replace("startPos", start).replace("endPos", end);
            targetUrl = targetUrl.replace("deviceId", deviceId.length == 0 ? 0:deviceId);
            targetUrl = targetUrl.replace("driverId", driverId.length == 0 ? 0:driverId);
            targetUrl = targetUrl.replace("browserId", browserId.length == 0 ? 0:browserId);
            targetUrl = targetUrl.replace("date", date.length == 0 ? 0:date);

            $.ajax({
                type: 'POST',
                url: targetUrl,
                async: false,
                beforeSend: function() {},
                success: function(response) {
                },
                error: function(response) {
                    alert('Error ! Problem when processing');
                }
            });
        }
    }
}