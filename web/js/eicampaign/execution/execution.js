rotateTimeout = null;
posStart = null;
posEnd = null;
intervalleRefresh = null;

$(document).ready(function() {

    $('.tooltipCampaignName').tooltip();
    $("#ei_campaign_graph_scenario_id").attr("disabled","disabled");
    //Ajout d'une campagne de tests à une livraison
    $(this).delegate(".showNodeChilds", "click", showNodeChilds);
    $(this).delegate("#campaignGraphTestSuiteAdd", "click", campaignGraphTestSuiteAdd);
    $(this).delegate("#campaignGraphDataSetSearch", "click", campaignGraphDataSetSearch);

    $(this).delegate(".chooseTestSuiteForCampaignGraphNode","click",chooseTestSuiteForCampaignGraphNode);
    $(this).delegate(".chooseDataSetForCampaignGraphNode","click",chooseDataSetForCampaignGraphNode);
    $(this).delegate("#ei_campaign_graph_step_type_id" ,"change" , refreshCampainGraphForm);
    /* Gestion d'ajout des steps sur une campagne */
    $(this).delegate("#addCampaignStep","click", addCampaignStep);
    $(this).delegate("#saveCampaignStep","click", saveCampaignStep);
    $(this).delegate("#campaignGraphForm","submit", saveCampaignStep);
    /* Edition d'une step de campagne */
    $(this).delegate(".editCampaignStep",'click', editCampaignStep);

    /* Réinitialisation des états d'une campagne */
    $(this).delegate("#btnRefreshCampaignGraphStates",'click', refreshCampaignGraphStates);
    $(this).delegate(".disabledOracle", "click", function(e){
        e.preventDefault();

        return false;
    });
    /* Chargement des fonctions non-exécutées d'une exécution de campagne */
    $(this).delegate("#loadUnexecutedFunctions","click",function(e){
        e.preventDefault(); 
        $("#executionFunctions").hide();
        loadEiAjaxActions($(this),$("#EiExecutedFunctionsLoader"),"json",true,{},loadUnexecutedFunctions,executionAjaxProcessErr);
    });
   

    /* Action sur Scroll */
    $(this).delegate(window,'scroll', navCampaignCiblingPage);

    // On met en place le rafraichissement des statuts de la campagne.
    intervalleRefresh = setInterval(function(){
        refreshCampaignGraphStates($("#btnRefreshCampaignGraphStates"));
    }, 5000);

    // AUTO SELECTION DU BOUTON START/END de la CAMPAGNE.
    if( !$("input[name=eicampaigngraph_start]").is(":checked") ){
        $("input[name=eicampaigngraph_start]").first().prop("checked", true);
    }

    if( !$("input[name=eicampaigngraph_end]").is(":checked") ){
        $("input[name=eicampaigngraph_end]").last().prop("checked", true);
    }

    /* "Play All" Scénarios => Actions sur choix de l'intervalle. */
    $(this).delegate("input[name=eicampaigngraph_start], input[name=eicampaigngraph_end]", "click", defineIntervalCampaignIDE);
    defineIntervalCampaignIDE();
    /*Sur changement de l'exécution, on appelle la fonction changeExecutionCampaign */
    $(this).delegate('.campaignExecution', 'change',changeExecutionCampaign);

    /*Sur changement du block type d'une step de campagne , on appelle cette fonction (changeBlocTypeId) */
    $(this).delegate('.CampaignBlockType', 'change',changeBlocTypeId);

    $.xhrPool = [];
    $.xhrPool.abortAll = function() {
        $(this).each(function(idx, jqXHR) {
            jqXHR.abort();
        });
        $.xhrPool = [];
    };

    $.ajaxSetup({
        beforeSend: function(jqXHR) {
            $.xhrPool.push(jqXHR);
        },
        complete: function(jqXHR) {
            if ($.xhrPool===undefined) window.location.href = window.location.pathname;

            var index = $.xhrPool.indexOf(jqXHR);
            if (index > -1) {
                $.xhrPool.splice(index, 1);
            }
        }
    });
});

//Récupération d'un formulaire d'ajout ou édition de step de campagne
function renderCampaignStepForm(elt,editMode){
    $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false,
            success: function(response) { 
                if(response.success){
                    $("#campaignStep").find('.campaignStepBody').empty().append(response.html);
                    if(editMode) $("#newCampaignStepLabel").text('Edit Step');
                }     
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        });
} 

