$(function () {
    $('#datetimepickerExpectedDate').datetimepicker({
        format: "YYYY/MM/DD",
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        }
    });
});
$(document).ready(function () {
    $('.comboboxForAssignUserToSubject').combobox({
        dropdown: true,
        disableTypeahead: true
    });

    $('.tooltipUser').tooltip({placement: "top"});
    $(".subjectPopover").popover({trigger: "hover"});
    $('label.tree-toggler').click(function () {
        $(this).parent().children('ul.tree').toggle(300);
    });

    $("#check_all_subject_for_mult_act").change(function () {
        if ($(this).attr("checked"))
        {
            $('#subjectList').find('.check_subject_for_mult_act').each(function () {
                if (!$(this).attr('disabled'))
                    $(this).attr('checked', 'checked');
            });
        }
        else
        {
            $('#subjectList').find('.check_subject_for_mult_act').removeAttr('checked');
        }
    });
    initTinyMce();
    $(this).delegate("#usersToAssignToSubject li", "click", addSubjectAssignment); //Ajout d'une assignation pour un sujet 
    $(this).delegate(".removeSubjectAssignment", "click", removeSubjectAssignment); //Suppression d'un assignation sur un sujet
    /* Traitement des assignations multiples */

    $(this).delegate(".assignUserItem", "click", assignUserItemEvent); //Choix d'un utilisateur pour la liste à assigner
    $(this).delegate(".removeUserFromMultipleAssign", "click", removeUserFromMultipleAssignEvent); //Retrait d'un utilisateur pour la liste à assigner

    $(this).delegate(".openNonAssignUserOnSubjectBox", "click", openNonAssignUserOnSubjectBox);
    $(this).delegate(".closeNonAssignUserOnSubjectBox", "click", closeNonAssignUserOnSubjectBox);
    $(this).delegate("#showOrhideDeliveryCriteria", "click", showOrhideDeliveryCriteria);

    $(this).delegate("#addsubjectMessage", "click", addsubjectMessage);
    $(this).delegate('#submitSubjectMessage', 'click', submitSubjectMessage);

    /* Définition d'une intervention comme intervention par défaut */
    $(this).delegate('#setInterventionAsDefault', 'click', function (e) {
        e.preventDefault();
        $("#alertBox").remove();
        loadEiAjaxActions($(this), $('.eiLoading'), "json", true, {}, setInterventionAsDefault, interventionAjaxProcessErr);
    });
    //Suppression d'un fichier attaché
    $(this).delegate(".removeSubjectAttachment", "click", removeSubjectAttachment);
    /*Gestion des actions multiples sur les sujets */
    $(this).delegate("#saveGroupActionType", "click", changeGroupSubjectAction);
    $(this).delegate("#saveGroupActionPriority", "click", changeGroupSubjectAction);
    $(this).delegate("#saveGroupActionState", "click", changeGroupSubjectAction);
    $(this).delegate("#openGroupActionPriorityForm", "click", function () {
        $('#groupActionPriorityForm').show();
        $('#groupActionTypeForm').hide();
        $('#groupActionStateForm').hide();
    });
    $(this).delegate("#openGroupActionTypeForm", "click", function () {
        $('#groupActionTypeForm').show();
        $('#groupActionPriorityForm').hide();
        $('#groupActionStateForm').hide();
    });
    $(this).delegate("#openGroupActionStateForm", "click", function () {
        $('#groupActionStateForm').show();
        $('#groupActionPriorityForm').hide();
        $('#groupActionTypeForm').hide();
    });
    $(this).delegate(".chooseDel", "click", chooseDel);

    /* Gestion des méssages sur les sujets */
    $(this).delegate(".addSubjectMsg", "click", {eltType: "elt"}, addSubjectMsgEvent); //Ajout d'un méssage sur un sujet
    $(this).delegate(".itemMessageFooter .form-horizontal", "submit", {eltType: "submit"}, addSubjectMsgEvent);
});


/**/
function setInterventionAsDefault(response, elt) {
if(response.success){ 
 $("#eiProjectCurrentSubjectLi").replaceWith(response.html);   
 $("#setInterventionAsDefault").parent().parent().remove();//On retire le bouton permettant de définir l'intervention comme intervention par défaut
 if($("#alertBox").length){
     $("#alertBox").replaceWith(response.alertPart);
 }
 else{ 
     $("#subjectContent").before(response.alertPart);
 }
}
}

