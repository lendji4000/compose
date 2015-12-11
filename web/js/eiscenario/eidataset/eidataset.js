var oldDataSetSelectedVersion;

$(document).ready(function() {
    $(this).delegate(".majStepDataSet", "click", majStepDataSet);
    $(this).delegate("a.stepLineInContentDataSetTitle, a.externalDataSetAccessLink", "click", editDataSetTemplateInVersion);

    //************************************************************//
    //**********          AJOUT JEU DE DONNEES          **********//
    //************************************************************//

    $(this).delegate(".add_ei_data_set", 'click', function(e) {
        e.preventDefault();

        if( isDataSetModeModal() ){
            showEiDataSetContent(addEiDataSetEvent, $(this));
        }
        else{
            addEiDataSetEvent($(this));
        }
    });

    $(this).delegate("#eiSaveDataSetFolder", "click", function(event){
        event.preventDefault();

        var form = $("#ei_data_set_content > form");
        var formData = new FormData(document.getElementById("ei_data_set_content").getElementsByTagName("form")[0]);

        $("#xsl_helper").css("z-index", 1);
        $("#editDataSetStepBox > .modal-dialog").block();

        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: formData,
            mimeType:"multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                $("#ei_data_set_content").html(data.content);

                if( typeof data.success != "undefined" && data.success == true ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));
                }
                else if( typeof data.success != "undefined" && data.success == false && typeof data.title != "undefined" ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));
                }

                if( typeof data.sidebar != "undefined" ){
                    updateSidebarContent(data.sidebar);
                }

                $("#editDataSetStepBox > .modal-dialog").unblock();
            },
            dataType: "json"
        });
    });

    //********************************************************************************//
    //**********          CREATION D'UN DOSSIER DE JEUX DE DONNEES          **********//
    //********************************************************************************//

    $(this).delegate(".add_ei_data_set_folder", 'click', function(e) {
        e.preventDefault();

        if( isDataSetModeModal() ){
            showEiDataSetContent(addEiDataSetEvent, $(this));
        }
        else{
            addEiDataSetEvent($(this));
            submitEiDataSetForm();
        }
    });

    $(this).delegate("#eiSaveDataSet", "click", function(event){
        event.preventDefault();

        var isUpdate = $(this).hasClass("update") ? true:false;
        var form = isUpdate ? $("#ei_data_set_content form").first():$("#ei_data_set_content > form");
        var formData = new FormData(document.getElementById("ei_data_set_content").getElementsByTagName("form")[0]);

        $("#editDataSetStepBox > .modal-dialog").block();

        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: formData,
            mimeType:"multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                $("#ei_data_set_content").html(data.content);

                if( typeof data.success != "undefined" && data.success == true ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));
                }
                else if( typeof data.success != "undefined" && data.success == false && typeof data.title != "undefined" ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));
                }

                if( typeof data.sidebar != "undefined" ){
                    updateSidebarContent(data.sidebar);
                }

                $("#editDataSetStepBox > .modal-dialog").unblock();
            },
            dataType: "json"
        });
    });

    $(this).delegate("input[name=jdd_version_courante]", "change", function(event){
        var newJddReference = $(this).val();
        var oldJddReference = oldDataSetSelectedVersion;
        var templateSb = undefined;
        var idScenario, nomJdd;

        console.log("Changement de version de JDD", newJddReference, oldJddReference);

        $.ajax({
            type: "POST",
            url: $(this).attr("data-href"),
            data: { "newVersionId" : newJddReference, "oldVersionId" : oldJddReference },
            dataType: "json",
            success: function(data){
                if( typeof data.success != "undefined" && data.success == true ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));

                    // On récupère le template dans la sidebar.
                    templateSb = $(".linkSelectDataSetApplet[data-id="+oldJddReference+"]");
                    // On met à jour le data set id rattaché au template dans la sidebar.
                    templateSb.attr("data-id", newJddReference);
                    // On récupère l'id du scénario & le nom du template.
                    idScenario = templateSb.attr("data-parent");
                    nomJdd = templateSb.attr("data-name");

                    changeCookieAppletJdd(newJddReference, nomJdd, idScenario);
                    changeAppletJdd(newJddReference);
                }
                else if( typeof data.success != "undefined" && data.success == false && typeof data.title != "undefined" ){
                    insertFormMessage(data.title, data.message, !data.success, $("#ei_data_set_content"));
                }
            }
        });

        // On modifie l'ancien JDD.
        oldDataSetSelectedVersion = newJddReference;
    });

    //***********************************************************************************//
    //**********          MISE A JOUR D'UN DOSSIER DE JEUX DE DONNEES          **********//
    //***********************************************************************************//
    
    $(this).delegate(".data_set_folder", "click", function(e){
        e.preventDefault();

        if( !isDataSetModeModal() ){
            addEiDataSetEvent($(this));
            submitEiDataSetForm();
        }
    });
    
    $(this).delegate(".data_set", "click", function(e){
        e.preventDefault();

        addEiDataSetEvent($(this));
        submitEiDataSetForm();
    });

    $(this).delegate(".edit_node_folder", "click", function(e){
        e.preventDefault();

        showEiDataSetContent(addEiDataSetEvent, $(this));
    });

    $(this).delegate(".edit_node_child", "click", function(e){
        e.preventDefault();

        showEiDataSetContent(addEiDataSetEvent, $(this));
    });

    $(this).delegate("#editDataSetStepBoxContent .separator, #editDataSetStepBoxContent .panel-actions a.btn-close, #datasetCloseTab", "click", function(e){
        e.preventDefault();

        hideEiDataSetContent();
    });
    
    /* Ouverture et fermeture d'un noeud d'arbre */
    $(this).delegate(".hide_node_diagram_data_set", "click",  function(e){
        event_hide_node_diagram_data_set($(this));
        });
    $(this).delegate(".show_node_diagram_data_set", "click", event_show_node_diagram_data_set);
    
