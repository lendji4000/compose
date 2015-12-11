$(document).ready(function() {
    definedStateForScenario();
    definedStateForJdd();
    definedStateForFile();
});
function definedStateForScenario() {
    $(".rightSideStep .scenarioInCampaign ").each(function() { 
        var elt = $(this);  
        $("#campaignContentList .step_scenario_id ").each(function() {
            var elt2 = $(this);
            if (elt.val() === elt2.val()) {
                elt.parent().parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
    });
    //Arbre des scénarios
    $(":input[value=EiScenario][class=node_type]").each(function() { 
        var elt = $(this).parent().find('.obj_id');  
        $("#campaignContentList .step_scenario_id ").each(function() {
            var elt2 = $(this);
            if (elt.val() === elt2.val()) {
                elt.parent().find('a').first().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
    });
}


function definedStateForJdd() {
    $(".rightSideStep .jddInCampaign ").each(function() { 
        var elt = $(this); 
        $("#campaignContentList .step_jdd_id ").each(function() {
            var elt2 = $(this);
            if (elt.val() === elt2.val()) {
                elt.parent().parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
    });
    //Arbre des jeux de données
    $(":input[class=data_set_id]").each(function() {
        var elt = $(this);  
        $('input[class=step_jdd_id][value="' + elt.val() + '"]').each(function() {  
                elt.parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic'); 
        });
    });
}

function definedStateForFile() {
    $(".rightSideStep .fileInCampaign ").each(function() { 
        var elt = $(this);
        //S'il n'ya aucune step dans la campagne , on change le statut 
        $("#campaignContentList .step_file_name ").each(function() {
            var elt2 = $(this);
            if (elt.val() === elt2.val()) {
                elt.parent().parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
    });
}
function checkScenarioInCampaign(step_scenario_id, step_jdd_id, step_file_name,scenario_tree_id) {
    /* On est en affichage classique des steps et on veut définir tous les statuts */
    if (step_scenario_id === 0 && step_jdd_id === 0 && step_file_name === 0 && scenario_tree_id === 0) {
        if ($("#campaignContentList .stepLineInContent ").length > 0) {
            definedStateForScenario();
            definedStateForJdd();
            definedStateForFile();
            
        }
        
    } 
    /*Cas d'un suppression ou ajout de step à la campagne*/
    else {
        if (step_scenario_id !== undefined || scenario_tree_id!==undefined)
            setStateOnAddOrDeleteScenario(step_scenario_id,scenario_tree_id);
        if (step_jdd_id !== undefined)
            setStateOnAddOrDeleteJdd(step_jdd_id);
        if (step_file_name !== undefined)
            setStateOnAddOrDeleteFile(step_file_name);
    }
 
}
 

/* Cas d'ajout ou de suppression suppression d'une step de campagne */
function setStateOnAddOrDeleteScenario(step_scenario_id) {
    if ($('input[class=step_scenario_id][value="' + step_scenario_id + '"]').length > 0) {
        //alert('good');
        $(".rightSideStep .scenarioInCampaign ").each(function() {
            var elt = $(this);
            if (elt.val() === step_scenario_id) {
                elt.parent().parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
        //Arbre des scénarios   
            $(":input[value="+step_scenario_id+"][class=obj_id]").each(function() {
                var elt = $(this);
                var node_type=elt.parent().find('.node_type');
                if (elt.val() === step_scenario_id && node_type==='EiScenario') {
                    elt.parent().find('a').first().addClass('rightSideStepOk');
                    elt.parent().css('font-style', 'italic');
                }
            });  
            definedStateForJdd();
    }
    else {
        //alert('good2');
        $(".rightSideStep .scenarioInCampaign ").each(function() {
            var elt = $(this);
            if (elt.val() === step_scenario_id) {
                elt.parent().parent().removeClass('rightSideStepOk');
                elt.parent().css('font-style', 'normal');
            }
        });
        //Arbre des scénarios
        $(":input[value="+step_scenario_id+"][class=obj_id]").each(function() {
                var elt = $(this);
                if (elt.val() === step_scenario_id) {
                    elt.parent().find('a').first().removeClass('rightSideStepOk');
                    elt.parent().css('font-style', 'normal');
                }
            }); 
    }
    definedStateForScenario();
            
}

function setStateOnAddOrDeleteJdd(step_jdd_id) {
    //Si on retrouve des éléments avec l'id du jeu de données renseigné
    if ($('input[class=step_jdd_id][value="' + step_jdd_id + '"]').length > 0) {
        if ($('input[class=jddInCampaign][value="' + step_jdd_id + '"]').length > 0) {
            //Si des éléments dans la campagne de droite possèdent cet id 
            $('input[class=jddInCampaign][value="' + step_jdd_id + '"]').each(function() {
            $(this).parent().parent().addClass('rightSideStepOk');
            $(this).parent().css('font-style', 'italic');
            });
        } 
        if ($('input[class=data_set_id][value="' + step_jdd_id + '"]').length > 0) {
            //Si des éléments dans l'arbre des jeux de données de droite possèdent cet id 
            $('input[class=data_set_id][value="' + step_jdd_id + '"]').each(function() {
            $(this).parent().addClass('rightSideStepOk');
            $(this).parent().css('font-style', 'italic');
            });
        } 
        //On supprime les codes couleurs sur les éléments n'existant plus sur l'arbre de droite
        deleteStateOnJdd();
    }//Aucun élément à droite n'est retrouvé avec cet id , donc on retire les codes couleurs sur tous les éléments trouvés
    else {
        $('input[class=jddInCampaign][value="' + step_jdd_id + '"]').each(function() { 
                $(this).parent().parent().removeClass('rightSideStepOk');
                $(this).parent().css('font-style', 'normal'); 
        });
        $('input[class=data_set_id][value="' + step_jdd_id + '"]').each(function() { 
                $(this).parent().removeClass('rightSideStepOk');
                $(this).parent().css('font-style', 'normal'); 
        });
    }
    //On refait un parcours global pour s'assurer que tout est en ordre
    definedStateForJdd();
}

function setStateOnAddOrDeleteFile(step_file_name) {
    if ($('input[class=step_file_name][value="' + step_file_name + '"]').length > 0) {
        //alert('good');
        $(".rightSideStep .fileInCampaign ").each(function() {
            var elt = $(this);
            if (elt.val() === step_file_name) {
                elt.parent().parent().addClass('rightSideStepOk');
                elt.parent().css('font-style', 'italic');
            }
        });
    }
    else { 
        $(".rightSideStep .fileInCampaign ").each(function() {
            var elt = $(this);
            if (elt.val() === step_file_name) {
                elt.parent().parent().removeClass('rightSideStepOk');
                elt.parent().css('font-style', 'normal');
            }
        });
    }
    definedStateForFile();
}

//Supprime les codes couleurs des jeux de données ne se trouvant pas dans la campagne
function deleteStateOnJdd() {
    $('input[class=jddInCampaign]').each(function() {
        var elt = $(this);
        if (!($('input[class=step_jdd_id][value="' + elt.val() + '"]').length > 0)) {
            elt.parent().parent().removeClass('rightSideStepOk');
            elt.parent().css('font-style', 'normal');
        }
    });
    //Arbre des jeux de données
    $('input[class=data_set_id]').each(function() {
        var elt = $(this);
        if (!($('input[class=step_jdd_id][value="' + elt.val() + '"]').length > 0)) {
            elt.parent().removeClass('rightSideStepOk');
            elt.parent().css('font-style', 'normal');
        }
    }); 
}