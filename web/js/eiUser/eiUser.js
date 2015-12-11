$(document).ready(function(){  
    $(this).delegate(".editUserProfileParam","click", eventEditUserProfileParam);  
    $(this).delegate(".saveUserProfileParam","click", eventSaveUserProfileParam);     
    $(this).delegate('.userProfileParamCase', 'mouseover',function(){
       $(this).find('.userProfileParamCaseIcon').show(); 
    });
    $(this).delegate('.userProfileParamCase', 'mouseout',function(){
       $(this).find('.userProfileParamCaseIcon').hide(); 
    });
    //Reset d'un paramètre de profil utilisateur
    $(this).delegate(".resetUserProfileParam","click", eventResetUserProfileParam); 
    /* Changement du profil par défaut d'un utilisateur */
    $(this).delegate(".setDefaultUserProfile","click",setDefaultUserProfile);
});
function eventEditUserProfileParam(e){ 
    e.preventDefault();
    var elt=$(this);    
    $.ajax({
            type: 'POST',
            url: elt.attr('href'), 
            dataType: 'json',
            async: false,
            beforeSend: function() {  },
            success: function(response) {
                if (response.success) {//Si tout se passe bien
                    elt.parents('.userProfileParamCase').empty().append(response.html);//On récupère le formulaire d'édition
                }
                else {  alert(response.html);   } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { });
}
//Sauvegarde d'un paramètre de profil  niveau utilisateur
function eventSaveUserProfileParam(e){
    e.preventDefault();
    var elt=$(this);  
    var form=elt.parents('.userProfileParamForm');
    $.ajax({
            type: 'POST',
            url: form.attr('action'),  
            data: form.serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function() {  },
            success: function(response) {
                if (response.success) {//Si tout se passe bien
                    elt.parents('.userProfileParamCase').replaceWith(response.html);//On récupère le formulaire d'édition
                }
                else { form.replaceWith(response.html)  } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { });
}
/* Reset d'un paramètre de profil utilisateur */
function eventResetUserProfileParam(e){
    e.preventDefault();
    var elt=$(this);   
    $.ajax({
            type: 'POST',
            url: elt.attr('itemref'),  
            dataType: 'json',
            async: false,
            beforeSend: function() {  },
            success: function(response) {
                if (response.success) {//Si tout se passe bien
                    elt.parents('.userProfileParamCase').replaceWith(response.html);//On récupère le formulaire d'édition
                }
                else { alert(response.html);  } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { });
}

/* Changement du profil par défaut d'un utilisateur */
function setDefaultUserProfile(e){
    e.preventDefault();
    var elt=$(this);
    var oldDef=$("#currentDefaultProfile");
    var success=false;
    var resp;
    $.ajax({
            type: 'POST',
            url: elt.attr('itemref'),  
            dataType: 'json',
            async: true,
            beforeSend: function() {  },
            success: function(response) {
                success=response.success;
                resp=response; 
                $("#projectProfilesAlerts").addClass(response.alertClass).find('span').text(response.html);
                $("#projectProfilesAlerts").show();
                if (response.success) {//Si tout se passe bien 
                    elt.attr("class",response.resultClass).attr("title",response.resultTitle).text(response.resultText);
                    oldDef.attr("class",response.oldDefaultClass);
                    oldDef.attr("title",response.oldDefaultTitle).text(response.oldDefaultText).attr("id",response.oldDefaultId);
                    $("#projectProfilesListWarning").remove();
                } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { 
            if(success){ 
                elt.attr("id",resp.resultId);
            }
    });
}