//    submitEiDataSetForm();

    setTimeout("openExternalDataSet()", 500);
});

function openExternalDataSet()
{
    //**********          CHARGEMENT JEU DE DONNEES         **********//
    //****************************************************************//

    var elt = $("#informations input[name=ei_scenario_id]");
    var cookieJdd = $.parseJSON($.cookie("open_jdd_in_scenario"));
    var allIsDefined = typeof elt != "undefined" && typeof cookieJdd != "undefined" && cookieJdd != null;

    if( allIsDefined && elt.val() == cookieJdd.scenarioId ){
        $("#editDataSetStepBox").on("shown.bs.modal", function(event){
            $(this).off("shown.bs.modal");

            exploreAndOpenDataSet();
        });

        $(".editDataSetStepBox").first().click();
    }
}

function exploreAndOpenDataSet()
{
    var cookieJdd = $.parseJSON($.cookie("open_jdd_in_scenario"));
    var jdd = $("a.edit_node_child[data-id="+cookieJdd.templateId+"]");

    if( jdd.length > 0 ){
        jdd.click();
    }
    else{
        $(".show_node_diagram_data_set").click();

        setTimeout("exploreAndOpenDataSet()", 1000);
    }
}

/**
 *
 * @param event
 */
function editDataSetTemplateInVersion(event)
{
    var datas = {
        "templateId": $(this).attr("data-id"),
        "scenarioId": $(this).attr("data-parent")
    };

    // Durée du cookie
    var date = new Date();
    date.setTime(date.getTime() + (60 * 1000));

    $.cookie("open_jdd_in_scenario", JSON.stringify(datas), {expires: date, path: "/"});

    return true;
}

