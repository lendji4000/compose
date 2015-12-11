rotateTimeout = null;
posStart = null;
posEnd = null;
intervalleRefresh = null;
authors = null;
current_flag = null; /* Flag courant lors de l'édition d'une campagne */
$(document).ready(function() {
    //Contenu d'une campagne ( steps)
    $(this).delegate(".show_camp_steps", "click", show_camp_steps);
    $(this).delegate(".hide_camp_steps", "click", hide_camp_steps);
    //Contenu d'un sujet (campagnes)
    $(this).delegate(".show_sub_camps", "click", show_sub_camps);
    $(this).delegate(".hide_sub_camps", "click", hide_sub_camps);
    //Contenu d'une livraison (campagnes + sujets)
    $(this).delegate("#show_del_camps", "click", show_del_camps);
    $(this).delegate("#hide_del_camps", "click", hide_del_camps);
    /* Insertion d'une campagne au contenu */
    $(this).delegate(".addStepInContent", "click", addStepInContent);
    /* Insertion d'une action manuelle à une campagne */
    $(this).delegate("#addManualStepInContent", "click", addStepInContent);
    /* Changement du curseur pour l'ajout d'un step à une campagne */
    $(this).delegate(".checked_place_to_add_in_camp_content", "click", checked_place_to_add_in_camp_content);
    $(this).delegate(".deleteCampaignStepWithoutRedirect", "click", deleteCampaignStepWithoutRedirect);

    //Chargement de la box de recherche d'une livraison pour les steps
    $(this).delegate("#deliverySearchBoxForSteps", "show.bs.modal", loadDeliverySearchBoxForStepsContent);
    
    $(this).delegate("#loadDelForStepsForm", "click", loadDelForStepsForm);
    $(this).delegate(".loadDelForStepsLink", "click", loadDelForStepsLink);
    /* Choix d'une livraison dans la search box des steps */
    $(this).delegate(".select_del_for_steps", "click", select_del_for_steps);

    /* Choix d'un sujet dans la search box des steps*/
    $(this).delegate(".select_sub_for_steps", "click", select_sub_for_steps);
    //Chargement de la box de recherche d'un sujet pour les steps
    $(this).delegate("#subjectSearchBoxForSteps", "show.bs.modal",loadSubjectSearchBoxForStepsContent) ; 
    $(this).delegate("#loadSubForStepsForm", "click", loadSubForStepsForm);
    $(this).delegate(".loadSubForStepsLink", "click", loadSubForStepsLink);

    /* Choix d'une campagne dans la search box des steps*/
    $(this).delegate(".select_camp_for_steps", "click", select_camp_for_steps);
    //Chargement de la box de recherche d'un sujet pour les steps

    $(this).delegate("#campaignSearchBoxForSteps", "show.bs.modal", loadCampaignSearchBoxForStepsContent);
    $(this).delegate(".loadCampForStepsLink", "click", loadCampForStepsLink);

    /* Campaign content*/
    $(this).delegate(".stepLineInContentBoxMoinsInfo", "click", stepLineInContentBoxMoinsInfo);
    $(this).delegate(".stepLineInContentBoxPlusInfo", "click", stepLineInContentBoxPlusInfo);
    $(this).delegate("#collapseSteps", "click", eventCollapseSteps);
    $(this).delegate("#extendSteps", "click", eventExtendSteps);
    //Gestion des variations sur la description d'une step pour les sauvegardes automatiques
    $(this).delegate('.stepLineInContentDescContent', 'keypress', function(e) {
        var elt = $(this); //Récupération de l'élément 
        $(this).bind('textchange', {
            elt: elt
        }, function(e, previousText) {
            changeStepDescEvent(elt, previousText);
        });
    });
    /* Gestion des flags */
    $(this).delegate(".setFlagForCampaign", "click", setFlagForCampaign);
    $(this).delegate(".setCommentForCampaign", "click", setCommentForCampaign);
    $(this).delegate("#saveCommentForCampaign", "click", saveCommentForCampaign);

    /* Gestion des ajouts multiples de step à une campagne */
    $(this).delegate(".check_all_steps_for_mult_act", "change", check_all_steps_for_mult_act);
    $(this).delegate("#addManyStepInContent", "click", addManyStepInContent);

});

