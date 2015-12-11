var timeoutApplet;
$(function () {
    var nowDate = new Date();
    var minDate = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), 0, 0);
    $('#datetimepickerExpectedDate').datetimepicker({
        format: "YYYY/MM/DD HH:mm:ss",
        minDate: minDate,
        widgetPositioning: {
            horizontal: 'auto',
            vertical: 'top'
        }
    });
});
$(document).ready(function() {
    $(this).delegate(".linkSelectDataSetApplet", "click", chooseScenarioDataSet);
    $(this).delegate(".editDataSetStepBox", "click", loadScenarioDataSetTreeForStep);
    $(this).delegate("#btnEmptySelectedDataSet", "click", emptySelectedDataSet);

    //********************************************************//
    //*****     GESTION MENU DES OPTIONS D'EXECUTION     *****//
    //********************************************************//
    $('#closeExecutionMenu').click(function(e){
        $('#btnSwitchExecutionMenu').removeClass('open');
        $('#btnSwitchExecutionMenu').parent().removeClass('open');
        $('#executionMenu').removeClass('expanded');
        $('#executionMenu').fadeOut('fast');
    });
    
    $('#btnSwitchExecutionMenu').click(function(e){
        var etat = $('#executionMenu').hasClass('expanded');
        if(etat)
        {
            $(this).parent().removeClass('open');
            $(this).removeClass('open');
            $('#executionMenu').removeClass('expanded');
            $('#executionMenu').fadeOut('fast');
        }
        else
        {
            $(this).parent().addClass('open');
            $(this).addClass('open');
            $('#executionMenu').addClass('expanded');
            $('#executionMenu').fadeIn('fast');
        }
    });
    //***********************************************//
    //*****     GESTION SETTINGS WEB DRIVER     *****//
    //***********************************************//
    
    $('#webDriverDropdown li, #webDriverDropdown label').click(function(e){
        e.stopPropagation();
    });
    
    $(".titleChoice").click(function(){
        var etat = $(this).hasClass('expanded');
        if(etat)
        {
            $(this).removeClass('expanded');
            if($(this).hasClass("titleChoiceDevices"))
            {
                $('.titleChoiceBrowsers').each(function(){
                   if($(this).hasClass('expanded'))
                   {
                       $(this).click();
                   }
                });
                $('.titleChoiceBrowsers').fadeOut();
            }
            else if ($(this).hasClass("titleChoiceDrivers"))
            {
                $(this).nextUntil('#selLi').fadeOut();
            }
            else if($(this).hasClass("titleChoiceBrowsers"))
            {
                $(this).nextUntil('.titleChoiceBrowsers, .titleChoiceDrivers').fadeOut();
            }
            $(this).children("i").removeClass("fa-minus-square");
            $(this).children("i").addClass("fa-plus-square");
        }
        else
        {
            $(this).addClass('expanded');
            if($(this).hasClass("titleChoiceDevices"))
            {
                $('.titleChoiceBrowsers').fadeIn();
            }
            else if ($(this).hasClass("titleChoiceDrivers"))
            {
                $(this).nextUntil('#selLi').fadeIn();
            }
            else if($(this).hasClass("titleChoiceBrowsers"))
            {
                $(this).nextUntil('.titleChoiceBrowsers, .titleChoiceDrivers').fadeIn();
            }
            $(this).children("i").removeClass("fa-plus-square");
            $(this).children("i").addClass("fa-minus-square");
        }
    });
    
    $('#webDriverDropdown li.choice').click(function(e) {
        
        //****************************************************//
        //*****     DETERMINATION DEVICE SELECTIONNE     *****//
        //****************************************************//

        // Récupération de l'élément sélectionné
        var checkedElt = $("#webDriverDropdown input:checkbox");
        // Récupération de sa valeur.
        var checkedVal = [];
        // Récupération de l'élément contenant le nom du device.
        var targetImg = $("#deviceImgWebDrivers");
        var countElts = $("#webDriverDropdown input:checkbox:checked").length;
        if(!$(this).hasClass("checked"))
        {
            /* On différencie le cas du local selenium IDE des devices/browsers car son comportement est différent par la suite (play/step/record) */
            if($(this).attr('id') == 'selLi')
            {
                /* dans le cas du local selenium ide, on désactive les devices/browsers */
                $('#webDriverDropdown input:checkbox').prop("checked", false);
                $('#webDriverDropdown input:checkbox').closest("li").removeClass("checked");
                $('#webDriverDropdown input:checkbox').parent().css("background-color", "white");
            }
            else
            {
                $('#selLi input:checkbox').prop("checked", false);
                $('#selLi input:checkbox').closest("li").removeClass("checked");
                $('#selLi input:checkbox').parent().css("background-color", "white");
            }
            $(this).children('input').prop("checked", true);
            $(this).addClass("checked");
            $(this).css("background-color", "silver");

            // Ajout des icones dans la barre d'exécution.
            /*targetImg.html("");

            $("#webDriverDropdown input:checked").each(function(event){
                if($(this).parent().prevAll("li.titleChoice:first").hasClass("titleChoiceBrowsers"))
                {
                    targetImg.append($(this).parent().prevAll("li.titleChoice:first").children("img").clone());
                    targetImg.append("&nbsp;");
                    targetImg.append($(this).parent().prevAll("li.titleChoice:first").children("strong").clone());
                    targetImg.append("&nbsp;");
                }
                targetImg.append($(this).next().next("img").clone());
            });*/

            /*if($("#blockDeviceManager").hasClass("open"))
            {
                $("#btnPlayScenarioInWebDrivers").click();
            }*/
        }
        else if($('li.choice.checked').length > 1) /* cette condition fait que l'on doit avoir au minimum un choix sélectionné */
        {
            $(this).children('input').prop("checked", false);
            $(this).removeClass("checked");
            $(this).css("background-color", "white");
        }
        // Gestion des boutons de play / step by step / record
        // Si IDE, on active les boutons désactivés.
        if( $("#selLi").hasClass("checked") ){
           $("#btnRecordScenarioInIde, #btnDebugScenarioInIde, #btnPlayScenarioInIde, #btnPlayCampagneInIde").addClass("disabledOracle");
           EiServiceManager.isServiceAvailable(initializeServiceInteractions);
        }
        else{
            $("#btnPlayScenarioInIde, #btnPlayCampagneInIde").removeClass("disabledOracle").addClass("webDrivers");
            $("#btnRecordScenarioInIde, #btnDebugScenarioInIde").addClass("disabledOracle");
        }
    });
    /* Paramètres présents à l'affichage de la page */
    $("li.choice, li.titleChoiceBrowsers").not('#selLi').fadeOut();
    $("#selLi").click();

    $("#webDriverDropdown .btnSwitchDevice").click(function(event){
        $("#" + $(this).attr("for")).trigger("click");
    });

    /**
     * Action déclenchée sur clic du bouton RECORD dans le scénario/version...
     */
    $("#btnRecordScenarioInIde").click(function(event){
        event.preventDefault();

        var self = $(this);
        var id = $(this).attr("data-player-id");
        var nom = $(this).attr("data-player-nom");
        var jdd = $(this).attr("data-player-jdd");

        if( !$(this).hasClass("disabledOracle") ){
            if( $("#SeleniumIde").is(":checked") ){
                $(this).hide();
                showLoaderPlayButton();

                if( typeof id != "undefined" && typeof nom != "undefined" && typeof jdd != "undefined" ){
                    EiServiceManager.record(nom, id, jdd, function(){
                        hideLoaderPlayButton();
                        self.show();
                    });
                }
            }
        }
        else{
            if( $("#SeleniumIde").is(":checked") ){
                alert("To use this fonctionality, you have to install or activate Kalifast service.");
            }
            else{
                alert("Recording is disabled for devices");
            }  
        }
    });

    /**
     * Action déclenchée sur clic du bouton STEP BY STEP dans le scénario/version...
     */
    $("#btnDebugScenarioInIde").click(function(event){
        event.preventDefault();

        var id = $(this).attr("data-player-id");
        var nom = $(this).attr("data-player-nom");
        var jdd = $(this).attr("data-player-jdd");
        var self = $(this);

        if( !$(this).hasClass("disabledOracle") ){
            if( $("#SeleniumIde").is(":checked") ){
                $(this).hide();
                showLoaderPlayButton();

                if( typeof id != "undefined" && typeof nom != "undefined" && typeof jdd != "undefined" ){
                    EiServiceManager.debug(nom, id, jdd, function(){
                        hideLoaderPlayButton();
                        self.show();
                    });
                }
            }
        }
        else{
            if( $("#SeleniumIde").is(":checked") ){
                alert("To use this fonctionality, you have to install or activate Kalifast service.");
            }
            else{
                alert("Step by step is disabled for devices");
            } 
        }
    });

    /**
     * Action déclenchée sur clic du bouton PLAY dans le scénario/version pour le sélénium IDE
     */
    $(document).delegate("#btnPlayScenarioInIde:not(.webDrivers)", "click", function(event){
        event.preventDefault();

        var id = $(this).attr("data-player-id");
        var nom = $(this).attr("data-player-nom");
        var jdd = $(this).attr("data-player-jdd");
        var self = $(this);

        if( !$(this).hasClass("disabledOracle") ){
            $(this).hide();
            showLoaderPlayButton();

            if( typeof id != "undefined" && typeof nom != "undefined" && typeof jdd != "undefined" ){
                EiServiceManager.play(nom, id, jdd, function(){
                    hideLoaderPlayButton();
                    self.show();
                });
            }
        }
        else{
            alert("To use this fonctionality, you have to install/activate Kalifast service and then refresh the page.");
        }
    });

    /**
     * Action déclenchée sur clic du bouton PLAY dans le scénario/version pour les drivers / devices
     */
    $(document).delegate("#btnPlayScenarioInIde.webDrivers", "click", function(event){
        event.preventDefault();

        var id = $(this).attr("data-player-id");
        var jdd = $(this).attr("data-player-jdd");
        var date = $('#ei_execution_stack_expected_date').val();
        date = date.replace(/\//g , "-");
        date = date.replace(" ", "*");
        date = date.replace(/:/g, "_");
        var nbExecs = $('#ei_execution_stack_nb_execs').val();
        if(nbExecs === "")
        {
            nbExecs = 1;
        }
        try{
            if( !$(this).hasClass("disabledOracle") ){
                if( typeof id != "undefined" && typeof jdd != "undefined" ){
                    if((parseFloat(nbExecs) == parseInt(nbExecs)) && !isNaN(nbExecs) && nbExecs > 0)
                    {
                        closeExecutionStackSlidePanel();
                        for(i = 1; i <= nbExecs; i++) { 
                            $("#webDriverDropdown input[name='webdriversChoice[]']:checked").each(function(e){
                                var datas = $(this).val();
                                var deviceId = datas.substring(0, datas.indexOf('/'));
                                var driverId = datas.substring(datas.indexOf('/') + 1, datas.lastIndexOf('/'));
                                var browserId = datas.substring(datas.lastIndexOf('/') + 1, datas.length);
                                addToExecutionStack(id, jdd, deviceId, driverId, browserId, date);
                            });
                        }
                        $("#btnSwitchExecutionStackPane").click();
                    }
                    else
                    {
                        alert('Wrong number of executions requested');
                    }
                }
            }
        }
        catch(err){
            console.log("Error on robot : ", err);
            alert("We are unable to run your test on robot");
        }
    });

    $("#btnPlayCampagneInIde").click(function(event){
        event.preventDefault();

        var id = $(this).attr("data-player-id");
        var start = $(this).attr("data-player-start");
        var end = $(this).attr("data-player-end");

        if( !$(this).hasClass("disabledOracle") ){
            if( typeof id != "undefined" && typeof start != "undefined" && typeof end != "undefined" && start != -1 && end != -1){
                if( $("#selLi").hasClass("checked") )
                {
                    /* Partie selenium IDE local */
                    $(this).hide();
                    showLoaderPlayButton();

                    EiServiceManager.launchCampaign(id, start, end);
                }
                else
                {
                    var date = $('#ei_execution_stack_expected_date').val();
                    date = date.replace(/\//g , "-");
                    date = date.replace(" ", "*");
                    date = date.replace(/:/g, "_");
                    var nbExecs = $('#ei_execution_stack_nb_execs').val();
                    if(nbExecs === "")
                    {
                        nbExecs = 1;
                    }
                    /* Partie devices */
                    closeExecutionStackSlidePanel();
                    for(i = 1; i <= nbExecs; i++) { 
                        $("#webDriverDropdown input[name='webdriversChoice[]']:checked").each(function(e){
                            var datas = $(this).val();
                            var deviceId = datas.substring(0, datas.indexOf('/'));
                            var driverId = datas.substring(datas.indexOf('/') + 1, datas.lastIndexOf('/'));
                            var browserId = datas.substring(datas.lastIndexOf('/') + 1, datas.length);
                            addCampaignToExecutionStack(id, start, end, deviceId, driverId, browserId, date);
                        });
                    }
                    $("#btnSwitchExecutionStackPane").click();
                }
            }
            else{
                alert("You need to define the start and end position to execute your campaign.");
            }
        }
        else{
            alert("To use this fonctionality, you have to install/activate Kalifast service and then refresh the page.");
        }
    });
});

$(window).load(function() {
    EiServiceManager.isServiceAvailable(initializeServiceInteractions);
});

/**
 *
 * @param serviceAvailable
 */
function initializeServiceInteractions(serviceAvailable){
    initializePlayersButtonsInteraction(serviceAvailable);

    if( serviceAvailable  == true){
        EiServiceManager.isExcelAvailable(initializeExcelInteraction);
    }
    else{
        initializeExcelInteraction(false);
    }
}

/**
 * Fonction appelée après lancement de firefox.
 *
 * @param idExecution
 * @param urlExecution
 */
function callbackPlayIDE(idExecution, urlExecution)
{
    console.log("CALLBACK IDE called with ", idExecution, urlExecution);

    if( typeof idExecution != "undefined" && typeof urlExecution != "undefined" )
    {
        // On débloque tout d'abord la fenêtre.
        debloquerCamaignExecWindow();

        // On met en place le rafraichissement des statuts de la campagne.
        changeExecutionCampaign(undefined, urlExecution);
    }
    else
    {
        hideLoaderPlayButton();
        $("#btnPlayScenarioInIde").show();
    }
}

/**
 * Fonction permettant de supprimer le jeu de données sélectionné.
 *
 * @param event
 */
function emptySelectedDataSet(event)
{
    event.preventDefault();

    changeCookieAppletJdd(null, null, $(this).attr("data-parent"));
    // Ainsi que l'applet de référence.
    changeAppletJdd(-1);
}

/**
 *
 * @param e
 */
function chooseScenarioDataSet(e)
{
    // On récupère l'ID et le NOM du jeu de données.
    var idDataSet = $(this).attr("data-id");
    var nomDataSet = $(this).attr("data-name");
    var idScenario = $(this).attr("data-parent");

    console.log("Jeu de données sélectionné : ", idDataSet, nomDataSet);

    $('#editDataSetStepBox').on('hidden.bs.modal', function () {
        if(typeof idDataSet != "undefined"){
            // On modifie les informations relatives aux cookies.
            changeCookieAppletJdd(idDataSet, nomDataSet, idScenario);
            // Ainsi que l'applet de référence.
            changeAppletJdd(idDataSet);
        }
    })

    $('#editDataSetStepBox').modal('hide');
}

/* Chargement de la box de choix d'un jeu de données lors de l'édition d'une step de campagne */
function loadScenarioDataSetTreeForStep(e){ 
    e.preventDefault();
    var elt=$(this);
    var result=true;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data : 'step_id='+elt.parent().parent().parent().parent().find('.node_id').val(),
        async: false,
        beforeSend: function() {},
        success: function(response) {
            $("#editDataSetStepBoxLink").attr("itemref", elt.attr('href'));
            $("#editDataSetStepBoxContent").empty().append(response);
        },
        error: function(response) { result=false;
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function(){
        if(result){ 
            $('#editDataSetStepBox').modal('show');
        }
    });
}

function initializePlayersButtonsInteraction(serviceAvailable)
{
    if( typeof EiServiceManager != "undefined" && serviceAvailable == true ){
        activatePlayersButtonsInteraction();
    }
    else{
        desactivatePlayersButtonsInteraction();
    }
}

/**
 * Méthode permettant d'activer le comportement des boutons.
 */
function activatePlayersButtonsInteraction()
{
    $("#btnRecordScenarioInIde, #btnDebugScenarioInIde, #btnPlayScenarioInIde, #btnPlayCampagneInIde")
        .removeClass("disabledOracle")
        .removeClass("webDrivers")
    ;
}

/**
 * Méthode permettant de désactiver le comportement des boutons et d'afficher un message
 *
 * @param message
 * @param exceptSelectors
 */
function desactivatePlayersButtonsInteraction(message, exceptSelectors)
{
    var selectors = ["#btnRecordScenarioInIde", "#btnDebugScenarioInIde", "#btnPlayScenarioInIde", "#btnPlayCampagneInIde"];
    var oSelectors = $(arrayDiff(selectors, exceptSelectors).join(","));

    message = typeof message == "undefined" ? "Java must be activate in your browser to use this fonctionality.":message;

    // On désactive.
    oSelectors.addClass("disabledOracle");
    oSelectors.removeClass("webDrivers");
    
    // On affiche le message
    /*oSelectors.popover({
        "html" : true,
        "trigger": "hover",
        "placement": "top",
        "content" : "<b style='color: red'>"+message+"</b>"
    });*/
}

/**
 * Méthode permettant d'initialiser les icones Excel et de détecter si excel est activé.
 */
function initializeExcelInteraction(excelExists)
{
    if( excelExists == true )
    {
        $(document).undelegate(".excel-open-logs", "click");
        $(document).undelegate(".excel-open-jdd", "click");

        $(".excel-open-logs img").removeClass("disabledOracle");
        $(".excel-open-jdd[data-id] img").removeClass("disabledOracle");

        $(document).delegate(".excel-open-logs", "click", function(event){

            event.preventDefault();

            var oLien = $(this);
            var datas = {
                test_set_id: $(this).attr("data-id"),
                preserve_original: false
            };

            oLien.find(".loaderPlayInExcel").removeClass("hide");
            oLien.find(".excel-icon-img").hide();

            $.post($(this).attr("href"), datas).done(function(data)
            {
                if( typeof data.error.code != "undefined" && data.error.code == 500 ){
                    alert("Please, refresh the window and sign in.")
                }
                else if( typeof data.error != undefined && data.error == true ){
                    alert(data.error);
                }
                else{
                    EiServiceManager.openExcel();
                }

                oLien.find(".loaderPlayInExcel").addClass("hide");
                oLien.find(".excel-icon-img").show();
            });

        });

        $(document).delegate(".excel-open-jdd", "click", function(event){
            event.preventDefault();

            var dataSetId = $(this).attr("data-id");

            if( typeof dataSetId != "undefined" && dataSetId != "" && dataSetId != -1 ){
                var oLien = $(this);
                var datas = {
                    data_set_id: dataSetId,
                    preserve_original: $(this).attr("data-preserve") != "undefined" && $(this).attr("data-preserve") == "original"
                };

                oLien.find(".loaderPlayInExcel").removeClass("hide");
                oLien.find(".excel-icon-img").hide();

                $.post($(this).attr("href"), datas).done(function(data)
                {
                    if( typeof data.error.code != "undefined" && data.error.code == 500 ){
                        alert("Please, refresh the window and sign in.")
                    }
                    else if( typeof data.error != undefined && data.error == true ){
                        alert(data.error);
                    }
                    else{
                        EiServiceManager.openExcel();
                    }

                    oLien.find(".loaderPlayInExcel").addClass("hide");
                    oLien.find(".excel-icon-img").show();
                });
            }

        });

        $(".excel-open-jdd[data-id]").each(function(index){
            if( $(this).attr("data-id") == -1 ){
                $(this).find(".excel-icon-img").addClass("disabledOracle");

                $(document).undelegate(".excel-open-jdd", "click");

                $(document).delegate(".excel-open-jdd", "click", function(event){
                    event.preventDefault();
                });
            }
        });
    }
    else{
        $(document).delegate(".excel-open-jdd", "click", function(event){
            event.preventDefault();

            alert("To use this functionnality, you have to install/activate Kalifast service and Excel Client. Then, you have to refresh the page.");
        });

        $(document).delegate(".excel-open-logs", "click", function(event){
            event.preventDefault();

            alert("To use this functionnality, you have to install/activate Kalifast service and Excel Client. Then, you have to refresh the page.");
        });
    }

    $(".excel-open-jdd").attrchange({
        trackValues: true,
        callback: function (event){

            // CAS pas de fichier excel sélectionné.
            if( event.attributeName == "data-id" && (event.oldValue == -1 || event.oldValue == null) && $(this).attr("data-id") > 0 ){
                EiServiceManager.isServiceAvailable(initializeServiceInteractions);
            }
            // CAS déselection du JDD.
            else if( event.attributeName == "data-id" && event.oldValue > 0 && $(this).attr("data-id") == -1 ){
                $(this).find(".excel-icon-img").addClass("disabledOracle");

                $(document).undelegate(".excel-open-jdd", "click");

                $(document).delegate(".excel-open-jdd", "click", function(event){
                    event.preventDefault();
                });
            }
        }
    });
}

function showLoaderPlayButton()
{
    $("#loaderPlayInIde").removeClass("hide");
}

function hideLoaderPlayButton()
{
    if( !$("#loaderPlayInIde").hasClass("hide") ){
        $("#loaderPlayInIde").addClass("hide");
    }
}

/**
 * Méthode permettant de gérer le cookie permettant de recenser les JDDs sélectionnés pour chaque scénario.
 *
 * @param idJdd
 * @param nomJdd
 * @param idScenario
 */
function changeCookieAppletJdd(idJdd, nomJdd, idScenario)
{
    // Ajout du data set dans les cookies.
    //=> Récupération des jeux de données sélectionnés pour les scénarios.
    var selected_jdds_scenarios_to_play = $.parseJSON($.cookie("jdds_scenarios_to_play"));

    //=> Si aucun cookie n'a déjà été créé, on crée le tableau.
    if( typeof selected_jdds_scenarios_to_play == "undefined" || selected_jdds_scenarios_to_play == null ){
        selected_jdds_scenarios_to_play = {};
    }

    //=> On ajoute le data set avec l'identifiant du scénario.
    selected_jdds_scenarios_to_play[idScenario] =  {
        "id" : idJdd,
        "name" : nomJdd
    };

    // Durée du cookie
    var date = new Date();
    var mois = 12;
    date.setTime(date.getTime() + (mois * 30 * 24 * 60 * 60 * 1000));

    $.cookie("jdds_scenarios_to_play", JSON.stringify(selected_jdds_scenarios_to_play), {expires: date, path: "/"});

    if( nomJdd == null ){
        $(".selectedDataSetToPlay").html('<i class="fa fa-file-text fa-lg"></i>&nbsp;No Data Set');
    }
    else{
        $(".selectedDataSetToPlay").html('<i class="fa fa-file-text fa-lg"></i>&nbsp;'+nomJdd+"&nbsp;<a href='#' id='btnEmptySelectedDataSet' title='Remove selected data set ?' data-parent='"+idScenario+"'><i class='fa fa-remove'></i></a>");
    }
}

function changeAppletJdd(jdd, type)
{
    if( typeof type == "undefined" ){
        $("#btnPlayScenarioInIde").attr("data-player-jdd", jdd);
        $("#btnDebugScenarioInIde").attr("data-player-jdd", jdd);
        $("#btnRecordScenarioInIde").attr("data-player-jdd", jdd);

        var boutonsExcels = $(".excel-open-jdd:not(.noUpdate)");

        boutonsExcels.attr("data-id", jdd);

        if( typeof boutonsExcels.attr("data-href") != "undefined" ){
            boutonsExcels.attr("href", boutonsExcels.attr("data-href"));
        }
    }
    else{
        $("#btnPlayCampagneInIde").attr("data-player-jdd", jdd);
    }
}