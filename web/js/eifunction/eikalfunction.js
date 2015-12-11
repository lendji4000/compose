var maxprogress = 100;   // max que l'on peut atteindre
$(document).ready(function () { 
    $(this).delegate("#changeFunctionCriticity", "click", changeFunctionCriticity);
    $(this).delegate(".addFunctionToSubject", "click", addFunctionToSubject);
    $(this).delegate(".removeFunctionFormSubject", "click", removeFunctionFormSubject);
    /* Gestion des fonctions dans l'édition des campagnes */
    //Ouverture de la box pour le choix d'une fonction
    $(this).delegate("#tabStepFunctionsLink", "click", OpenStepFunctionsBox);
    $(this).delegate(".showFunctionSubjects", "click", showFunctionSubjects);
    //Contenu d'une fonction (sujets de la fonction)
    $(this).delegate("#show_funct_subj", "click", showFunctionSubjects);
    $(this).delegate("#hide_funct_subj", "click", hide_funct_subj);

    /* Ajout d'une fonction sur la plate forme centrale à partir de compose */
    $(this).delegate(".add_kal_function", "click", add_kal_function); 
    $(this).delegate('.nodeMoreInf', 'click', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(this), $('.eiLoading'), "json", true, {}, nodeDetailsModal,functAjaxProcessErr);
    });
    /* Ajout d'une vue sur la plate forme centrale à partir de compose */
    $(this).delegate(".add_script_folder", "click", add_script_folder);

    //Sauvegarde d'une vue la plate forme centrale à partir de compose
    $(this).delegate('#saveKalFolder', 'click', saveKalFolder);
    $(this).delegate('#kalViewForm', 'submit', saveKalFolder);
    /* Associer une fonction à un bug dans les fonctions d'une version */
    $(this).delegate('.ei-breakable-link ,.ei-notfound-link', 'click', linkFunctionWithDefPack);
    $(this).delegate('#addImpactsModal', 'shown.bs.modal', function (e) { 
            $(".addImpactsModalBody").load($('.addImpactsModalBody').attr('itemref'));
          }); 
          
    /* Edition d'une fonction*/
    $(this).delegate('#editKalFunctionModal', 'shown.bs.modal', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(e.relatedTarget), $(".eiLoading"), "json", true, {}, openEditKalFunctionModal, functAjaxProcessErr);
    });
    $(this).delegate('#editKalFunctionModal', 'hidden.bs.modal', function (e) {
        $("#editKalFunctionModalBody").empty();
    });
    /* Modification des données principales d'une fonction */
    
    $(this).delegate('#updateKalFunction', 'click', function (e) { 
        e.preventDefault();
        loadEiAjaxForm($("#editKalFunctionModalBody").find("form"),$(".eiLoading"),"json",true,updateKalFunction);
    });
});

/**/
function updateKalFunction(response){
    if(response.success){
        $("#editKalFunctionModal").modal('hide');
        $("#administrateFunctions").replaceWith(response.html);
    }
    else{
        if(response.is_default_intervention_null){
            alert(response.html);
            $("#editKalFunctionModal").modal('hide');
        }
        $("#kalFunctionForm").replaceWith(response.html);
    }
}