/* Gestion des ajouts multiples de step à une campagne */
function check_all_steps_for_mult_act() {
    if ($(this).attr("checked"))
    {
        $(this).parents('.rightSideStepsListOfCampaignContent').find('.check_step_for_mult_act').attr('checked', 'checked');
    }
    else
    {
        $(this).parents('.rightSideStepsListOfCampaignContent').find('.check_step_for_mult_act').removeAttr('checked');
    }
}
/* Ajout de plusieurs steps à la campagne */
function addManyStepInContent(e) {
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $('#current_campaign_id').val();
    var before_step_id = $("#lighter_in_camp_content").find(".lighter_in_camp_content_value").val();
    var selectStepTab = new Array();
    var result, k = 0;
    $('.check_step_for_mult_act').each(function() {
        if ($(this).is(':checked')) {
            selectStepTab[k] = $(this).val();
            k++;
        }
    });
    if (selectStepTab.length > 0) { //alert(selectStepTab); alert(current_campaign_id+"/"+before_step_id);
        $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            data: 'current_campaign_id=' + current_campaign_id + '&selectStepTab=' + selectStepTab + '&before_step_id=' + before_step_id,
            dataType: 'json',
            async: false,
            beforeSend: function() {
            },
            success: function(response) {
                result = response.success;
                if (response.success) {
                    var elt = $("#lighter_in_camp_content");
                    if (elt.parent().attr('id') === 'stepLineInContentBoxFirst') {
                        $('#campaignContentList').prepend(response.html);
                    }
                    else {
                        elt.parent().after(response.html);
                    }

                    elt.attr('id', '');
                }
                else
                    alert(response.html);
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
            if (result) {
                //On décoche les éléments précedemment cochés 
                $('.check_step_for_mult_act').each(function() {
                    if ($(this).is(':checked')) {
                        $(this).removeAttr('checked');
                    }
                });
                //Si on est dans la gestion des steps d'une campagne
                if($('#tabStepCampaigns').length)checkScenarioInCampaign(0, 0, 0);
            }
        });
    }
    else {
        alert('Select at least one step to process ...');
    }
}

function setCommentForCampaign() {
    var elt = $(this);
    var link = elt.data('id');
    current_flag = elt;
    $("#setCommentForCampaignBox").find("#setCommentForCampaignBoxContent").
            find('textarea').
            replaceWith('<textarea class="form-control" >' + elt.
            find('textarea').text() + '</textarea>');
    $("#setCommentForCampaignBox").find('#saveCommentForCampaign').attr('href', link).parent().parent().parent().parent().modal('show');
}

/*Sauvegarde du commentaire sur un flag de campagne */
function saveCommentForCampaign(e) {
    e.preventDefault();
    var elt = $(this);
    var comment = $("#setCommentForCampaignBoxContent").find('textarea').val();
    //alert(comment);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data: 'campaign_id=' + $('#current_campaign_id').val() + '&comment=' + comment,
        dataType: 'json',
        async: false,
        beforeSend: function() {
        },
        success: function(response) {
            if (response.success)
                current_flag.replaceWith(response.html);
            else
                alert(response.html);
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {//current_flag.length=0;
    });
}
/*Définition d'un statut pour le flag d'une campagne */
function setFlagForCampaign(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data: 'campaign_id=' + $('#current_campaign_id').val(),
        dataType: 'json',
        async: false,
        beforeSend: function() {
        },
        success: function(response) {
            if (response.success)
                elt.replaceWith(response.html);
            else
                alert(response.html);
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
/* Fonction permettant de visualiser les changements de la commande et de provoquer les sauvegardes automatiques */
function changeStepDescEvent(elt, previousText) {
    if ($.trim(previousText) != $.trim(elt.val())) {
        //On vérifie qu'on est dans le cas d'une mise à jour du script 
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            eventStepDesc(elt);
        }, 300);
    }

}


/* Mise à jour d'un script déjà existant .Vue que l'utilisateur ne peut modifier que la description du script,
 on s'intéresse uniquement à cette dernière . */
