$(document).ready(function() {    
      $(this).delegate('.migrateBugFunction', 'click', {class_to_remove: "migrateBugFunction"},eventMigrateBugObj);
      $(this).delegate('.migrateBugScenario', 'click', {class_to_remove: "migrateBugScenario"},eventMigrateBugObj);
      //Choix de plusieurs scénarios de sujet pour migration
      $(this).delegate('#check_scenarios_for_migration,#check_scenarios_for_migration_del', 'click',  {objType: "EiScenario"}, check_obj_for_migration); 
      //Choix de plusieurs fonctions de sujet pour migration
      $(this).delegate('#check_functions_for_migration,#check_functions_for_migration_del', 'click', {objType: "EiFunction"},  check_obj_for_migration);  
      
      //Migration d'une sélection de scénarios pour un sujet (bug)
      $(this).delegate('#migrateSelectedScenarios', 'click', eventMigrateManyScenariosToProfile);
      //Migration d'une sélection de fonctions pour un sujet (bug)
      $(this).delegate('#migrateSelectedFunctions', 'click', eventMigrateManyToProfile);
//      $(this).delegate('#migrateSelectedFunctionsDelivery','click', eventMigrateManyToProfileInDeleivery)
      
      //Lien de changement du profil de migration des scripts
      $(this).delegate(".changeProfileForMigration,.changeProfileForMigrationScenario", "click", eventChangeProfileForMigration);
      //Lien de changement du profil de migration des scripts d'une livraison
//      $(this).delegate(".changeProfileForMigrationDelivery", "click", eventChangeProfileForMigrationDelivery);
      $(this).delegate(".choosePackageForMigrationWhenConflict",'change',choosePackageForMigrationWhenConflict);
      $(this).delegate(".choosePackageForMigrationWhenConflictScenario",'change',choosePackageForMigrationWhenConflictScenario);
});
//Récupération des fonctions sélectionnées pour la migration
function getSelectedFunctionsForMigration() {
    //Récupération du profile courant
    var current_profile_id = $("#profilesForMigration").find(":input[class=current_profile_migration_id]").val(),
            current_profile_ref = $("#profilesForMigration").find(":input[class=current_profile_migration_ref]").val(),
            current_profile_name = $("#profilesForMigration").find(":input[class=current_profile_migration_name]").val();
    //On vérifie que les informations du profil courant on été bien récupérées
    if (current_profile_id !== undefined && current_profile_ref !== undefined && current_profile_name !== undefined)
    {
        var tab = new Array();
        var i = 0;
        $('.function_line').each(function() {//Pour chaque ligne de fonction
            var function_id = $(this).find(":input[class=function_id]").val();
            var function_ref = $(this).find(":input[class=function_ref]").val();
            var ticket_id = $(this).find(":input[class=ticket_id]").val();
            var ticket_ref = $(this).find(":input[class=ticket_ref]").val();
            var function_line = $(this);
            if ($(this).find('.check_function_for_migration').is(':checked')) {//Si la fonction est cochée
                
                //On vérifie que le profil de migration n'y est pas coché
                function_line.find('.migrateBugFunction').each(function() {
                    if ($.trim($(this).text()) == $.trim(current_profile_name) && ticket_id!==0 && ticket_ref!==0) {
                        tab[i++] = new Array(function_id, function_ref,ticket_id,ticket_ref);
                    }

                });
            }
        }); //alert(tab.join('|'));
        return tab.join('|');
    }
    else {alert(current_profile_id + '/'+ current_profile_ref + '/' +current_profile_name);
        alert('No profile selected');
        return false;
    } 
}

