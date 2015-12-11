$(function () {
    $('#datetimepickerDeliveryStartDate ,#datetimepickerDeliveryEndDate, #datetimepickerDeliveryDate').datetimepicker({
        format: "YYYY/MM/DD"
    });
});
$selectItForDelStats = new Array; //Tableau permettant de récupérer la liste des itérations pour la génération des statistiques d'une livraison

$(document).ready(function () {

    //Ajout d'une campagne de tests à une livraison 
    $(this).delegate(".addCampaignToDelivery", "click", addCampaignToDelivery);
    /* Définition d'une itération comme itération par défaut */
    $(this).delegate(".setIterationAsDefault", "click", setIterationAsDefault);
    $(this).delegate('#iterationModal', 'shown.bs.modal', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(e.relatedTarget), $('.eiLoading'), "json", true, {}, addIterationForProfile, delAjaxProcessErr);
    });
    $(this).delegate('#iterationModal', 'hidden.bs.modal', function (e) {
        e.preventDefault();
        $(this).find('form').find('#ei_iteration_description').val("");
    });
    $(this).delegate('#changeInterventionOnMigrationModal', 'shown.bs.modal', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(e.relatedTarget), $('.eiLoading'), "html", false, {}, loadnterventionOnMigrationModal, delAjaxProcessErr);
    });

    $(this).delegate('.changeInterventionInMigrationLine', 'click', function (e) {
        e.preventDefault();
        var script_id = $("#current_script_id").val(); //alert(script_id);
        var scenario_version_id = $("#current_scenario_version_id").val();
        var data;
        if (!isNaN(parseInt(script_id))) {//alert(parseInt(script_id));

            data = "script_id=" + parseInt(script_id);
        }
        else {
            if (!isNaN(parseInt(scenario_version_id))) { //alert(parseInt(scenario_version_id));
                data = "scenario_version_id=" + parseInt(scenario_version_id);
            }
        }
        loadEiAjaxActions($(this), $('.eiLoading'), "json", false, data, changeInterventionInMigrationLine, delAjaxProcessErr);
    });

    $(this).delegate('#changeInterventionOnMigrationModal', 'hidden.bs.modal', function (e) {
        e.preventDefault();
        $("#current_script_id").val("");
        $("#current_scenario_version_id").val("");
    });
    /* Chargement des interventions dans le contexte d'un changement d'intervention sur migration */
    $(this).delegate('#loadIntForMigration', 'click', function (e) {
        e.preventDefault();
        loadEiAjaxForm($("#searchSubjectForm"), $('.eiLoading'), "html", false, loadIntForMigration);
    });
    $(this).delegate('.loadIntForMigrationLink', 'click', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(this), $('.reloading_img_tree'), "html", false, {}, loadIntForMigrationLink, delAjaxProcessErr);
    });

    $(this).delegate('#saveIteration', 'click', function (e) {
        e.preventDefault();
        loadEiAjaxForm($("#iterationForm"), $('.eiLoading'), "json", true, saveIteration);
    });
    $(this).delegate('#iterationForm', 'submit', function (e) {
        e.preventDefault();
        loadEiAjaxForm($("#iterationForm"), $('.eiLoading'), "json", true, saveIteration);
    });
    $(this).delegate('#searchIterationForDelStats', 'click', function (e) {
        e.preventDefault();
        loadEiAjaxForm($("#iterationSearchBoxForDelStatsForm"), $('.eiLoading'), "json", true, searchIterationForDelStats);
    });
    $(this).delegate('#iterationSearchBoxForDelStatsForm', 'submit', function (e) {
        e.preventDefault();
        loadEiAjaxForm($("#iterationSearchBoxForDelStatsForm"), $('.eiLoading'), "json", true, searchIterationForDelStats);
    });
    /* Selection et désélection d'une itération pour les stats de livraison*/
    $(this).delegate('.selectItForDelStats', 'click', function (e) {
        var elt = $(this);
        if ($(this).parent().parent().hasClass('selectItColor')) {
            $(this).parent().parent().removeClass('selectItColor');
            $(this).removeAttr("checked");
            $selectItForDelStats = $.grep($selectItForDelStats, function (value) {
                return value !== elt.parent().find('.iteration_id').val();
            });
        }

        else {
            if (jQuery.inArray(elt.parent().find('.iteration_id').val(), $selectItForDelStats)===-1)
                $selectItForDelStats.push(elt.parent().find('.iteration_id').val());
            $(this).attr("checked", true);
            $(this).parent().parent().addClass('selectItColor');
        }

    });
    /* Selection et désélection de toutes les itérations  pour les stats de livraison*/
    $(this).delegate('#selectAllItForDelStats', 'click', function () {
        if ($(this).hasClass('isSelected')) {
            //$selectItForDelStats.splice(0, $selectItForDelStats.length);
            $(this).parents('table').find('tbody').find('tr').each(function () {
                var elt=$(this);
                elt.removeClass('selectItColor');
                elt.find('.selectItForDelStats').removeAttr("checked");
                $selectItForDelStats = $.grep($selectItForDelStats, function (value) {
                    return value !== elt.find('.iteration_id').val();
                });
            });
            $(this).removeClass('isSelected');
        }
        else {
            $(this).parents('table').find('tbody').find('tr').each(function () {
                $(this).addClass('selectItColor');
                $(this).find('.selectItForDelStats').attr("checked", true);
                if (jQuery.inArray($(this).find('.iteration_id').val(), $selectItForDelStats)===-1)
                    $selectItForDelStats.push($(this).find('.iteration_id').val());
            });
            $(this).addClass('isSelected');
        }
    });

    /* Retour des statistiques pour les itérations choisies */
    $(this).delegate('#getStatsForSelectedId', 'click', function (e) {
        e.preventDefault();
//        var iterationList = new Array;
//        $("#ItTablePanel").find('tbody').find('tr').find('.selectItForDelStats').each(function () {
//            if ($(this).attr("checked")) {
//                iterationList.push($(this).parent().find('.iteration_id').val());
//            }
//
//        });
        if ($selectItForDelStats.length > 0) {
            //alert($selectItForDelStats);
            $("#iterationContent").empty();
            loadEiAjaxActions($(this), $('.eiLoading'), "json", true, {"ei_iterations": $selectItForDelStats}, getStatsForSelectedId, delAjaxProcessErr);
        }
        else {
            alert('No iteration selected .Please select at least one...');
        }

    });


});
/* Fonction permettant de retourner les statistiques d'une livraison pour un ensemble d'itérations */