function eventStepDesc(elt) {
    if (elt !== undefined) {
        var desc = elt.val();
        $.ajax({
            type: "POST",
            data: 'step_desc=' + desc,
            url: elt.parents('.stepLineInContentDesc').find('.stepLineInContentDescHref').attr('itemref'),
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    elt.parents('.stepLineInContentDesc')
                            .find('.stepLineInContentDescNotif')
                            .addClass('alert-success').text(result.html)
                            .show(1000).hide(1000);
                }
                else {
                    elt.parents('.stepLineInContentDesc')
                            .find('.stepLineInContentDescNotif')
                            .addClass('alert-danger').text(result.html)
                            .show(1000).hide(1000)
                            .removeClass('alert-danger');
                }
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
            }
        }).done(function() {
        });
    }

}
function select_camp_for_steps(e) {
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $("#current_campaign_id").val();
    var result = false;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data: 'current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        beforeSend: function() {
        },
        success: function(response) {
            if (response.success) {
                result = true;
                $("#rightSideCampaignsBloc").empty().append(response.html);
                $("#show_camp_steps").attr("class", "fa fa-minus-square");
                $("#show_camp_steps").attr('id', 'hide_camp_steps');
                $('#campaignSearchBoxForSteps').modal('hide');
                $("#stepSearchGlobalBox").replaceWith(response.searchBox);
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        if (result && $('#tabStepCampaigns').length )  checkScenarioInCampaign(0, 0, 0);
    });
}

function select_sub_for_steps(e) { 
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $("#current_campaign_id").val();
    $.ajax({
        type: 'POST',
        url: elt.attr('itemref'),
        data: 'current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success) {
                $("#rightSideCampaignsBloc").empty().append(response.html);
                $("#show_sub_camps").attr("class", "fa fa-minus-square");
                $("#show_sub_camps").attr('id', 'hide_sub_camps');
                $('#subjectSearchBoxForSteps').modal('hide');
                $("#stepSearchGlobalBox").replaceWith(response.searchBox);
            }
        },
        error: function(response) { 
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}

function select_del_for_steps(e) {
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $("#current_campaign_id").val();
    if (!($('#campaignContent').length > 0))
        return false;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data: 'current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        beforeSend: function() {
        },
        success: function(response) {
            if (response.success) {
                $("#rightSideCampaignsBloc").replaceWith(response.html);
                $("#show_del_camps").attr("src", "fa fa-minus-square");
                $("#show_del_camps").attr('id', 'hide_del_camps');
                $('#deliverySearchBoxForSteps').modal('hide');
                $("#stepSearchGlobalBox").replaceWith(response.searchBox);
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
} 
function loadDataForSteps(action, elt) {
    $.ajax({
        type: 'POST',
        url: action,  
        async: false,
        beforeSend: function() {
            $('.reloading_img_tree').show();
        },
        success: function(response) {
            elt.html(response);
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function(){
        $('#datetimepickerDeliveryStartDate ,#datetimepickerDeliveryEndDate').datetimepicker({
            language: 'pt-BR',
            pickTime: false
        }).on('show.bs.modal', function(event) { 
            event.stopPropagation();
        });
        
        $('.tooltipUser').tooltip({placement: "top"});
        $(".subjectPopover").popover({trigger: "hover"}); 
        $('.reloading_img_tree').hide();  
      }); 
    
} 
function loadDeliverySearchBoxForStepsContent() {   
     loadDataForSteps($("#deliverySearchBoxForStepsLink").attr('itemref'), $("#deliverySearchBoxForStepsContent"));
}
function loadSubjectSearchBoxForStepsContent() {
    
    loadDataForSteps($("#subjectSearchBoxForStepsLink").attr('itemref'), $("#subjectSearchBoxForStepsContent"));
}
function loadCampaignSearchBoxForStepsContent() {
    $('.reloading_img_tree').show();
    loadDataForSteps($("#campaignSearchBoxForStepsLink").attr('itemref'), $("#campaignSearchBoxForStepsContent"));
}
function loadDelForStepsLink(e) {
    e.preventDefault();
    loadDataForSteps($(this).attr('href'), $("#deliverySearchBoxForStepsContent"));
}
function loadSubForStepsLink(e) {
    e.preventDefault();
    loadDataForSteps($(this).attr('itemref'), $("#subjectSearchBoxForStepsContent"));
}
function loadCampForStepsLink(e) {
    e.preventDefault();
    loadDataForSteps($(this).attr('href'), $("#campaignSearchBoxForStepsContent"));
}


/* Recherche des livraisons en ajax pour l'édition des steps de campagne */
function loadDelForStepsForm(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("#searchDeliveryForm").attr('action'),
        data: $("#searchDeliveryForm").serialize(),
        async: false,
        success: function(response) {
            $("#deliverySearchBoxForStepsContent").empty().append(response);
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        $('#datetimepickerDeliveryStartDate ,#datetimepickerDeliveryEndDate').datetimepicker({
            language: 'pt-BR',
            pickTime: false
        });
    });
}
/* Recherche des sujets en ajax pour l'édition des steps de campagne */
function loadSubForStepsForm(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("#searchSubjectForm").attr('action'),
        data: $("#searchSubjectForm").serialize(),
        async: false,
        success: function(response) {
            $("#subjectSearchBoxForStepsContent").empty().append(response);
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        $('#datetimepickerDeliveryStartDate ,#datetimepickerDeliveryEndDate').datetimepicker({
            language: 'pt-BR',
            pickTime: false
        });
    });
}

function deleteCampaignStepWithoutRedirect(e) {
    e.preventDefault();
    var elt = $(this);
    var step_scenario_id = elt.parents('.stepLineInContent').find('.step_scenario_id').val();
    var step_jdd_id = elt.parents('.stepLineInContent').find('.step_jdd_id').val();
    var step_file_name = elt.parents('.stepLineInContent').find('.step_file_name').val();
    var prev;
    var result = false;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success) {
                result = true;
                //Si le curseur était sur l'élément à supprimer, on le déplace à l'élément précedent
                if (elt.parents('.stepLineInContentBox').find('#lighter_in_camp_content').length > 0) {

                    prev = elt.parents('.stepLineInContentBox').first().prev();
                    if (prev.length > 0)
                        prev.find('.checked_place_to_add_in_camp_content ')
                                .attr('id', 'lighter_in_camp_content');
                    else
                        $('#stepLineInContentBoxFirst').find('.checked_place_to_add_in_camp_content ')
                                .attr('id', 'lighter_in_camp_content');
                }
                elt.parents('.stepLineInContentBox').remove();
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        if (result && $('#tabStepCampaigns').length)  checkScenarioInCampaign(step_scenario_id, step_jdd_id, step_file_name);
    });
}
function checked_place_to_add_in_camp_content() {
    var elt = $(this);
    $("#lighter_in_camp_content").attr('id', '');
    elt.attr('id', 'lighter_in_camp_content');
}
/* Enrouler le contenu d'une campagne */
function hide_camp_steps(e) {
    e.preventDefault();
    var elt = $(this);
    elt.attr("class", "fa fa-plus-square show_camp_steps");
    elt.removeClass('hide_camp_steps');
    elt.addClass('show_camp_steps');
    elt.parents('.rightSideStepsListOfCampaign')
            .find('.rightSideStepsListOfCampaignContent')
            .empty();
}