//Récupération des scenarios sélectionnées pour la migration
function getSelectedScenariosForMigration() {
    //Récupération du profile courant
    var current_profile_id = $("#profilesForMigrationScenario").find(":input[class=current_profile_migration_id]").val(),
            current_profile_ref = $("#profilesForMigrationScenario").find(":input[class=current_profile_migration_ref]").val(),
            current_profile_name = $("#profilesForMigrationScenario").find(":input[class=current_profile_migration_name]").val();
    //On vérifie que les informations du profil courant on été bien récupérées
    if (current_profile_id !== undefined && current_profile_ref !== undefined && current_profile_name !== undefined)
    {
        var tab = new Array();
        var i = 0;
        $('.scenario_line').each(function() {//Pour chaque ligne de scénario
            var ei_scenario_id = $(this).find(":input[class=ei_scenario_id]").val(); 
            var package_id = $(this).find(":input[class=package_id]").val();
            var package_ref = $(this).find(":input[class=package_ref]").val();
            var scenario_line = $(this);
            if ($(this).find('.check_scenario_for_migration').is(':checked')) {//Si le scénario est cochée
                
                //On vérifie que le profil de migration n'y est pas coché
                scenario_line.find('.migrateBugScenario').each(function() {
                    if ($.trim($(this).text()) == $.trim(current_profile_name) && package_id!==0 && package_ref!==0) { 
                        tab[i++] = new Array(ei_scenario_id,package_id,package_ref);
                    }  
                });
            }
        });  
        return tab.join('|');
    }
    else {
        alert('No profile selected');
        return false;
    } 
}

/* Migration d'un objet (fonction, scenario) d'un bug sur un profil */
function eventMigrateBugObj(e) { 
    e.preventDefault();
    var elt = $(this);
    var tmp = elt;
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        beforeSend: function() { // traitements JS à faire AVANT l'envoi
            //Initialisation du loader 
            elt.removeClass(e.data.class_to_remove);
            elt.attr('href', '#');
            elt.find('.loaderProfile').show();
        },
        success: function(response) {
            if (response.success) {
                elt.addClass('btn-success');
            }
            else {
                elt.replaceWith(tmp);
            }

        },
        error: function(response) {
            //alert(window.location.pathname);
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert("Something went wrong");
        }
    }).done(function() {
        //On masque le loader 
        elt.find('.loaderProfile').hide();
    });
} 

/* Migration de plusieurs scripts de fonction à la fois */

function eventMigrateManyToProfile(e) {
    e.preventDefault();
    var elt = $(this);
    var tmp = $('#functionsOfTicket');
    //Récupération du tableau des fonction sélectionnées
    var tab = getSelectedFunctionsForMigration(); 
    //Si le tableau retourné n'est pas vide
    if (tab.length > 0) { 
        $.ajax({
            type: "POST",
            url: elt.attr('href'),
            data: 'tab=' + tab,
            dataType: 'json',
            beforeSend: function() { // traitements JS à faire AVANT l'envoi
                //Initialisation du loader   
                $('#functionsOfTicketList').html('');
                $('#loading-indicator').show();
            },
            success: function(response) {
                if(response.success){
                    $('#functionsOfTicketList').replaceWith(response.html);
                }  
            },
            error: function(response) {
                //alert(window.location.pathname);
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                elt = tmp;
            }
        }).done(function() {
            //On masque le loader  
            $('#loading-indicator').hide();
        });

    }

}
/* Migration de plusieurs scénarios de bug à la fois */

function eventMigrateManyScenariosToProfile(e) { 
    e.preventDefault();
    var elt = $(this);
    var tmp = $('#scenariosOfTicketList');
    //Récupération du tableau des fonction sélectionnées
    var tab = getSelectedScenariosForMigration(); 
    //Si le tableau retourné n'est pas vide
    if (tab.length > 0) { 
        $.ajax({
            type: "POST",
            url: elt.attr('href'),
            data: 'tab=' + tab,
            dataType: 'json',
            beforeSend: function() { // traitements JS à faire AVANT l'envoi
                //Initialisation du loader   
                $('#scenariosOfTicketList').html('');
                $('#loading-indicator').show();
            },
            success: function(response) {
                if(response.success){
                    $('#scenariosOfTicketList').replaceWith(response.html);
                }  
            },
            error: function(response) {
                //alert(window.location.pathname);
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                elt = tmp;
            }
        }).done(function() {
            //On masque le loader  
            $('#loading-indicator').hide();
            
        });

    } 

} 

/* Cocher toutes les objets à migrer d'un coup */