/* Edition d'une fonction */
function openEditKalFunctionModal(response,elt){  
    $("#editKalFunctionModalBody").append(response.html);
}
/* Ouverture de la box des fonctions pour le choix d'une fonction dans l'édition des campagnes */
function OpenStepFunctionsBox() {
    $("#tabStepFunctions").tab('show');
    $('#functionSearchBoxForSteps').modal('show');
}
/* Affichage des sujets d'une fonction*/
function showFunctionSubjects(e) {
    e.preventDefault();
    var elt = $(this);
    var uri = elt.attr('href');
    var current_campaign_id = $("#current_campaign_id").val();
    if (uri === undefined)
        uri = elt.attr('data-href');
    $.ajax({
        type: 'POST',
        url: uri,
        dataType: 'json',
        data: 'current_campaign_id=' + current_campaign_id,
        async: false,
        success: function (response) {
            if (response.success) {
                $('#tabStepFunctions').find('.rightSideFunctionBloc').replaceWith(response.html);
                $('#functionSearchBoxForSteps').modal('hide');
            }
            else {
                alert(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
/* Masquer le contenu d'une fonction (sujets de la fonction) */
function hide_funct_subj(e) {
    e.preventDefault();
    var elt = $(this);
    elt.attr("src", "/images/boutons/plus2.png");
    elt.attr("id", "show_funct_subj");
    $('#tabStepFunctions').find('.rightSideFunctionBloc').find('.rightSideFunctionBlocContent').hide();
}
/* Ajout d'une fonction à un sujet */
function addFunctionToSubject(e) {
    e.preventDefault();
    var elt = $(this);
    $("#eicontent").load(elt.attr('href'),'subject_id=' + parseInt($(":input[id=subject_id][name=subject_id]").val())); 
    $('#addImpactsModal').modal('hide');
        generateDataTable($('#executionFunctions'),30,0,"desc");
}
/* Suppression d'une fonction d'un sujet */
function removeFunctionFormSubject(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response.success) {
                elt.parents('.subjectFunctionLine').hide();
            }
            else {
                alert(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function(){ 
        generateDataTable($('#executionFunctions'),30,0,"desc");  
    });
} 



/* Gestion des changements de criticité d'une fonction */
function changeFunctionCriticity(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('itemref'),
        dataType: 'json',
        async: true,
        success: function (response) {
            if (response.success) {
                changeCriticity(elt);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
function changeCriticity(elt) {
    if ($.trim(elt.text()) === "High") {
        elt.text("Blank");
        elt.attr('class', 'criticity criticity-Blank btn');
    }
    else if ($.trim(elt.text()) === "Medium") {
        elt.text("High");
        elt.attr('class', 'criticity criticity-High btn');
    }
    else if ($.trim(elt.text()) === "Low") {
        elt.text("Medium");
        elt.attr('class', 'criticity criticity-Medium btn');
    }
    else if ($.trim(elt.text()) === "Blank") {
        elt.text("Low");
        elt.attr('class', ' criticity criticity-Low btn');
    }
}
/* Ajout d'une fonction sur la plate forme centrale à partir de compose */
function add_kal_function(e) {
    e.preventDefault();
    var elt = $(this);
    var node_id = elt.parent().parent().find(":input[class=node_id][name=node_id]").val();
    var arrayRes = isUserGetDefaultPackage();
    if (!arrayRes['result']) {
        alert(arrayRes['textResult']);
        return false;
    }
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        success: function (response) {
            $('.addKalFunctionModalBody').empty().append(response.html);
            $('#addKalFunctionModal').modal('show');
            $('#addKalFunctionModal').find(":input[class=node_id][name=node_id]").val(node_id);
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
/* Récupération des détails d'un noeud de fonction  */
function nodeDetailsModal(response, elt) { 
    $('#nodeDetailsModal').modal('show'); 
    $('#nodeDetailsModalBody').empty().append(response.html);
    $('#nodeDetailsModalLabel').replaceWith(response.header);
}

/* Fonction permettant de vérifier si l'utilisateur  a définit un package par défaut */
function isUserGetDefaultPackage() {
    var arrayResult = {'result': false};
    //var result=false;
    var uri = $('#isUserGetDefaultPack');
    $.ajax({
        type: 'POST',
        url: uri.attr('itemref'),
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response.success)
                arrayResult['result'] = true;
            else
                arrayResult['textResult'] = response.html;
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
    return arrayResult;
}

function add_script_folder(e) {
    e.preventDefault();
    var elt = $(this);
    var node_id = elt.parent().parent().find(":input[class=node_id][name=node_id]").val();
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        async: false,
        success: function (response) {
            $('.addKalFolderModalBody').empty().append(response.html);
            $('#addKalFolderModal').modal('show');
            $('#addKalFolderModal').find(":input[class=node_id][name=node_id]").val(node_id);
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}

/* Sauvegarde d'une vue par webservice sur la plate forme centrale */

function saveKalFolder(e) {
    e.preventDefault(); 
    var   result;
    if($("#saveKalFolder").hasClass('processing')) return ;
    $("#saveKalFolder").addClass('processing');
    $.ajax({
        type: 'POST',
        url: $('#kalViewForm').attr('action'),
        data: $('#kalViewForm').serialize(),
        dataType: 'json',
        async: true,
        beforeSend: function () {
            $('.eiLoading').show();
        },
        success: function (response) {
            if (response.success) {
                result = true;  //alert('Well done ...');    
            }
            else {
                $('#kalViewForm').replaceWith(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {
        $('.eiLoading').hide();
        if (result) {
            var root_elt = $(":input[class=node_id][value=" + $('#addKalFolderModal')
                    .find(":input[class=node_id][name=node_id]").val() + "]").parent();
            $('#addKalFolderModal').modal('hide');
            if(root_elt.hasClass('lien_survol_tree')){
                $("#refreshProject").click();
            }
            else{
                root_elt.find('.ei-fa-disabled').first().remove();
                root_elt.find('.show_node_childs').first().show();
                root_elt.find('.show_node_childs').first().click();
            } 
        }
    });
    $("#saveKalFolder").removeClass('processing');
}
 /* Associer une fonction à un bug dans les fonctions d'une version */
function linkFunctionWithDefPack(e){
    e.preventDefault();
    var elt = $(this); 
    $.ajax({
        type: 'POST',
        url: elt.attr('itemref'),
        dataType: 'json',
        async: false,
        success: function (response) { 
            if(response.success){
                elt.replaceWith(response.html);
            }
            else{
                alert(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function () {   });
}

function functAjaxProcessErr(eltId,additionalDatas){
  alert('Error ! Problem when processing');  
}