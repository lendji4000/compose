$(document).ready(function() { 
    /* Nouveau contexte */
    $(this).delegate(".newBugContext",'click', newBugContext); 
    $(this).delegate("#saveBugContextInStep",'click', saveBugContextInStep);
    /*
     * Gestion des variations du choix de la campagne dans u l'édition d'un contexte de bug 
     */
    $(this).delegate('#ei_bug_context_campaign_id','change',renderCampaignStepWidget);
    
    
}); 


/* Sauvegarde d'un contexte de création d'un bug dans une campagne */
function saveBugContextInStep(e){  
    e.preventDefault();  
    $.ajax({
            type: 'POST',
            url: $('#bugContextForm').attr('action'),
            data: $('#bugContextForm').serialize(),
            dataType: 'json',
            async: false,
            beforeSend: function() {   },
            success: function(response) {
                $("#bugContextForm").replaceWith(response.html);
                if(response.success){  
                     $("#contextModalTitle").text('Intervention Context'); 
                     $("#saveBugContextInStep").hide();
                }    
                else  
                    $("#bugContextForm").replaceWith(response.html);
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        });
}
//Edition d'une step de campagne
function newBugContext(e){
    e.preventDefault();
    var elt = $(this);
    $("#saveBugContextInStep").show();
    $("#contextModal").modal("show");
    renderBugContextForm(elt,false);
}
//Récupération d'un formulaire d'ajout ou édition d'un contexte 
function renderBugContextForm(elt,editMode){
    $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false, 
            success: function(response) { 
                if(response.success){
                    $("#contextModal").find('.bugContextBody').empty().append(response.html); 
                }  
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        });   
    }
    
function renderCampaignStepWidget(e){
    e.preventDefault();
    var elt = $(this);
    var itemref=elt.parents('.contextCampaignFormPart').find('.contextCampaignFormPartHref').attr('itemref');
    $.ajax({
            type: 'POST',
            url:  itemref,
            data : 'campaign_id = ' + elt.val(),
            dataType: 'json',
            async: false, 
            success: function(response) { 
                if(response.success){
                    $('#subjectForm').find('.contextCampaignStepFormPart').replaceWith(response.html);
                }
                    
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        });
}        