//Edition d'une step de campagne
function editCampaignStep(e){
    e.preventDefault();
    var elt = $(this);

    openModal($("#campaignStep"));
    renderCampaignStepForm(elt,true);
}

//Ajout d'une step de campagne
function addCampaignStep(e){
    e.preventDefault();
    var elt = $(this);

    openModal($("#campaignStep"));
    renderCampaignStepForm(elt,false); 
}

/* Sauvegarde d'un step de campagne */
function saveCampaignStep(e){ 
    e.preventDefault();   var elt = $(this);
    $.ajax({
            type: 'POST',
            url: $('#campaignGraphForm').attr('action'),
            data: $('#campaignGraphForm').serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function() { },
            success: function(response) {  
                if(response.success){
                    if(response.updateMode){ 
                        $('.manualStepFile'+response.step_id).attr('href',response.html);
                        $('.manualStepFile'+response.step_id)
                                .empty()
                                .append('<i class="icon icon-file"></i> '+response.filename);
                    }
                        
                    else{//Mode création  
                        $("#campaignGraphList").find('tbody').append(response.html); 
                        $("#addCampaignStep").attr('href',response.new_create_url);
                    }
                    closeModal($("#campaignStep"));
                }    
                else
                    $("#campaignStep").find('.campaignStepBody').empty().append(response.html);
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        });
}

function refreshCampainGraphForm(e){  

    $.ajax({
            type: 'POST',
            url: $("#isStepAutomatizable").attr('itemref'),
            data : 'step_type_id='+ $(this).val(),
            dataType: 'json',
            async: false, 
            success: function(response) {
                if (response.success) {
                    if($("#campaignGraphAttachment").is(":visible")) $("#campaignGraphAttachment").hide('fast');
                    $("#campaignGraphTestSuite").show('fast');
                    $("#campaignGraphDataSet").show('fast');
                }
                else { 
                    $("#campaignGraphAttachment").show('fast');
                    if($("#campaignGraphTestSuite").is(":visible")) $("#campaignGraphTestSuite").hide('fast');
                    if($("#campaignGraphDataSet").is(":visible"))  $("#campaignGraphDataSet").hide('fast');
                }
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
    });
}

/* Choix d'un scénario pour une noeud du graphe de campagne */
function chooseTestSuiteForCampaignGraphNode(e){
    e.preventDefault();
    var elt = $(this);
    $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false, 
            success: function(response) {
                if (response.success) { 
                     //On récupère l'arbre des jeux de données du scénario
                    $("#campaignGraphDataSetAddBox").find('#arbre_jdd').replaceWith(response.html);
                    //On remplit l'input avec le numéro du scenario et le nom
                    $(":input[id=appendedInputTestSuiteButton]").val(response.test_suite_name); 
                    $(":input[id=ei_campaign_graph_scenario_id]").val(response.test_suite_id);  //Si on se trouve dans une step de campagne 
                    $(":input[id=ei_bug_context_scenario_id]").val(response.test_suite_id);    //Si on se trouve dans un context de bug
                    //On ferme la box du choix de scénario pour laisser plus de place au choix des jeux de données
                    $("#campaignGraphTestSuiteAddBox").hide('fast');
                    //On ouvre la box des jeux de données
                    $("#campaignGraphDataSet").show('fast');
                    $("#campaignGraphDataSetAddBox").show('fast');
                    
                    //On réinitialise l'input du jeu de données 
                    $(":input[id=ei_campaign_graph_data_set_id]").val('');//Si on se trouve dans une step de campagne 
                    $(':input[id=ei_bug_context_ei_data_set_id]').val('');//Si on se trouve dans un context de bug
                    
                    $("#appendedInputDataSetButton").val('');
                    $('#campaignGraphDataSetSearch').show();
                }
                else {
                    //alert(response.html);
                    //elt.show(); //On rend l'élément visualisable puisque la transaction a échoué
                }
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
        });
    
}
/* Choix d'un jeu de données (EiDataSet) pour une noeud du graphe de campagne */
function chooseDataSetForCampaignGraphNode(e){
    e.preventDefault();
    var elt = $(this);
    $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false, 
            success: function(response) {
                if (response.success) { 
                     //On récupère l'arbre des jeux de données du scénario 
                    //On remplit l'input avec le numéro du scenario et le nom
                    $(":input[id=appendedInputTestSuiteButton]").val(response.test_suite_name); 
                    $(":input[id=ei_campaign_graph_scenario_id]").val(response.test_suite_id); //Si on se trouve dans une step de campagne
                    $(":input[id=ei_bug_context_scenario_id]").val(response.test_suite_id); //Si on se trouve dans un context de bug
                    $(":input[id=appendedInputDataSetButton]").val(response.data_set_name); 
                    $(":input[id=ei_campaign_graph_data_set_id]").val(response.data_set_id); //Si on se trouve dans une step de campagne
                    $(":input[id=ei_bug_context_ei_data_set_id]").val(response.data_set_id); //Si on se trouve dans un context de bug
                    
                    //On ferme la box du choix de scénario pour laisser plus de place au choix des jeux de données
                    $("#campaignGraphDataSetAddBox").hide('fast');
                } 
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {  });
    
}
function loadUnexecutedFunctions(response, elt) {
    $("#EiExecutedFunctionsPanel").replaceWith(response.html);
    $("#EiExecutedFunctionsLoader").hide();
    $("#executionFunctions").show();
    if ($("#EiUnExecutedFunctions")) {
        $('#EiUnExecutedFunctions').dataTable({
            "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
            "bPaginate": true,
            "bFilter": false,
            "bLengthChange": false,
            "bInfo": false,
            "order": [[3, "desc"]],
            "iDisplayLength": 30,
        });
    }
}

/* Ouverture de la box d'ajout d'un scénario à un noeud du graphe de campagne */
function campaignGraphTestSuiteAdd(){
   if($("#campaignGraphTestSuiteAddBox").is(":visible"))
       $("#campaignGraphTestSuiteAddBox").hide('fast');
   else
       $("#campaignGraphTestSuiteAddBox").show('fast');
}

/* Ouverture de la box d'ajout d'un scénario à un noeud du graphe de campagne */
function campaignGraphDataSetSearch(){
   if($("#campaignGraphDataSetAddBox").is(":visible"))
       $("#campaignGraphDataSetAddBox").hide('fast');
   else
       $("#campaignGraphDataSetAddBox").show('fast');
}

/* Récupération des noeuds (EiNode) pour la création d'une line de CampaignGraph */
function showNodeChilds(e) {
    e.preventDefault();
    var elt = $(this);

    var children = elt.parent().parent().find(' > ul > li');
    if (elt.find('.iconNodeChildren').hasClass('hideNodeChildsImg')) {  
        elt.parent().parent().find('.node_childs').hide('fast');
        elt.find('.iconNodeChildren').attr('title', 'Expand this branch').addClass('showNodeChildsImg').removeClass('hideNodeChildsImg');
        elt.find('.iconNodeChildren').attr('src' ,'/images/icones/fleche-droite.png');
    } else {
        $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false, 
            success: function(response) {
                if (response.success) {
                    children.show('fast');
                    elt.find('img').attr('title', 'Close this node').addClass('hideNodeChildsImg').removeClass('showNodeChildsImg');
                    //On range le résultat dans la liste des utilisateurs assignés au sujet
                    elt.parent().parent().find('.node_childs').replaceWith(response.html);
                    elt.find('.iconNodeChildren').attr('src' ,'/images/icones/fleche-bas.png');
                } 
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {  }); 
    } 
}

function refreshCampaignGraphStates(e){
    var object = e;

    if( typeof e.target != "undefined"){
        e.preventDefault();

        object = $(this);
    }

    var url = object.attr("data-url");

    $('#body_div').block({
        message: '<h1>Processing</h1>',
        css: { border: '3px solid #a00' }
    });

    rotateTimeout = setInterval('rotateRefreshIcon();', 1100);

    updateCampaignGraphState(url);
}


//*********************************************//
//*******       GESTION APPLET/JS       *******//
//*********************************************//


/**
 * Fonction permettant de créer une exécution de campagne et de bloquer les actions
 * sur la fenêtre d'une campagne.
 *
 * @param profil
 * @param projet
 * @param campagne
 * @param callback
 * @returns {boolean}
 */
function createExecutionCampaign(profil, projet, campagne, callback){

    // On bloque dans un premier temps la fenêtre.
    bloquerCamaignExecWindow();

    oData = false;
    urlBase = $("#player_href_create_campaign_execution").val();

    // Traitement du choix en cas d'erreur.
    onErrorWidget = $("select[name=CampaignBlockType]");
    onErrorAct = onErrorWidget.find("option:selected").text().trim();
    onErrorActId = onErrorWidget.val();

    if( typeof urlBase != "undefined" ){
        // On recherche l'URL de base.
        params = window.location.href.split("/");
        file = params[3];

        // Exécution de la requête AJAX permettant de créer l'exécution.
        $.ajax({
            type: "post",
            dataType: "json",
            url: urlBase,
            data: {
                "projet": projet,
                "profil" : profil,
                "campagne": campagne,
                "position_debut": posStart,
                "position_fin": posEnd,
                "onError": {
                    "id": onErrorActId,
                    "label": onErrorAct
                }
            },
            success: function(data, textStatus, jqXHR){

                if( !data.erreur ){
                    oData = {
                        id: data.id,
                        url: data.url
                    };
                }
                else{
                    oData = false;

                    alert(data.erreur);
                }

                debloquerCamaignExecWindow();

                callback(oData);
            },
            error: function(error){
                console.log(error);

                oData = "error";

                debloquerCamaignExecWindow();
            }
        });
    }
    else{
        debloquerCamaignExecWindow();
    }

    return oData;
}

/**
 * Fonction permettant de bloquer la fenêtre contenant la campagne et de réinitialiser le timer.
 */
function bloquerCamaignExecWindow(){

    // On bloque le bloc div.
    $('#body_div').block({
        message: '<h1>Processing</h1>',
        css: { border: '3px solid #a00' }
    });

    // On supprime l'intervalle de rafraichissement.
    clearInterval(intervalleRefresh);
}

function debloquerCamaignExecWindow(){
    $('#body_div').unblock();
}


function updateCampaignGraphState(url){

    ajaxRequest = $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        async: false, 
        success: function(response) {
            if (response.success) {
                console.log(response);

                $("#sortCampaignSteps tbody tr").each(function(index){
                    var res = response.resultats[$(this).find("td.index").text().trim()];

                    $(this).find("td.stepState").html(res);

                    if( res == "Ok" ){
                        $(this).attr("class", "success");
                    }
                    else if( res == "Ko" ){
                        $(this).attr("class", "error");
                    }
                    else if( res == "Processing" ){
                        $(this).attr("class", "process");
                    }
                    else if( res == "Aborted" ){
                        $(this).attr("class", "aborted");
                    }
                    else{
                        $(this).attr("class", "");
                    }
                });

                clearInterval(rotateTimeout);

                /**
                 * On clôs le rafraîchissement si cela est demandé.
                 */
                if( typeof response.stop != "undefined" && response.stop ){
                    clearInterval(intervalleRefresh);
                }

                /**
                 * On met à jour les liens manquants pour les oracles si possible.
                 */
                if( typeof response.oracles != "undefined" ){
                    // On parcourt chaque élément.
                    $(".disabledOracle").each(function (){
                        // On récupère le TR du step.
                        trStep = $(this).parents("tr").first().attr("id");

                        // Si ce dernier est défini, on récupère son ID.
                        if( typeof trStep != "undefined"){
                            idStep = parseInt(trStep.replace("campaignGraphStep",""));

                            // On vérifie que l'on récupère bien un ID et qu'il correspond à quelque chose.
                            if( !isNaN(idStep) && typeof response.oracles[idStep] != "undefined" && response.oracles[idStep] != false ){
                                $(this).attr("href", response.oracles[idStep]);
                                $(this).removeClass("disabledOracle");
                                $(this).addClass("activatedOracle");

                                $(this).click(function(event){
                                    window.open($(this).attr("href"), '_blank');
                                });
                            }
                        }
                    });
                }

                $('#body_div').unblock();
            }
            else {
                $('#body_div').unblock();
            }
        },
        error: function(response) {
            $('#body_div').unblock();

            clearInterval(intervalleRefresh);

            console.log(response);

            if (response.status == '401')
                window.location.href = window.location.pathname;

            if (response.status != '0' && response.status != '200')
                alert('Error ! Problem when processing');
        }
    });
}