function check_obj_for_migration (e){ 
    var class_line, check_class;
    if(e.data.objType==="EiScenario"){//On est dans le cas de migration des scénarios
        class_line=$('.scenario_line');
        check_class='.check_scenario_for_migration';
    }
    else{
        if(e.data.objType==="EiFunction"){//On est dans le cas de migration des fonctions
            class_line=$('.function_line');
            check_class='.check_function_for_migration';
        } 
    }
        if ($(this).is(':checked')) {//S'il est coché , alors je coche tous les autres
            class_line.find(check_class).each(function() {
                if (!$(this).is(':checked'))//S'il n'est pas coché alors je le coche 
                    $(this).attr('checked', true);
            });

        }
        else {//on decoche
            class_line.find(check_class).each(function() {
                if ($(this).is(':checked')) //S'il est coché , alors je le décoche
                    $(this).attr('checked', false);
            });
        }
    }  
    
    /* Changement d'un profil de migration */
function eventChangeProfileForMigration(e) {
    e.preventDefault();
    var elt = $(this);
    $.ajax({
        type: 'POST',
        url: elt.attr('href'),
        dataType: 'json',
        success: function(response) {
            if (response.success){
                if(elt.hasClass('changeProfileForMigrationScenario'))
                 $('#profilesForMigrationScenario').replaceWith(response.html);
             else
                 $('#profilesForMigration').replaceWith(response.html);
            }
               

        },
        error: function(response) {
            //alert(window.location.pathname);
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert("Something went wrong");
        }
    });
}

///* Changement d'un profil de migration des fonctions d'une livraison */
//function eventChangeProfileForMigrationDelivery(e) {
//    e.preventDefault();
//    var elt = $(this);
//    $.ajax({
//        type: 'POST',
//        url: elt.attr('href'),
//        dataType: 'json',
//        success: function(response) {
//            if (response.success)
//                $('#profilesForMigrationDelivery').replaceWith(response.html);
//
//        },
//        error: function(response) {
//            //alert(window.location.pathname);
//            if (response.status == '401')
//                window.location.href = window.location.pathname;
//            alert("Something went wrong");
//        }
//    });
//}


function choosePackageForMigrationWhenConflict(e) {
    e.preventDefault(); 
    var elt=$(this);
var optionSelected = $("option:selected", $(this));
var selectedUri = optionSelected.attr('itemref');
if (selectedUri !== undefined) { 
    $.ajax({
        type: 'POST',
        url: selectedUri,
        dataType: 'json',
        beforeSend: function () { },
        success: function (response) {
            if (response.success) {
                optionSelected.parents('.function_line').find('.function_line_profiles').replaceWith(response.html);
                elt.parents('.function_line').find('.ticket_id').val(optionSelected.attr('itemid'));
                elt.parents('.function_line').find('.ticket_ref').val(optionSelected.attr('itemtype'));
            }  
        },
        error: function (response) {  
            alert("Something went wrong");
        }
    }).done(function () { });
}
else{
    optionSelected.parents('.function_line').find('.function_line_profiles').replaceWith(
            "<div class='function_line_profiles'> Conflicts detected on function: <a href='#'> Solve them?</a> ");
    $(this).parents('.function_line').find('.ticket_id').val(0);
    $(this).parents('.function_line').find('.ticket_ref').val(0);
}
}
/* Resolve conflict on scénario when detected */
function choosePackageForMigrationWhenConflictScenario(e) {
    e.preventDefault(); 
    var elt=$(this);
var optionSelected = $("option:selected", $(this));
var selectedUri = optionSelected.attr('itemref');
if (selectedUri !== undefined) { 
    $.ajax({
        type: 'POST',
        url: selectedUri,
        dataType: 'json',
        beforeSend: function () { },
        success: function (response) {
            if (response.success) {//alert('good');
                optionSelected.parents('.scenario_line').find('.scenario_line_profiles').replaceWith(response.html);
                elt.parents('.scenario_line').find('.package_id').val(optionSelected.attr('itemid'));
                elt.parents('.scenario_line').find('.package_ref').val(optionSelected.attr('itemtype'));
            }  
        },
        error: function (response) {  
            alert("Something went wrong");
        }
    }).done(function () { });
}
else{
    optionSelected.parents('.scenario_line').find('.scenario_line_profiles').replaceWith(
            "<div class='scenario_line_profiles'> Conflicts detected on scenario: <a href='#'> Solve them?</a> ");
    $(this).parents('.scenario_line').find('.package_id').val(0);
    $(this).parents('.scenario_line').find('.package_ref').val(0);
}
}