function getStatsForSelectedId(response, elt) {
    if (response.success) {
        $("#searchIterationForStatsModal").modal("hide");
        $("#iterationContent").replaceWith(response.html);
        generateDataTable($('#ItTableStatsPanel'), 5, 6, "desc");
    }
    else {
        alert("error in process.Try again...");
        if ($('.eiLoading').length > 0)
            $('.eiLoading').hide();
    }
    //$selectItForDelStats.splice(0, $selectItForDelStats.length);
}
/* Fonction de recherche des itérations pour les statistiques d'une livraison */
function searchIterationForDelStats(response) {
    if (response.success) {
        $("#ItTablePanel").find("tbody").replaceWith(response.html);
        generateDataTable($('#ItTablePanel'), 5, 7, "desc");
        generateDataTable($('#ItTableStatsPanel'), 5, 6, "desc");
        $selectItForDelStats.splice(0, $selectItForDelStats.length); // Refresh du tableau des itérations
    }
    else {
        alert("Error in process .Try again ...");
    }

}

/*Chargement des interventions dans une modale pour le changement du package d'une ligne de migration */
function loadIntForMigrationLink(response, elt) {
    $("#changeInterventionOnMigrationModalContent").html(response);
}
/* Recherche des interventions dans le contexte d'un changement du package d'une ligne de migration */
function loadIntForMigration(response)
{
    $("#changeInterventionOnMigrationModalContent").empty().append(response);
}
;
/* Chargement de la fenêtre modale des interventions dans la page de migration des fonctions d'une intervention/livraison */
function loadnterventionOnMigrationModal(response, elt) {
    $("#changeInterventionOnMigrationModalContent").html(response);
    $("#current_script_id").val(elt.find('.script_id').val());
    $("#current_scenario_version_id").val(elt.find('.scenario_version_id').val());
}
/* Action éffectuée suite au changement d'un package d'une ligne de migration*/
function changeInterventionInMigrationLine(response, elt) {
    if (response.success) {
        location.reload(true);
    }
    else {
        alert(response.html);
    }
}