function rotateRefreshIcon(){
    $('.icon-refresh').rotate();
}

function openModal(modal){ 
    modal.modal("show");
}

function closeModal(modal){ 
    modal.modal("hide");
}

function defineIntervalCampaignIDE(e){
    var radioStartSel = $("input[name=eicampaigngraph_start]:checked");
    var radioEndSel = $("input[name=eicampaigngraph_end]:checked");
    // On récupère l'intervalle choisi.
    var tmpPosStart = radioStartSel.val();
    var tmpPosEnd = radioEndSel.val();

    var tmpRealPosStart = radioStartSel.parent().parent().index();
    var tmpRealPosEnd = radioEndSel.parent().parent().index();

    // On vérifie la cohérence.
    if( typeof tmpPosEnd != "undefined" && typeof tmpPosStart != "undefined" && tmpRealPosEnd >= tmpRealPosStart ){

        // Si c'est cohérent, on modifie la position de début/fin.
        posStart = tmpPosStart;
        posEnd = tmpPosEnd;
    }
    else if( posStart != null && posEnd != null ){

        // Sinon, on bloque la nouvelle valeur et on conserve l'ancienne.
        $("input[name=eicampaigngraph_start]").filter("[value="+tmpPosStart+"]").prop("checked", false);
        $("input[name=eicampaigngraph_start]").filter("[value="+posStart+"]").prop("checked", true);

        $("input[name=eicampaigngraph_end]").filter("[value="+tmpPosEnd+"]").prop("checked", false);
        $("input[name=eicampaigngraph_end]").filter("[value="+posEnd+"]").prop("checked", true);
    }

    // Si posStart & posEnd sont définis, on ajoute le paramètre dans l'objet.
    if( posStart != null && posEnd != null ){

        $("a[data-player-start]").each(function(index){
            $(this).attr("data-player-start", posStart);
            $(this).attr("data-player-end", posEnd);
        });
    }
}