function hide_del_camps(e) {
    e.preventDefault();
    var elt = $(this);
    elt.attr("class", "fa fa-plus-square");
    elt.attr("id", "show_del_camps");
    $("#rightSideDeliveryContent").hide();
}
function hide_sub_camps(e) {
    e.preventDefault();
    var elt = $(this);
    elt.attr("class", "fa fa-plus-square show_sub_camps");
    elt.removeClass('hide_sub_camps');
    elt.addClass('show_sub_camps');
    elt.parents('.rightSideSubjectBloc')
            .find('.campaignsPart')
            .empty();
}

/* Déroulé le contenu d'un sujet  */
function show_sub_camps(e) {
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $("#current_campaign_id").val();
    $.ajax({
        type: 'POST',
        url: elt.attr('data-href'),
        data: 'current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success) {
                elt.parents('.rightSideSubjectBloc').replaceWith(response.html);
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
/* Déroulé le contenu d'une livraison (sujets et campagnes ) */
function show_del_camps(e) {
    e.preventDefault();
    var elt = $(this);
    var current_campaign_id = $("#current_campaign_id").val();
    $.ajax({
        type: 'POST',
        url: elt.attr('data-href'),
        data: 'current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success) {
                $("#rightSideCampaignsBloc").replaceWith(response.html);
                $("#show_del_camps").attr("class", "fa fa-minus-square");
                $("#show_del_camps").attr('id', 'hide_del_camps');
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
/* Déroulé le contenu d'une campagne */
function show_camp_steps(e) {
    e.preventDefault();
    var elt = $(this);
    var result = false;
    $.ajax({
        type: 'POST',
        url: elt.attr('data-href'),
        dataType: 'json',
        async: false,
        success: function(response) {
            result = true;
            if (response.success) {
                elt.parents('.rightSideStepsListOfCampaign')
                        .find('.rightSideStepsListOfCampaignContent')
                        .replaceWith(response.html);
                elt.attr("class", "fa fa-minus-square hide_camp_steps");
                elt.removeClass('show_camp_steps');
                elt.addClass('hide_camp_steps');
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        if (result && $('#tabStepCampaigns').length) checkScenarioInCampaign(0, 0, 0);
    });
}
/* Insertion d'une campagne au contenu */
function addStepInContent(e) {
    e.preventDefault();
    var elt = $(this);
    var before_step_id = $("#lighter_in_camp_content").find(".lighter_in_camp_content_value").val();
    var current_campaign_id = $("#current_campaign_id").val();
    var step_scenario_id = elt.parents('.rightSideStep').find('.scenarioInCampaign').val();
    var scenario_tree_id = elt.parent().find('.obj_id').val();
    var step_jdd_id = elt.parents('.rightSideStep').find('.jddInCampaign').val();
    var jdd_tree_id = elt.parent().find('.data_set_id').val();
    var step_file_name = elt.parents('.rightSideStep').find('.fileInCampaign').val();

    var result = false;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        data: 'before_step_id=' + before_step_id + '&current_campaign_id=' + current_campaign_id,
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success) {
                result = true;
                var elt = $("#lighter_in_camp_content");
                if (elt.parent().attr('id') === 'stepLineInContentBoxFirst') {
                    $('#campaignContentList').prepend(response.html);
                }
                else {
                    elt.parent().after(response.html);
                }

                elt.attr('id', '');
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() {
        if (result) {
            var scenario_id, jdd_id;
            if (scenario_tree_id !== undefined)
                scenario_id = scenario_tree_id;
            else
                scenario_id = step_scenario_id;
            if (jdd_tree_id !== undefined)
                jdd_id = jdd_tree_id;
            else
                jdd_id = step_jdd_id;

            //Si on est dans la gestion des steps d'une campagne
            if($('#tabStepCampaigns').length) checkScenarioInCampaign(scenario_id, jdd_id, step_file_name);
        }
    });
}


/* Ouverture de la box d'ajout d'un scénario à un noeud du graphe de campagne */
function campaignGraphTestSuiteAdd() {
    if ($("#campaignGraphTestSuiteAddBox").is(":visible"))
        $("#campaignGraphTestSuiteAddBox").hide('fast');
    else
        $("#campaignGraphTestSuiteAddBox").show('fast');
}

/* Ouverture de la box d'ajout d'un scénario à un noeud du graphe de campagne */
function campaignGraphDataSetSearch() {
    if ($("#campaignGraphDataSetAddBox").is(":visible"))
        $("#campaignGraphDataSetAddBox").hide('fast');
    else
        $("#campaignGraphDataSetAddBox").show('fast');
}
function rotateRefreshIcon() {
    $('.icon-refresh').rotate();
}

function openModal(modal) {
    modal.modal("show");
}

function closeModal(modal) {
    modal.modal("hide");
}
/*  Collapse et extend des descriptions des steps de scénario */
function stepLineInContentBoxMoinsInfo() {
    $(this).parentsUntil('.stepLineInContentBox').find('.stepLineInContentDesc').hide();
    $(this).parentsUntil('.stepLineInContentBox').find('.stepLineInContentBoxPlusInfo').show();
    $(this).hide();
}
function stepLineInContentBoxPlusInfo() {
    $(this).parentsUntil('.stepLineInContentBox').find('.stepLineInContentDesc').show();
    $(this).parentsUntil('.stepLineInContentBox').find('.stepLineInContentBoxMoinsInfo').show();
    $(this).hide();
}
/*  Collapse et extend de toutes les descriptions des steps de scénario à la fois  */
function eventExtendSteps() {
    $("#campaignContent").find('.stepLineInContentBoxMoinsInfo').show();
    $("#campaignContent").find('.stepLineInContentDesc').show();
    $("#campaignContent").find('.stepLineInContentBoxPlusInfo').hide();
    $("#collapseSteps").removeClass('active');
    $(this).addClass('active');
    return false;
}
function eventCollapseSteps() {
    $("#campaignContent").find('.stepLineInContentBoxPlusInfo').show();
    $("#campaignContent").find('.stepLineInContentDesc').hide();
    $("#campaignContent").find('.stepLineInContentBoxMoinsInfo').hide();
    $("#extendSteps").removeClass('active');
    $(this).addClass('active');
    return false;
}

  