function chooseDel(e) {
    e.preventDefault();
    var elt = $(this);
    var delivery_id = elt.parent().parent().find('.delivery_id').val();
    if ($('#chooseDelForManySub').length > 0 && delivery_id.length > 0) {
        //On vérifie l'état de la livraison à choisir pour les bug . Si livraison close on empêche l'action
        if (!isDeliveryClosed(delivery_id)) {
            if (confirm('Sure to define this delivery for all subject ?'))
                chooseDelForManySub($('#chooseDelForManySub').attr('itemref'), delivery_id);
        }
        else {
            alert("Can't associate because delivery was closed. Select an open delivery ...");
        }
    }
}
/* Fonction permettant de savoir si une livraison est close ou pas */
function isDeliveryClosed(delivery_id) {
    var elt = $("#isDeliveryClosed");
    var resp = false;
    $.ajax({
        type: 'POST',
        url: elt.attr("itemref"),
        data: 'delivery_id=' + delivery_id,
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response.success)
                resp = true;
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
    });
    return resp;
}


function chooseDelForManySub(url, delivery_id) {
    var selectSubjectTab = new Array();
    var k = 0;
    $('.check_subject_for_mult_act').each(function () {
        if ($(this).is(':checked')) {
            selectSubjectTab[k] = $(this).val();
            k++;
        }
    });
    if (selectSubjectTab.length > 0) {
        $.ajax({
            type: 'POST',
            url: url,
            data: 'delivery_id=' + delivery_id + '&selectSubjectTab=' + selectSubjectTab,
            dataType: 'json',
            async: false,
            beforeSend: function () {
            },
            success: function (response) {
                if (response.success)
                    location.reload(true);
            },
            error: function (response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function () {
            selectSubjectTab.length = 0;
        });
    }
    else
        alert('no subject selected ...');
}

function changeGroupSubjectAction(e) {
    e.preventDefault();
    var elt = $(this);
    var selectSubjectTab = new Array();
    var k = 0;
    $('.check_subject_for_mult_act').each(function (i) {
        if ($(this).is(':checked')) {
            selectSubjectTab[k] = $(this).val();
            k++;
        }

    });

    if (selectSubjectTab.length > 0) {
        $.ajax({
            type: 'POST',
            url: elt.parents('form').attr('action'),
            data: elt.parents('form').serialize() + '&selectSubjectTab=' + selectSubjectTab,
            dataType: 'json',
            async: false,
            beforeSend: function () {
            },
            success: function (response) {
                if (response.success)
                    location.reload(true);
            },
            error: function (response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function () {
            selectSubjectTab.length = 0;
        });
    }
    else
        alert('no subject selected ...');

}

function submitSubjectMessage(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $('#subjectMessageForm').attr('action'),
        data: $('#subjectMessageForm').serialize(),
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) {
            if (response.success) {
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
function addsubjectMessage(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        beforeSend: function () {
            $("#subjectMessageFormModal").find('.modal-body').empty()
        },
        success: function (response) {
            if (response.success) {
                $("#subjectMessageFormModal").find('.modal-body').replaceWith(response.html);
            }
            else {
                alert('Error ...');
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
        $("#subjectMessageFormModal").modal('show');
    });
}

function removeSubjectAttachment(e) {
    e.preventDefault();
    var elt = $(this);
    if (confirm("Sure to delete attachment ?")) {
        $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false,
            success: function (response) {
                if (response.success) {
                    if ($('.alert_subject_attachment').length > 0)
                        $('.alert_subject_attachment').replaceWith(response.html);
                    else
                        $('#subjectAttachmentsTitle').before(response.html);


                    elt.parents('.btn-group').remove();
                }
                else {
                    alert('Error ...');
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

}

function showOrhideDeliveryCriteria(e) {
    if ($('#subjectDeliverySearchBlock').is(':visible')) {
        $('#subjectDeliverySearchBlock').hide('fast');
    }
    else {
        $('#subjectDeliverySearchBlock').show('fast');
    }

}
function openNonAssignUserOnSubjectBox(e) {
    e.preventDefault();
    var elt = $(this);
    $("#usersToAssignToSubject").show();
    elt.find('.fa').removeClass('fa-plus').addClass('fa-minus');
    elt.removeClass('openNonAssignUserOnSubjectBox');
    elt.addClass("closeNonAssignUserOnSubjectBox");

}
function closeNonAssignUserOnSubjectBox(e) {
    e.preventDefault();
    var elt = $(this);
    $("#usersToAssignToSubject").hide();
    elt.find('.fa').removeClass('fa-minus').addClass('fa-plus');
    elt.removeClass('closeNonAssignUserOnSubjectBox');
    elt.addClass("openNonAssignUserOnSubjectBox");
}

function removeSubjectAssignment(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        beforeSend: function () {
            //on cache le nom du user + l'action delete contenus dans un même parent
            elt.parent().hide();
        },
        success: function (response) {
            if (response.success) {
                //On retire l'élément de la liste des utilisateurs non assignés
                elt.remove();
                //On range le résultat dans la liste des utilisateurs assignés au sujet 
            }
            else {
                //alert(response.html);
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

function addSubjectAssignment(e) {
    e.preventDefault();
    var elt = $(this);
    var guard_username = elt.attr('data-value');
    $.ajax({
        type: 'POST',
        url: elt.parents('#usersToAssignToSubject').find('.addSubjectAssignment').attr('href'),
        data: 'guard_username=' + $.trim(guard_username),
        dataType: 'json',
        async: false,
        beforeSend: function () { // traitements JS à faire AVANT l'envoi  
            elt.hide();
        },
        success: function (response) {
            if (response.success) {
                //On retire l'élément de la liste des utilisateurs non assignés
                elt.remove();
                //On range le résultat dans la liste des utilisateurs assignés au sujet
                $("#alreadyAssignUsers").append(response.html);
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
function addSubjectMsgEvent(e) {
    e.preventDefault();

    if (e.data.eltType === "submit") {//On est dans le cas du soumission du formulaire par la touche entrée
        var elt = $(this).find(".addSubjectMsg:first");
    }
    else {
        var elt = $(this);
    }
    var form = elt.closest('.form-horizontal'); //alert(elt.html());

    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) {
            if (response.success) {
//                    elt.closest(".itemMessages").find(".itemMessage:first").after(response.html);
                //alert(elt.html());
                elt.closest(".eiPanel").find('.itemMessages:first').append(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}

function assignUserItemEvent(e) {
    e.preventDefault();
    var elt = $(this);
    var itemId = $("#ei_project_for_multiple_assign").find("option[class='assignUserItem'][itemprop='" + elt.text() + "']").attr("itemid");
    //alert(itemId);//alert(itemId.html());
    if ($("input[class='userIdForMultipleAssign'][value='" + itemId + "']").length > 0)
        return false; //L'utilisateur a déjà été ajouté 
    $("#listOfUserToAssign").append('<div class="btn-group"><button type="button" class="btn btn-sm btn-primary">\n\
    ' + elt.text() + '<input class="userIdForMultipleAssign" type="hidden" value="' + itemId + '" /></button>\n\
    <button type="button" class="btn btn-sm btn-danger removeUserFromMultipleAssign"><i class="fa fa-times "></i></button></div>');
}
function removeUserFromMultipleAssignEvent(e) {
    e.preventDefault();
    var elt = $(this);
    elt.parent().remove();
}



/*  Fonction d'initialisation du tinyMCE pour la prise en compte
 *  des paramètres d'entrées et sorties de la fonction  
 */
function initTinyMce() {
    tinymce.init({
        selector: "textarea.tinyMceSubject",
        height: 400,
        paste_data_images: true,
        plugins: [
            " example advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table  paste "
        ],
        menu: {
            edit: {title: 'Edit', items: 'undo redo  | cut copy paste selectall | searchreplace'},
            insert: {title: 'Insert', items: 'link charmap'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}
        },
        //menubar: "file edit format table view insert tools parameters",  
        theme_advanced_buttons1: "mapalette",
        entity_encoding: "raw",
        toolbar: " undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

    });

}





/**/

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



if (typeof ei_subjects_assignments !== "undefined") {
    $('#search_subject_by_assignment').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'ei_subjects_assignments',
        displayKey: 'value',
        source: substringMatcher(ei_subjects_assignments)
    });
}

if (typeof ei_subjects_authors !== "undefined")
    $('#search_subject_by_author').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'ei_subjects_authors',
        displayKey: 'value',
        source: substringMatcher(ei_subjects_authors)
    });



function interventionAjaxProcessErr(eltId, additionalDatas) {
    alert('Error ! Problem when processing');
}