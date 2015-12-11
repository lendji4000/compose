var timeout; 
 
$(function() {
    $('#datetimepickerSearchStatsMin ,#datetimepickerSearchStatsMax').datetimepicker({
        language: 'pt-BR', 
        pickTime: true
    }).on('show.bs.modal', function(event) { 
            event.stopPropagation();
        }); 
});
/* Bout de code permettant d'editer les popup dans le tinymce (Par exemple pour l'edition des notice de fonction )*/
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});
/* fin*/
$(document).ready(function() {
    
    $(this).delegate('div[data-type=state-success], div[data-type=state-failed], div[data-type=state-aborted]', 'click', filterByState);
    $(this).delegate('div[data-type=state-success], div[data-type=state-failed], div[data-type=state-aborted]', 'hover', function(){
        $(this).css("cursor", "pointer");
    });
    $(this).delegate("#searchFunctionStats","click",searchFunctionStats);
    $(this).delegate("#searchGeneralFunctionsStats","submit",searchFunctionStats);
    $("#alertBox").fadeOut(4500);

    activePopoversAndTooltip();
    setPopOverEventParam();
    stepTab = new Array();

    var currentMoveStep;
    var stepIndexBefore;
    var prevStep;

    $("#campaignContentList").sortable({
        helper: fixHelperModified,
        stop: updateIndex
    }); 
    
    $(this).delegate(".lien_survol_node", "mouseenter", showEditNodeLink);
    $(this).delegate(".lien_survol_node", "mouseleave", hideEditNodeLink);
    $(this).delegate(".lien_survol_node", "mouseenter", showAddNodeLink);
    $(this).delegate(".lien_survol_node", "mouseleave", hideAddNodeLink);
    $(this).delegate(".lien_survol_tree", "mouseenter", showAddScriptNodeLink);
    $(this).delegate(".lien_survol_tree", "mouseleave", hideAddScriptNodeLink);
    
    $(this).delegate(".hide_node_diagram", "click",  function(e){
        e.preventDefault();
        event_hide_node_diagram($(this));
        });
    $(this).delegate(".show_node_diagram", "click", event_show_node_diagram);
    $(this).delegate(".hide_node_diagram_check", "click",event_hide_node_diagram_check);
    $(this).delegate(".show_node_diagram_check", "click", event_show_node_diagram_check);
    $(this).delegate('.checkNode', 'click', event_checkNode);
    //$(this).delegate('.confirmSelectedNodeParent', 'click', event_confirmSelectedNodeParent);
    $(this).delegate('.confirmSelectedNodeParent', 'click', function (e) {
        e.preventDefault();
        var current_node_id = $('#modalDiagram').find('.current_node_id').val();
        var new_parent_id = $('#selectedNode').find('.new_parent_id').val();
        if (current_node_id == new_parent_id) {
            //si on choisit le même noeud comme parent du noeud courant
            setToolTip('Error : Object can\'t not be his own parent.', true);
            return false;
        }
        else {
            if (!$.trim($('#selectedNode').find('.new_parent_node_name').text()).length > 0 || !new_parent_id)
            {
                setToolTip('No Parent selected', true);
                return false;
            } else {
                if (!current_node_id) {
                    setToolTip('System error : Can\'t find object', true);
                    return false;
                }
                else {
                    var additionalDatas = {"current_node_id": current_node_id, "new_parent_id": new_parent_id}; 
                    loadEiAjaxActions($(this), $('.eiLoader'), "json", true, additionalDatas, event_confirmSelectedNodeParent, changeNodeParentAjaxProcessErr);
                }
            }

        }
    }); 
    $(this).delegate('.openFunctionInScript', 'click', openFunctionInScript);
    
 function openFunctionInScript(){   
     var elt=$(this);
        $.ajax({
            type: 'POST',
            url: elt.find('.openNodeUri').attr('itemref'),
            dataType: 'json',
            async: false, 
            success: function(response) {
                if (response.success) {   $("#ul_menu").replaceWith(response.html);  } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {   });
 }
    $('.fenetre').dialog({
        autoOpen: false,
        bgiframe: true,
        resizable: true,
        height: 'auto',
        width: 'auto',
        modal: true,
        position: 'center',
        overlay: {
            backgroundColor: '#00f',
            opacity: 0.5
        },
        buttons: {
            Annuler: function() {
                $(this).dialog('close');
            }
        }
    });
   
    
    //$(".pop").popover({placement: 'left', trigger: 'hover', html: true, delay: {show: 500, hide: 100}});
     
     
    $('#dataTableDefects ,#dataTableKalifasts ,#dataTableEnhancements ,#dataTableServiceRequest ,#dataTableOpenDeliveries, #EiPaginateList').dataTable({
		"sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
		"bPaginate": true,
		"bFilter": false,
		"bLengthChange": false,
		"bInfo": false
	}); 
        generateDataTable($('#EiGeneralStatsFunctionsTable'),30,7,"desc"); 
        generateDataTable($('#projectProfilesList'),30,1,"asc");  
        generateDataTable($('#EiPaginateBugHistoryList'),5,2,"desc");  
        generateDataTable($('#ItTableStatsPanel'),5,6,"desc");  
        generateDataTable($('#EiExecutionStats'),30,0,"desc");  
        generateDataTable($('#executionFunctions'),30,0,"desc");  

    $("#EiPaginateReportsList").dataTable({
        "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
        "bPaginate": true,
        "bFilter": false,
        "bLengthChange": false,
        "bInfo": false,
        "fnDrawCallback": initializeExcelInteraction,
        "order" : [[0, "desc"]]
    }); 
    
    /* Gestion de l'affichage du footer de la searchBox des subjects suivant le contexte */
    $(this).delegate('.eiPanel .btn-minimize','click',function(){
        if($(this).parents('.eiPanel').find('.panel-footer').is(':visible')){
            $(this).parents('.eiPanel').find('.panel-footer').hide();
        }
        else{
            $(this).parents('.eiPanel').find('.panel-footer').show();
        } 
    });
});

function searchFunctionStats(e){
    e.preventDefault();
    var elt= $(this); 
        $.ajax({
            type: 'POST',
            url: $("#searchGeneralFunctionsStats").attr("itemref"),
            data: $("#searchGeneralFunctionsStats").serialize(),
            async: true, 
            dataType: 'json',
            beforeSend: function (e) {
            $("#EiGeneralStatsFunctionsLoader").show();
            $("#EiGeneralStatsFunctionsTable,#EiGeneralStatsFunctionsTable_wrapper").hide();
        },
            success: function(response) {
               $("#EiGeneralStatsFunctionsPanel").replaceWith(response.html);
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { 
            $("#EiGeneralStatsFunctionsLoader").hide();
            $("#EiGeneralStatsFunctionsTable,#EiGeneralStatsFunctionsTable_wrapper").show();
        generateDataTable($('#EiGeneralStatsFunctionsTable'),30,7,"desc"); 
    }); 
}

function setPopOverEventParam(){
    $(".infos_param").popover();
}
function getUri() {
    return $(":input[class=url_depart][name=url_depart]").val();
} 
//Fonction d'activation des tooltips et popover
function activePopoversAndTooltip(){
    $('.popoverObjDesc,.popoverDesc').popover({
        html: 'true', 
        placement : 'top'
    }).click(function(e) { 
       //e.preventDefault(); 
       $(this).focus(); 
   });  
      
    $(".tooltipObjTitle").tooltip();
}


function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('/') + 1).split('&');
    for (var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

(function($) {
    $(document).ready(function() {
        var sujet = getUrlVars()["sujet"];
        $("input[name='subject']").val(sujet);
    });
})(jQuery);

/**
 * Fadeout object's background-color css property from colorStart to colorEnd at speed.
 * 
 * @param obj
 * @param colorStart
 * @param colorEnd
 * @param speed
 */
function fadeOutColor(obj, colorStart, colorEnd, speed) {
    obj.css('background-color', colorStart).animate({backgroundColor: colorEnd}, speed);
}

function showAddNodeLink() {
    $(this).find('.add_node_child').css('visibility', 'visible');
}

function hideAddNodeLink() {
    $(this).find('.add_node_child').css('visibility', 'hidden');
}

function showEditNodeLink() {
    $(this).find('.edit_node_child').css('visibility', 'visible');
    $(this).find('.edit_node_folder').css('visibility', 'visible');
}

function hideEditNodeLink() {
    $(this).find('.edit_node_child').css('visibility', 'hidden');
    $(this).find('.edit_node_folder').css('visibility', 'hidden');
} 
function  showAddScriptNodeLink() {
    $(this).find('.add_node_child').css('visibility', 'visible');
    $(this).find('.add_node_folder').css('visibility', 'visible');
    $(this).find('.nodeMoreInf').css('visibility', 'visible');
}
function hideAddScriptNodeLink() {
    $(this).find('.add_node_child').css('visibility', 'hidden');
    $(this).find('.add_node_folder').css('visibility', 'hidden');
    $(this).find('.nodeMoreInf').css('visibility', 'hidden');
}
function  event_show_node_diagram() {  
    tree = $(this).parent().parent().find('.node_diagram');
    tree.load();

    $.ajax({
        url: $(this).attr('data-href'),
        type: "GET",
        async: true,
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
        //Si on est dans la gestion des steps d'une campagne
        if($('#tabStepCampaigns').length)checkScenarioInCampaign(0,0,0);
    }); 
    tree.show();
    $(this).parent().find('.hide_node_diagram').show();
    $(this).hide();
}

function  event_hide_node_diagram(clickedObj) {
         
     $.ajax({
            type: 'GET',
            url: clickedObj.attr('data-href'),
            dataType: 'json',
            async: false,
            success: function(res) { 
                clickedObj.parent().find('.show_node_diagram').show();
                clickedObj.parent().parent().find('.node_diagram').hide();
                clickedObj.hide();
            },
            error: function(res) {
                setToolTip("An error occured. The node could not be closed.", true);
            }
        });
}




function  event_show_node_diagram_check() { 
    tree = $(this).parent().parent().find('.node_diagram_for_check'); 
    tree.load($(this).attr('data-href'));
    $(this).parent().find('.hide_node_diagram_check').show();
    tree.show();
    $(this).hide();
}

function event_hide_node_diagram_check(e) {
    e.preventDefault();
     var elt=$(this);
     $.ajax({
            type: 'GET',
            url: elt.attr('data-href'),
            dataType: 'json',
            async: false,
            success: function(res) {  
                elt.parent().find('.show_node_diagram_check').show();
                elt.parent().parent().find('.node_diagram_for_check').hide();
                elt.hide();
            },
            error: function(res) {
                setToolTip("An error occured. The node could not be closed.", true);
            }
        });
}


function event_checkNode() {
    $('#selectedNode').find('.new_parent_node_name').text($(this).text());
    $('#selectedNode').find('.new_parent_id').val($(this).parent().find('.node_id').val());
}

//Sur confirmation du choix du nouveau parent
function event_confirmSelectedNodeParent(response ,elt,additionalDatas) {   
    //Traitement du changement de parent
    setToolTip(response.html, !response.success);
            $('#modalDiagram').modal('hide');
            if(response.success){
            $(":input[class=node_id][value="+additionalDatas.current_node_id+"]").parent('.lien_survol_node').remove();
            $(":input[class=node_id][value="+additionalDatas.new_parent_id+"]").parent('.lien_survol_node').find('.show_node_diagram').click();
        } 
}

/* Plugin de détection des changements de textarea */
(function(a) {
    a.event.special.textchange = {
        setup: function() {
            a(this).data("lastValue", this.contentEditable === "true" ? a(this).html() : a(this).val());
            a(this).bind("keyup.textchange", a.event.special.textchange.handler);
            a(this).bind("cut.textchange paste.textchange input.textchange", a.event.special.textchange.delayedHandler)
        },
        teardown: function() {
            a(this).unbind(".textchange")
        },
        handler: function() {
            a.event.special.textchange.triggerIfChanged(a(this))
        },
        delayedHandler: function() {
            var c = a(this);
            setTimeout(function() {

                a.event.special.textchange.triggerIfChanged(c)
            },
                    25)
        },
        triggerIfChanged: function(a) {
            var b = a[0].contentEditable === "true" ? a.html() : a.val();
            b !== a.data("lastValue") && (a.trigger("textchange", [a.data("lastValue")]), a.data("lastValue", b))
        }
    };

    a.event.special.hastext = {
        setup: function() {
            a(this).bind("textchange", a.event.special.hastext.handler)
        },
        teardown: function() {
            a(this).unbind("textchange", a.event.special.hastext.handler)
        },
        handler: function(c, b) {
            b === "" && b !== a(this).val() && a(this).trigger("hastext")
        }
    };

    a.event.special.notext = {
        setup: function() {
            a(this).bind("textchange",
                    a.event.special.notext.handler)
        },
        teardown: function() {
            a(this).unbind("textchange", a.event.special.notext.handler)
        },
        handler: function(c, b) {
            a(this).val() === "" && a(this).val() !== b && a(this).trigger("notext")
        }
    }
})(jQuery);

function majStepInBase(stepTab){ 
        $.ajax({
            type: 'POST',
            url: $('#majStepInBase').attr('itemref'),
            data : 'stepTab=' + stepTab ,
            dataType: 'json',
            async: false,
            beforeSend: function() {},
            success: function(response) {
                if (response.success) {   
                }
                else{ 
                    alert('Update position steps failed ...');
                } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
            stepTab.length=0 ; //alert(stepTab);
        });
    }
    
    var fixHelperModified = function(e, tr) {
    
    prevStep=null;
    if (tr.find('#lighter_in_camp_content').length > 0){ 
        var prev=tr.prev();
        if(prev.length > 0) prevStep=prev; 
        else prevStep=$('#stepLineInContentBoxFirst') 
    }
    
    var $originals = tr.children();
    var $helper = tr.clone(); 
    id=$originals.eq(3).text(); 
    currentMoveStep=tr;
    currentMoveStep.find('.checked_place_to_add_in_camp_content').hide(); //On masque le curseur pendant le déplacement de l'objet
    stepIndexBefore=tr.index();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
    updateIndex = function(e, ui) {
        $('.stepLineInContentBoxIndex', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
            //alert($(this).parent().find('.node_id').val());
            stepTab[i]=$(this).parent().find('.node_id').val();
        });
        if(currentMoveStep.index()!==stepIndexBefore){
            majStepInBase(stepTab);
            if(prevStep!=null ){//alert('bgood');
                prevStep.find('.checked_place_to_add_in_camp_content ')
                            .attr('id', 'lighter_in_camp_content');
                currentMoveStep.find('.checked_place_to_add_in_camp_content ')
                               .attr('id', '');
            }
        }
         currentMoveStep.find('.checked_place_to_add_in_camp_content').show(); //On montre le curseur après le déplacement de l'objet
        
    };

 
/* headerBreadcrumb, déclaré sans "var", est une variable globale */
headerBreadcrumb = (function(larg ,haut){
 
    /* ces variables ne sont pas accessibles depuis l'extérieur */
    var headerBreadcrumb = {};
    var variablePrivee = true;
 
    /* ces fonctions sont accessibles seulement depuis pouetGenerator */
    headerBreadcrumb.alertLarg = function(){
        alert(larg);
    }
 
    headerBreadcrumb.pouet = function(){
        if (variablePrivee) {
            alert('pas pouet');
        }
    }
    headerBreadcrumb.activePopoverOnOpenDel=function(){ 
         $('.accessDelivery').each(function(){
             var elt=$(this);
             var completeTitle=$(this).find('.text').find('small').text();
           if(completeTitle && completeTitle.length>=17){ 
                elt.addClass('popoverObjDesc');
                elt.attr( 'data-trigger','hover');
                elt.attr( 'data-placement','top');
                elt.attr( 'data-toggle','popover'); 
                activePopoversAndTooltip(); 
           } 
        });
    }
    headerBreadcrumb.resizeHeight=function(){
        if(haut <=768){ 
        }
    }
    headerBreadcrumb.minifySize = function(){
        if (larg >1500 ) {  //Grande résolutions  
        }
        if (larg >1300 && larg<=1500 ) {  //Grande résolutions 
        }
        if (larg >1200 && larg <=1300 ) {  //Grande résolutions  
        }
        if (larg>=1100 && larg <=1200 ) {  //Moyennes résolutions  
        }
        if (larg>=993 && larg <=1099 ) {  //Moyennes résolutions    
        }
        if (larg>=768 && larg <=992 ) {  //Petites résolutions  
        }
        if (larg <768 ) {  //Petites résolutions 
        }
    }
 
    /* On rend notre headerBreadcrumb accessible au reste du monde
        en la retournant, ce qui va mettre la référence dans le headerBreadcrumb
        qui est la variable globale.
    */
    return headerBreadcrumb;
 
/* On passe 'pouet' en paramètre, il va se retrouver dans chaine_du_pouet */
})($(window).width(),$(window).height())
 
 $(document).ready(function() {
     /* On peut utiliser notre générateur de breadcrumb au même niveau */
     headerBreadcrumb.minifySize();
     headerBreadcrumb.resizeHeight();
     headerBreadcrumb.activePopoverOnOpenDel();
 });



/* Partie menu */
$(document).ready(function() {
    
    $('.nav-header').click(function(e){
        $(this).parent().children('li').each(function(i){
            if(i>=2){
                $(this).toggle();
            }
        });
    })
    
    $('#liste_version_choice').change(function() {
        var str;
        $("#liste_version_choice > option:selected").each(function() {
            str = $(this).attr('value').toString() + " ";
        });
        window.location.href = getRelocateForVersion(str);
    });

    $('#liste_scenario_choice').change(function() {
        //window.location.href = getRelocate();
        if ($('#liste_scenario_choice').val() != "")
            $('#liste_scenario_form').submit();

    });

    $('#liste_projet_choice').change(function() {
        window.location.href = $(":input[class=url_prefix][name=url_prefix]").val()
                + "/eiprojet/forwardToChoice/"
                + $(this).val();

    });

    $(this).delegate(".show_sub_menu_user", "click", event_show_sub_menu_user);
    $(this).delegate(".show_sub_menu_options", "click", event_show_sub_menu_options);
    $(this).delegate(".show_sub_menu_doc", "click", event_show_sub_menu_doc);
    $(this).delegate(".hide_sub_menu_user", "click", event_hide_sub_menu_user);
    $(this).delegate(".hide_sub_menu_options", "click", event_hide_sub_menu_options);
    $(this).delegate(".hide_sub_menu_doc", "click", event_hide_sub_menu_doc);



    $(".liste_users_moins_info").toggle();
    $(".details_projet_moins_info").toggle();

    $("#derouler_detail_projet").click(function(e) {
        $(this).children(".details_projet_plus_info").toggle();
        $(this).children(".details_projet_moins_info").toggle();
        $("#detail_projet .to_hide").toggle();
    });

    $("#derouler_liste_utilisateurs").click(function(e) {
        $(this).children(".liste_users_plus_info").toggle();
        $(this).children(".liste_users_moins_info").toggle();
        $(".liste_utilisateurs .to_hide").toggle();
    });
});

function event_show_sub_menu_user() {
    $(this).parent('li').find('.subMenuUser').show();
    $(this).parent('li').find('.hide_sub_menu_user').show();
    $(this).parents('#ul_header').find('.subMenuOptions').hide();
    $(this).parents('#ul_header').find('.hide_sub_menu_options').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_options').show();
    $(this).parents('#ul_header').find('.subMenuDoc').hide();
    $(this).parents('#ul_header').find('.hide_sub_menu_doc').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_doc').show();
    $(this).hide();
}
function event_show_sub_menu_options() {
    $(this).parent('li').find('.subMenuOptions').show();
    $(this).parent('li').find('.hide_sub_menu_options').show();
    $(this).parents('#ul_header').find('.subMenuUser').hide();
    $(this).parents('#ul_header').find('.hide_sub_menu_user').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_user').show();
    $(this).parents('#ul_header').find('.subMenuDoc').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_doc').show();
    $(this).parents('#ul_header').find('.hide_sub_menu_doc').hide();
    $(this).hide();
}
function event_show_sub_menu_doc() {
    $(this).parent('li').find('.subMenuDoc').show();
    $(this).parent('li').find('.hide_sub_menu_doc').show();
    $(this).parents('#ul_header').find('.subMenuUser').hide();
    $(this).parents('#ul_header').find('.hide_sub_menu_user').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_user').show();
    $(this).parents('#ul_header').find('.subMenuOptions').hide();
    $(this).parents('#ul_header').find('.hide_sub_menu_options').hide();
    $(this).parents('#ul_header').find('.show_sub_menu_options').show();
    $(this).hide();
}
function event_hide_sub_menu_user() {
    $(this).parent('li').find('.subMenuUser').hide();
    $(this).parent('li').find('.show_sub_menu_user').show();
    $(this).hide();
}
function event_hide_sub_menu_options() {
    $(this).parent('li').find('.subMenuOptions').hide();
    $(this).parent('li').find('.show_sub_menu_options').show();
    $(this).hide();
}
function event_hide_sub_menu_doc() {
    $(this).parent('li').find('.subMenuDoc').hide();
    $(this).parent('li').find('.show_sub_menu_doc').show();
    $(this).hide();
}
function arrayDiff(a, b) {

    if( typeof a != "undefined" && typeof b == "undefined" ){
        return a;
    }
    else if( typeof a == "undefined" && typeof b != "undefined" ){
        return b;
    }

    var seen = [], diff = [];
    for ( var i = 0; i < b.length; i++)
        seen[b[i]] = true;
    for ( var i = 0; i < a.length; i++)
        if (!seen[a[i]])
            diff.push(a[i]);
    return diff;
}
/**
 * Renvoie l'URL vers laquelle rediriger.
 * @param module le module
 * @param action l'action a déclencher
 * @param id_scenario l'identifiant du scenario
 */
function getRelocate(module, action, id_scenario) {

    if (id_scenario == '-1')
        id_scenario = '';
    else
        id_scenario = '/' + id_scenario;

    if ($(':input[name=profile_id]').val() == 0 || $(':input[name=profile_ref]').val() == 0) {
        var profile_name = "profil";
        var profile_id = '0';
        var profile_ref = '0';
    } else {
        var profile_name = $(':input[id=profile_name][name=profile_name]').val();
        var profile_id = $(':input[id=profile_id][name=profile_id]').val();
        var profile_ref = $(':input[id=profile_ref][name=profile_ref]').val();
    }

    return $(":input[class=url_prefix][name=url_prefix]").val()
            + "/Project/"
            + $(":input[id=project_id][name=project_id]").val() + "/"
            + $(":input[id=project_ref][name=project_ref]").val() + "/"
            + profile_name + "/"
            + profile_id + "/"
            + profile_ref
            + '/' + module + '/' + action + id_scenario;
}

/**
 * Redirige depuis le changement de sélection dans la liste
 * liste_scenario_choiche
 */
function getRelocateFromMenu() {
    var str;
    $("#liste_scenario_choice > option:selected").each(function() {
        str = $(this).attr('value').toString() + " ";
    });

    return getRelocate('eiscenario', 'forwardToEdit', str);
}

/**
 * Retourne l'URL de redirection vers la version passée en paramètres.
 * 
 * @param id_version Identifiant de la version à copier
 */
function getRelocateForVersion(id_version) {
    var id_scenario = $('#id_scenario').val();
    url = getRelocate('eiversion', 'edit', id_scenario);
    return url + '/id_version/' + id_version;
}

/**
 * Affiche un message dans la toolTip
 * @param {string} msg
 * @param {bool} error
 * @returns {undefined}
 */
function setToolTip(msg, error) {
    var className = "flash_msg_success";
    var classToRemove = "flash_msg_error";
    if(error){
        className = "flash_msg_error";
        classToRemove = "flash_msg_success";
    }
        
    $('#toolTips').removeClass(classToRemove);
    $('#toolTips').addClass(className);
    $("#toolTips").text(msg);
    $("#toolTips").show();
}

/**
 * Affiche un message en réponse à un formulaire (succès/échec) et ajoute un cadre à l'objet passé en paramètre.
 *
 * @param titre
 * @param message
 * @param isError
 * @param object
 * @param withDelay
 */
function insertFormMessage(titre, message, isError, object, withDelay)
{
    var classname = "alert-success";
    var html = '';
    var d = new Date();
    var n = d.getTime();

    if( typeof isError != "undefined" && isError == true ){
        classname = "alert-danger";
    }

    html = "<div class='alertBox' date-time='"+n+"'><div class=\"alert "+classname+"\" role=\"alert\">" +
        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">×</span><span class=\"sr-only\">Close</span></button>" +
        "<strong>"+titre+"</strong> "+message+"" +
        "</div></div>";

    html = $.parseHTML(html);

    object.prepend(html);

    if( typeof withDelay != "undefined" ){
        $(".alertBox[date-time="+n+"]").show().delay(withDelay).fadeOut(1000);
    }
}

/**
 *
 * @param key
 * @param value
 * @returns {*}
 */
function updateQueryStringParameter(uri,key,value)
{
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}

function filterByState(event)
{
    if( $(this).css("opacity") == 1 ){
        $(this).css("opacity", 0.6);
    }
    else{
        $(this).css("opacity", 1);
    }

    var opaciteSucc = $("div[data-type=state-success]").css("opacity");
    var opaciteFail = $("div[data-type=state-failed]").css("opacity");
    var opaciteAbor = $("div[data-type=state-aborted]").css("opacity");

    if( opaciteSucc == 1 ){ $("tr.state-success").show(); }else{ $("tr.state-success").hide(); }
    if( opaciteFail == 1 ){ $("tr.state-failed").show(); }else{ $("tr.state-failed").hide(); }
    if( opaciteAbor == 1 ){ $("tr.state-aborted").show(); }else{ $("tr.state-aborted").hide(); }
}

/* Génération des dataTables pour les tables triables bootstrap */
 function generateDataTable(elt , row, orderNumber, orderType){
        if(elt){
            elt.dataTable({
                destroy: true,
		"sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
		"bPaginate": true,
		"bFilter": false,
		"bLengthChange": false,
		"bInfo": false,
                "order" : [[orderNumber, orderType]],
                "iDisplayLength": row,
                columnDefs: [{ // Colonne à ne pas trier (porte la classe datatable-nosort) 
                    targets: "datatable-nosort",
                    orderable: false
                }]
	});
        } 
    } 


/* Création d'une fonction générique des traitement de formulaire ajax */
function loadEiAjaxForm(formId,formLoader,dataType,asyncMethod,callB){
      
    if(typeof(formId.attr('action')) !== 'undefined'){
        $.ajax({
        type: 'POST',
        url: formId.attr('action'),
        data: formId.serialize(),
        dataType: dataType,
        async: asyncMethod,
        beforeSend: function () {
            if(formLoader.length>0) formLoader.show();
        },
        success: function(response){
            callB(response); //Callback pour la suite de l'excution de la méthode ayant envoyer le formulaire
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
        if(formLoader.length>0) formLoader.hide();  //On masque le loader eventuellement invoqué
    }); 
    }
    
}


/* Méthode générique d'appel d'actions ajax en asynchrone ou pas  générique des traitement de formulaire ajax */
function loadEiAjaxActions(eltId,eltLoader,dataType,asyncMethod,additionalDatas,callB,callBErr){   
    var data;
    if(jQuery.isEmptyObject(additionalDatas)) {  
       data ={} ;     }
    else data=additionalDatas;
    if(typeof(eltId.attr('itemref')) !== 'undefined'){
        $.ajax({
        type: 'POST',
        url: eltId.attr('itemref'),
        data: data ,
        dataType: dataType,
        async: asyncMethod,
        beforeSend: function () {
            if(eltLoader.length>0) eltLoader.show();
        },
        success: function(response){
            callB(response,eltId,additionalDatas); //Callback pour la suite de l'excution de la méthode ayant envoyer le formulaire
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname; 
            callBErr(eltId,additionalDatas); 
        }
    }).done(function () {
        if(eltLoader.length>0) eltLoader.hide();  //On masque le loader eventuellement invoqué
    }); 
    }
    
}

function changeNodeParentAjaxProcessErr(eltId,additionalDatas){
  setToolTip('An error occured while changing the parent node.', true);
  return false;
}