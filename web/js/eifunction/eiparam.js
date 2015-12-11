newfieldsKalParamcount = 0;
function event_collapse_params(){
    $(this).parent().parent().parent().find('fieldset').hide();
    $(this).parent().find('.extend_params').show();
    $(this).hide();
}
function event_extend_params(){
    $(this).parent().parent().parent().find('fieldset').show();
    $(this).parent().find('.collapse_params').show();
    $(this).hide();
}
 
$(document).ready(function(){
  $(this).delegate('.addKalParamField','click',addKalParamField);
  $(this).delegate('.removeKalParamField','click',removeKalParamField); 
  $(this).delegate('#saveKalFunction','click',saveKalFunction);
  $(this).delegate('#kalFunctionForm','submit',function(e){
      e.preventDefault();
      return false;
  });  
  
  
  /* Gestion des paramètres de fonction  (EiFunctionHasParam )*/
   
    
    $(this).delegate('#functionParamModal', 'shown.bs.modal', function (e) {
        e.preventDefault();
        loadEiAjaxActions($(e.relatedTarget),$(".eiLoading"),"json",true,{},openModalFunctionParam,paramAjaxProcessErr) ;
    });
    $(this).delegate('#functionParamModal', 'hidden.bs.modal', function (e) {
        $("#functionParamModalBody").empty();
    });
    /* Sauvegarde d'un paramètre de fonction */ 
    $(this).delegate('#saveFunctionParam', 'click', function (e) {
        e.preventDefault();
        var name = $(this).parent().parent().find('form').find('#ei_function_has_param_name').val(); 
        if ($.trim(name).length > 0) {//Si le champ name n'est pas vide 
            loadEiAjaxForm($("#functionParamModalBody").find("form"),$(".eiLoading"),"json",true,saveFunctionParam); 
        }
        else{
            alert('Please fill parameter name ...');
        }
    });
    
    
    $(this).delegate('.deleteFunctionParam','click',function(e){  
        $(this).hide();
        if(confirm("Sure , you want to delete this parameter?"))
            loadEiAjaxActions($(this),$(this).parent().find(".saveLoader"),"json",true,{},deleteParam,paramAjaxProcessErr) ;
        else
            $(this).show();
    });    
});  


/* Gestion des ajouts de paramètres à une fonction pour la plate forme centrale */
function removeKalParamField(e){ 
    e.preventDefault();
    $(this).parent().parent().remove(); 
}

function addKalParamField(e){
    e.preventDefault();
    var elt=$(this);
    var listParams=elt.parents('.listKalParams');
    addNewKalParamField(elt.attr('href'),newfieldsKalParamcount,listParams.find('tbody'));
    
}

function addNewKalParamField(uri,num,content){ 
$.ajax({
    type: 'GET',
    url: uri,
    data: 'num=' + num ,
    dataType: 'json',
    async: false,
    beforeSend: function() { },
    success: function(response) {
        if (response.success)  content.append(response.html);
    },
    error: function(response) {
        if (response.status == '401')
            window.location.href = window.location.pathname;
        alert('Error ! Problem when processing');
    }
}).done(function() {  newfieldsKalParamcount = newfieldsKalParamcount + 1; });
}
/* Sauvegarde d'une fonction et de ses paramètres par webservice sur la plate forme centrale */

    function saveKalFunction(e) {
        e.preventDefault(); 
        var result; 
        if($("#saveKalFunction").hasClass('processing')) return ;
        $("#saveKalFunction").addClass('processing');
        $.ajax({
            type: 'POST',
            url: $('#kalFunctionForm').attr('action'),
            data: $('#kalFunctionForm').serialize(),
            dataType: 'json',
            async: true, 
            beforeSend: function() { $('.eiLoading').show() ;   },
            success: function(response) {
                if (response.success) { result=true;  }
                else { $('#kalFunctionForm').replaceWith(response.html);  } 
            },
            error: function(response) {  
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { $('.eiLoading').hide();
        if(result){ 
                $('#addKalFunctionModal').modal('hide');
                var root_elt=$(":input[class=node_id][value="+$('#addKalFunctionModal')
                        .find(":input[class=node_id][name=node_id]").val()+"]").parent();   
                root_elt.find('.show_node_childs').first().click();
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
        $("#saveKalFunction").removeClass('processing');
    }
    
    
/* Traitement des paramètres ei_function_has_param (Ceux se trouvant sur Script) */



/* Fonction permettant de visualiser les changements du paramètre et de provoquer les sauvegardes automatiques */
function changeParamEvent(elt, previousText) {
    if ($.trim(previousText) != $.trim(elt.val())) {
        //On vérifie qu'on est dans le cas d'une mise à jour de la fonction
        if ($.trim(elt.parents('.paramsForm').find('#ei_function_has_param_name').val()).length > 0) {
            clearTimeout(timeout);
            timeout = setTimeout(function() { 
                elt.unbind('textchange');
                eventSaveParam(elt);
                elt.bind('textchange');
            }, 300);
        }
    } 
}



 

//Suppression d'un paramètre
function deleteParam(response , elt) {  
    if(response.success){
        elt.parents('tr').remove(); 
    } 
    else{
        elt.show();
        alert('error when trying to delete parameter ...');
    }
}   

/* Ajout ou edition d'un paramètre de fonction */
function openModalFunctionParam(response , elt){ 
    if (response.success) {   
        $("#functionParamModalBody").empty().append(response.html);
    }
}
/* Sauvegarde d'un paramètre de fonction */
function saveFunctionParam(response) {

    if (response.success) {   //Cas d'une édition de paramètre 
        if ($('.param_line_' + response.param_id).length >0 ) {  
            $('.param_line_' + response.param_id).replaceWith(response.html);  //On remplace la ligne existante par la réponse
        }
        else { //Cas d'une création d'un paramètre de fonction 
            if(response.param_type==="IN"){ 
                 $("#inParams").append(response.html);
            } 
            else{ 
                $("#outParams").append(response.html);
            }
                
        }
        $("#functionParamModalBody").empty();
        $("#functionParamModal").modal("hide");
    }
    else {//Echec de la sauvegarde du paramètre
        $("#functionParamModalBody").find("form").replaceWith(response.html);
    } 
}
 
 
 function paramAjaxProcessErr(eltId,additionalDatas){ 
     alert('Error on function paramters process ...');
  return false;
}