/* Ajout rapide d'une itération à un profil de la livraison */
function addIterationForProfile(response, elt) {
    if (response.success) {
        if ($("#iterationModalContent").find('form').length)
            $("#iterationModalContent").find('form').replaceWith(response.html);
        else
            $("#iterationModalContent").append(response.html);
    }
}
/* Sauvegarde d'une itération  */
function saveIteration(response) {
    if (response.success) {
        if (response.iteration_num !== 0) { //On est en mode update
            $(".iteration_num" + response.iteration_num).replaceWith(response.html);
        }
        else {
            $(response.iteration_profile_class).find('tbody').find('.activeIteration').remove();
            $(response.iteration_profile_class).find('tbody').append(response.html);
        }
        $("#iterationModal").modal("hide");
    }
    else {
        $('#iterationForm').replaceWith(response.html);
    }
}
/* Définition d'une itération comme itération par défaut */

function setIterationAsDefault(e) {
    e.preventDefault();
    var elt = $(this);
    var oldDef = elt.parents(".iterationsByProfile").find(".activeIteration");
    var resp;
    $.ajax({
        type: 'POST',
        url: elt.attr('itemref'),
        dataType: 'json',
        async: true,
        beforeSend: function () {
        },
        success: function (response) {
            success = response.success;
            resp = response;
            if (response.success) {//Si tout se passe bien 
                elt.attr("class", response.resultClass).attr("title", response.resultTitle).text(response.html);
                oldDef.attr("class", "btn btn-sm btn-default setIterationAsDefault");
                oldDef.attr("title", "Set as active?").text("Set as active");
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
    });
}

//Ajout d'une campagne de tests à la livraison
function addCampaignToDelivery(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        beforeSend: function () { // traitements JS à faire AVANT l'envoi  
            elt.hide();
        },
        success: function (response) {
            if (response.success) {
                elt.remove();
                //On range le résultat dans la liste des utilisateurs assignés au sujet
                $("#deliveryCampaignsList").append(response.html);
            }
            else {
                elt.show(); //On rend l'élément visualisable puisque la transaction a échoué
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
    });
}

function eventIterationDesc(elt) {

    var desc = elt.val();
    if ($.trim(desc).length > 0) //Si le champ description change
        $.ajax({
            type: "POST",
            data: "iteration_description=" + desc,
            url: elt.attr('itemref'),
            dataType: 'json',
            async: true,
            beforeSend: function () {// traitements JS à faire AVANT l'envoi
                //Initialisation du loader
                elt.parents('.iterationBlock').find('.saveLoader').addClass("fa fa-spinner fa-spin fa-lg").css("display", "inline-block");
            },
            success: function (response) {
                if (response.success) {
                    elt.parents('.iterationBlock').find('.thumbsWell').fadeIn(1000).fadeOut(1000);
                    elt.parents('.iterationBlock').find('.saveLoader').removeClass("fa fa-spinner fa-spin fa-lg");
                }
            },
            error: function (response) {
                //alert(window.location.pathname);
                if (response.status == '401')
                    window.location.href = window.location.pathname;
            }
        }).done(function () {
        });

}

var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
        var matches, substrRegex;

// an array that will be populated with substring matches
        matches = [];

// regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

// iterate through the pool of strings and for any string that
// contains the substring `q`, add it to the `matches` array
        $.each(strs, function (i, str) {
            if (substrRegex.test(str)) {
// the typeahead jQuery plugin expects suggestions to a
// JavaScript object, refer to typeahead docs for more info
                matches.push({value: str});
            }
        });

        cb(matches);
    };
};
if (typeof ei_delivery_authors !== "undefined")
    $('#search_delivery_by_author').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'ei_delivery_authors',
        displayKey: 'value',
        source: substringMatcher(ei_delivery_authors)
    });
if (typeof ei_delivery_titles !== "undefined")
    $('#search_delivery_by_title').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'ei_delivery_titles',
        displayKey: 'value',
        source: substringMatcher(ei_delivery_titles)
    });


function delAjaxProcessErr(eltId, additionalDatas) {
    alert('Error ! Problem when processing');
}

if (typeof ei_iterations_authors !== "undefined")
    $('#search_iteration_by_author').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'ei_iterations_authors',
        displayKey: 'value',
        source: substringMatcher(ei_iterations_authors)
    });