/* Changement du jeu de données d'une step de campagne */
function majStepDataSet(e){
    e.preventDefault();  
    var elt=$(this);
    var result;
    var campaign_graph_id;
    var new_data_set_url;
    $.ajax({
            type: 'POST',
            url: elt.attr('href'), 
            dataType: 'json',
            async: false,
            beforeSend: function() {},
            success: function(response) { 
                result=response;
                if(response.success)  
                    { new_data_set_url=response.html; campaign_graph_id=response.campaign_graph_id; }
                    else alert('Error . Make sure data set is part of scenario');
            },
            error: function(response) { success=false;
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function(){ 
            if(result.success){ 
                var elt2=$(":input[value="+campaign_graph_id+"][class=node_id]").parent().parent()
                    .find('.stepLineInContentDataSetTitle');
                elt2.empty().append('<input class="step_jdd_id" type="hidden" value="'+result.data_set_id+'">'+elt.html()); 
                elt2.attr('href',new_data_set_url);
                    $('#editDataSetStepBox').modal('hide');
                    //Si on est dans la gestion des steps d'une campagne
                if($('#tabStepCampaigns').length) checkScenarioInCampaign(undefined,result.data_set_id,undefined);    
            } 
                
        }); 
}
function  event_show_node_diagram_data_set() {
    tree = $(this).parent().parent().find('.node_diagram');
    tree.load();

    $.ajax({
        url: $(this).attr('data-href'),
        type: "GET",
        async: false,
        dataType: 'json',
        success: function(data) {
            if (data.status == "error") {
                setToolTip(data.message, true);
            }
            else {
                tree.html(data.content);
                setToolTip("");
            }
        },
        error: function(e) {
            setToolTip("Unabled to node's children.", true);
        }
    }).done(function(){
        if($('#tabStepCampaigns').length)checkScenarioInCampaign(0,0,0);
    });

    $(this).parent().find('.hide_node_diagram_data_set').show();
    tree.show();
    $(this).hide();
}

function  event_hide_node_diagram_data_set(clickedObj) {
    clickedObj.parent().find('.show_node_diagram_data_set').show();
    clickedObj.parent().parent().find('.node_diagram').hide();
    clickedObj.hide();
}

function showEiDataSetContent(callback, args)
{
    // Déclaration du menu de navigation des data sets.
    var menuDS = $("#editDataSetStepBoxContent > .row > .ul_menu_dataset");
    var separatorDS = $("#editDataSetStepBoxContent > .row > .separator");
    var treeDS = $("#ei_data_set_tree");
    var contentDS = $("#ei_data_set_content");

    contentDS.hide();

    if( menuDS.length > 0 ){
        menuDS.animate({
            width: "-=200px"
        }, 500, function(e){
            callback(args);
        });

        separatorDS.css("height", menuDS.css("height"));
        separatorDS.show();

        treeDS.css("overflow", "hidden");
    }
}

function hideEiDataSetContent()
{
    // Déclaration du menu de navigation des data sets.
    var menuDS = $("#editDataSetStepBoxContent > .row > .ul_menu_dataset");
    var contentDS = $("#ei_data_set_content");
    var separatorDS = $("#editDataSetStepBoxContent > .row > .separator");
    var treeDS = $("#ei_data_set_tree");

    menuDS.animate({
        width: "+=200px"
    }, 700);

    contentDS.toggle("slide", { direction: "right" }, 300, function(e){
        contentDS.html("");
    });

    separatorDS.hide();

    treeDS.css("overflow", "scroll");
}

/**
 * Procédure permettant de mettre à jour le contenu de la sidebar tout en conservant son statut masqué/affiché.
 *
 * @param newContent
 */
function updateSidebarContent(newContent)
{
    // Déclaration de l'arbre des jeux de données ainsi que le menu.
    var treeDS = $("#ei_data_set_tree");
    var menuDS = $("#editDataSetStepBoxContent > .row > .ul_menu_dataset");

    // Remplacement du contenu.
    menuDS.replaceWith(newContent);

    treeDS = $("#ei_data_set_tree");
    menuDS = $("#editDataSetStepBoxContent > .row > .ul_menu_dataset");

    // Si la taille est inférieure à 200, on applique les filtres.
    if(menuDS.width() > 200){
        menuDS.css("width", "-=200px");
        treeDS.css("overflow", "hidden");
    }

}

function isDataSetModeModal(){
    // Déclaration du menu de navigation des data sets.
    var menuDS = $("#editDataSetStepBoxContent > .row > .ul_menu_dataset");

    return menuDS.length > 0;
}

function addEiDataSetEvent(link)
{
    var selectedDs;

    $.ajax({
        type: 'GET',
        url: link.attr("href"),
        async: true,
        dataType: 'json',
        beforeSend: function(e){
            $(".loaderEditDataSet").first().clone().removeClass("hide").appendTo("#ei_data_set_content");
            $("#ei_data_set_content").show();
        },
        success: function(e)
        {
            var dataSetContent = $("#ei_data_set_content");

            dataSetContent.toggle();
            dataSetContent.html(e.content);

            selectedDs = $("input[name=jdd_version_courante]");

            if (e.status === "ok") {  $("#xsl_helper").popover('show');  }
            else { setToolTip(e.message, true); }

            // Si JDD versions accessible, on stocke la version courante.
            if( selectedDs.length > 0 ){
                oldDataSetSelectedVersion = selectedDs.val();
            }

            if( isDataSetModeModal() ){
                dataSetContent.toggle("slide", { direction: "right" }, 500);
            }

            initializeExcelInteraction();
        },
        error: function(e) { setToolTip('An error occured. Unable to get data set form.', true); } 
    });
    
}

function submitEiDataSetForm() {  
    if ($('input[id=ei_data_set_file]').attr('type') === undefined) { 
        $("#ei_data_set_content").find('input[type="submit"]').first().bind("click", function(e) {
            e.preventDefault();
            var content = saveFormEvent($("#ei_data_set_content").find('form').first());
            $("#ei_data_set_content").html(content);
            submitEiDataSetForm();
        });
    }

}