function uploadCampaignGraphAttachment(e){  
    $.ajax({
            type: 'POST',
            url: '/uploadCampaignGraphAttachment.php', 
            secureuri: false,  
            success: function(response) {  },
            error: function(response) {  alert('Error ! Problem when processing');   }
        }).done(function() { });
}

function changeBlocTypeId(e){
    e.preventDefault();
    var elt = $(this);  
    $.ajax({
            type: 'POST',
            url: elt.find(":selected").attr('itemref'),
            dataType: 'json',
            async: false,
            beforeSend: function() { },
            success: function(response) {
                if (response.success) {   }
                else {   }
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {   });
}

/**
 *
 * @param e
 * @param url
 */
function changeExecutionCampaign(e, url){
    if( typeof e != "undefined") e.preventDefault();

    if( typeof url == "undefined" ){
        url = $(this).find(":selected").attr('itemref');
    }

    window.location.href = url;
}

/**
 * Permet de faire suivre la barre de sous-menu de la campagne tout au long du scroll.
 *
 * @param e
 */
function navCampaignCiblingPage(e){

    if( typeof $("#campaignSubHeader").offset() != "undefined" && ($("#campaignSubHeader").offset().top < $(document).scrollTop()) && !$("#campaignSubHeader").hasClass("scrolled") ){
        $("#campaignSubHeader").addClass("scrolled");
    }
    else if( typeof $("#campaignSubHeader").offset() != "undefined" && !($("#campaignSubHeader").offset().top < $(document).scrollTop()) && $("#campaignSubHeader").hasClass("scrolled") ){
        $("#campaignSubHeader").removeClass("scrolled");
    }

}

$.fn.rotate = function(until, step, initial, elt) {
    var _until = (!until)?360:until;
    var _step = (!step)?1:step;
    var _initial = (!initial)?0:initial;
    var _elt = (!elt)?$(this):elt;

    var deg = _initial + _step;

    var browser_prefixes = ['-webkit', '-moz', '-o', '-ms'];
    for (var i=0, l=browser_prefixes.length; i<l; i++) {
        var pfx = browser_prefixes[i];
        _elt.css(pfx+'-transform', 'rotate('+deg+'deg)');
    }

    if (deg < _until) {
        setTimeout(function() {
            $(this).rotate(_until, _step, deg, _elt); //recursive call
        }, 5);
    }
};

window.unload = function() {
    clearInterval(intervalleRefresh);

    $.xhrPool.abortAll();
};

function executionAjaxProcessErr(eltId,additionalDatas){
  alert('Error ! Problem when processing');  
}