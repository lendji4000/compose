var project_id;
var project_ref;
var profil_id;
var profil_ref;
var profile_name;



$(document).ready(function() {  
    project_id = $("#project_id").val();
    project_ref = $("#project_ref").val();
    profil_id = $("#profile_id").val();
    profil_ref = $("#profile_ref").val();
    profile_name = $("#profile_name").val();
    /* Rafraichissement du projet par l'utilisateur */ 
    $(this).delegate("#refreshProject", "click", function(e) {  
        e.preventDefault(); 
        loadEiAjaxActions($(this),$('.reloading_img_tree'),"html",true,{},refreshProject,projectAjaxProcessErr);
    }); 
    $('.download_kalfonction').bind('click', function(e) {
        e.preventDefault();
 
        $('.fenetre').empty(); //on vide le contenu précédent de la fenètre 
        if ($('.fenetre').dialog('isOpen')) {
            $('.fenetre').empty();
        }

        DownloadKalFonctions(project_id, project_ref);
    });
/*
 *  Rechargement du projet s'il n'est pas à jour .
 *  Si le projet n'est pas à jour, le partiel reloader du module projet génère la div d'id "content_reloader".
 *  Cette div provoque dès l'instant qu'elle est visible un rechargement du projet processus demon mais sans impacter  
 *  l'expérience utilisateur.
 */ 
    if ($('#content_reloader').html() != null){ 
       refreshProjectWithoutRedirect( $('#content_reloader').attr('datasrc'));
    } 
}); 


function refreshProject(response, elt) { 
     $('.arbre_projet').parent().parent().replaceWith(response);
     activePopoversAndTooltip(); 
}

function reloadProject(uri,callBackFunction, action) { 
    return $.ajax({
        type: 'GET',
        url: uri,
        async: true,
        success: callBackFunction,
        beforeSend : function (e){  $('.reloading_img_tree').show(); },
        error: function(ex) {
            alert('Error occur when reloading project');
        }
    }).done(function(){   $('.reloading_img_tree').hide(); // $('#content_reloader').remove();
    });
}
//Chargement des fonctions et profils d'un projet'
function DownloadKalFonctions(uri) {
    var fen;
    //ICI tu modifie les OPTIONS de ta boite de dialog que tu as definie au début de l execution du code dans $(document).ready();
    fen = $('.fenetre').dialog('option', 'buttons', {
        'Confirmer': function() {

            return $.ajax({
                type: 'GET',
                url: uri,
                async: true,
                beforeSend : function (e){  
                },
                success: function(e) { 
                    window.location.href = $('.download_kalfonction').children().first().attr('href'); 
                },
                error: function(ex) {
                    window.location.href = $('.download_kalfonction').children().first().attr('href');
                }
            }).done(function(){
                $('.reloading_img_tree').hide();
            });

        },
        'Annuler': function() { 
            $(this).dialog('close');
        }
    });

    //ICI TU OUVRE TON DIALOG
    $('.fenetre').dialog('open');
    return false;

} 

function refreshProjectWithoutRedirect (uri){
             project_id = $(":input[class=project_id][name=project_id]").val();
             project_ref = $(":input[class=project_ref][name=project_ref]").val();
            reloadProject(uri,function(e) {  }, 'DownloadKalFonctions');
            activePopoversAndTooltip(); 
        } 

function getUri() {
    return $(":input[class=url_depart][name=url_depart]").val();
}

function projectAjaxProcessErr(eltId,additionalDatas){
  alert('Error ! Problem when processing');  
}