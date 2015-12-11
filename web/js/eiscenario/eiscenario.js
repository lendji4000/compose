// ajoute la propriété pour le drop et le transfert de données
$.event.props.push('dataTransfer');
$(document).ready(function(){ 
     
     
    $(this).delegate( "#delete_folder","click", event_delete_folder); 
    $(this).delegate( ".show_node_childs","click", function(e){ 
        e.preventDefault();
        loadEiAjaxActions($(this),$(this).parent().find('.treeLoader').first(),"html",true,{"tooltipMsg":"Error occured when opening node..."},event_show_node_childs,scenAjaxProcessErr);
    });
    $(this).delegate( ".hide_node_childs","click", function(e){
        e.preventDefault();
        loadEiAjaxActions($(this),$(this).parent().find('.treeLoader').first(),"html",true,{"tooltipMsg":"Error occured when closing node..."},event_hide_node_childs,scenAjaxProcessErr);
    }); 
    
    /* Gestion des profils de scénario */
    $(this).delegate(".add_profil_scenario", "click", EventAddProfilScenario)
    $('.load_profil_scenario').delegate(".add_profil_scenario", "click", EventAddProfilScenario); 
    
    $(this).delegate( ".save_test_suit","click", event_save_test_suit);
    $(this).delegate( ".add_scenario","click", event_add_scenario);
    $(this).delegate( ".add_folder","click", event_add_folder);
    //Sauvegarder un scénario et rester dans la page d'ajout
    $(this).delegate( "#saveScenarioAndStay","click", saveScenario);
    $(this).delegate( "#saveScenario","click", saveScenario);
    $(this).delegate( "#saveFolder","click", saveFolder);
    
    $(this).delegate( ".download_scenario","click", EventdownloadScenario);
    $(this).delegate( "#create_scenario_clone","click", function(e){
        e.preventDefault();
        event_create_scenario_clone($(this));
    }); 
    $(this).delegate('.rename_node', 'click' , event_rename_node );
    $("#arbre_scenarios").on('click', '.folder', function(e){
        e.preventDefault();
        open_folder($(this));
    }); 
});

function event_save_test_suit(){
    $("#corps").find('form').first().submit();
}
//Sauvegarder un scénario et rester dans la page d'ajout
function saveScenario(e){ 
    e.preventDefault();
    var elt=$(this);   
    var redirect_after_save=0;
    
    if($("#saveScenarioAndStay").hasClass('processing')) return ;
    if($("#saveScenario").hasClass('processing')) return ;
    $("#saveScenario").addClass('processing');
    $("#saveScenarioAndStay").addClass('processing');
    if(elt.attr('id')==='saveScenario') redirect_after_save=1;
     var childs_list=$( "input[class='node_id'][value="+$('#ei_scenario_ei_node_root_id').val()+"]" )
             .parent().parent()
             .find( ".node_diagram" ).first() ; 
    $.ajax({
        type: 'POST',
        url: $('#ei_scenario_form').attr('action'),
        data: $('#ei_scenario_form').serialize() + '&redirect_after_save='+redirect_after_save,
        dataType: 'json',
        async: false,
        beforeSend: function() { $('#alertBox').remove();},
        success: function(response) {
            if (!response.success) {//Si erreur au niveau du formulaire
                $('#ei_scenario_form').replaceWith(response.html);//On recharge le formulaire entier
            }
            else { //La sauvegarde du formulaire a bien été éffectuée 
                if(response.redirect_after_save){
                    window.location.href = response.html;
                }
                else {
                    var parentShowHideChildsButton = childs_list.parent().prev().children('i:first');
                    if(parentShowHideChildsButton.hasClass('show_node_diagram'))
                    {
                        parentShowHideChildsButton.click();
                    }
                    childs_list.append(response.html);
                    $('#ei_scenario_form').remove();
                    $('#action_scenarios').html(response.flash_box);
                } 
            }
        },
        error: function(response) { 
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).done(function() { });
    $("#saveScenario").removeClass('processing');
}
//Fonction de sauvegarde d'un dossier de scénario
function saveFolder(e){ 
    e.preventDefault();
    var elt=$(this);    
    if($("#saveFolder").hasClass('processing')) return ;
    $("#saveFolder").addClass('processing');
     var childs_list=$( "input[class='node_id'][value="+$('#ei_folder_ei_node_root_id').val()+"]" )
             .parent().parent()
             .find( ".node_diagram" ).first() ; 
     var obj_id=$( "input[id='ei_folder_id']" ).val();
     var new_name =$( "input[id='ei_folder_name']" ).val();
    $.ajax({
            type: 'POST',
            url: $('#folder_edit_form').attr('action'),
            data: $('#folder_edit_form').serialize() ,
            dataType: 'json',
            async: false,
            beforeSend: function() { $('#alertBox').remove();},
            success: function(response) {
                if (!response.success) {//Si erreur au niveau du formulaire
                    $('#folder_edit_form').replaceWith(response.html);//On recharge le formulaire entier
                }
                else { //La sauvegarde du formulaire a bien été éffectuée  
                        if(!response.update_mode){
                            var parentShowHideChildsButton = childs_list.parent().prev().children('i:first');
                            if(parentShowHideChildsButton.hasClass('show_node_diagram'))
                            {
                                parentShowHideChildsButton.click();
                            }
                            childs_list.append(response.nodeLine);
                        } 
                        else{ 
                            //Mode update du dossier 
                            $( "input[class='obj_id'][value="+obj_id+"]" ).parent().find('.folder').html(
                                   ' <i class="fa fa-folder ei-folder"></i> '+ new_name ) ;
                        }
                        $('#action_scenarios').html(response.flash_box);
                    }  
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { $("#alertBox").fadeOut(2500);});
        $("#saveFolder").removeClass('processing');
}

function open_folder(obj){
    $.ajax({
        type: 'GET',
        url: obj.attr('href'),
        async: false,
        success: function(resp){
            $("#action_scenarios").html(resp);
            bindSortableEventOnFolder();
            $("#folder_edit_form").find("button").bind('click', function(e){
               //submitFolderForm(e); 
            });
        },
        error: function(){
            setToolTip("An error occured.", true);
        }
    });  
}
//Creation du clone d'un scénario
function createScenarioClone(obj){
    var new_name = $(":input[id=new_name_scenario][id=new_name_scenario]").val();
    //Vérification du nom du noeud
    if(verifedNodeName(new_name)){
        var name = '?new_name=' + new_name;
        var url = obj.attr('href') + name;
        window.location.href = url;
    }
}
function event_create_scenario_clone(obj){
    $('.fenetre').empty();
    $('.fenetre').append($("<input type='text' name='new_name_scenario' id='new_name_scenario'  />").show());
    var id_scenario = $(":input[class=id_scenario][name=id_scenario]").val();
    $('.fenetre').dialog({
        title: 'Save as' ,
        buttons:{
     
            'Confirm': function() {
                createScenarioClone(obj);
                
            },
            'Cancel': function() {
                $(this).dialog('close');
            }
        }

    });
    
    
    $('.fenetre').dialog('open');
    
}


/* Ajout d'un scénario */
function addNewScenario(root_id) {

    return $.ajax({
        type: 'GET',
        url: getRelocate('eiscenario', 'new', '-1') + '?root_id=' + root_id,
        async: false,
        
        success: function(e) {
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    }).responseText;
} 

//Fonction d'ajout d'un scénario

function event_add_scenario(){
    
    var root_id=$(this).parent().find(":input[class=node_id][name=node_id]").val();       
    $('#action_scenarios').html(addNewScenario(root_id));
}

//Fonction d'ajout d'un dossier de scénarios
function event_add_folder(e){ 
    e.preventDefault();
    var elt=$(this);      
    $.ajax({
            type: 'GET',
            url: elt.attr('href'), 
            async: false,
            beforeSend: function() { $('#alertBox').remove();},
            success: function(response) {
                 $('#action_scenarios').html(response);
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { });
}


//Téléchargement d'un scénario 

function downloadScenario(id_scenario,id_version,profile_id,profile_ref){
    var data='id_scenario='+ id_scenario + '&profile_id='+profile_id+'&profile_ref='+profile_ref;
    if(id_version!=null) data =data + '&id_version='+id_version ;
      window.location.href = $(":input[class=url_prefix][name=url_prefix]").val()+
    '/eiscenario/download?id_scenario='+id_scenario + '&id_version=' + id_version +
    '&profile_id='+profile_id+ '&profile_ref='+profile_ref;

}


function EventdownloadScenario(elt){

    var  profile_id;
    var  profile_ref;
    profile_id=$(this).children(":input[class=profile_id][name=profile_id]").val(); //téléchargement à partir du scenario
    profile_ref=$(this).children(":input[class=profile_ref][name=profile_ref]").val();
    if(!profile_id || !profile_ref) { 
        profile_id= $(this).parent().parent().find('.list_profils').find('.profils_scenario :selected').val(); 
    } //à partir du listing
    var id_scenario = $(this).children(":input[class=id_scenario][name=id_scenario]").val(); 
    if((profile_id!="") && (profile_ref!="") &&(id_scenario!="")){
        downloadScenario(id_scenario,null,profile_id,profile_ref);
    }
    else{
        alert ("choisir le profil!!")
    }

}
 
//Ronemmer un noeud (scénario ou folder)

function event_rename_node(){
    $('.fenetre').empty();
    $('.fenetre').append($("<input type='text' name='new_node_name' id='new_node_name'  />").show());
    var node_id = $(this).parent().find(":input[class=node_id][name=node_id]").val();
    var nodeType=$(this).parent().find(":input[class=node_type][name=node_type]").val();
    var obj_id=$(this).parent().find(":input[class=obj_id][name=obj_id]").val(); 
    $('.fenetre').dialog({
        title: 'Rename  :' ,
        buttons:{
     
            'Rename': function() {
                var new_node_name = $(":input[id=new_node_name][id=new_node_name]").val(); 
                  //Vérification du nom du noeud
                    if(verifedNodeName(new_node_name)){
                        return $.ajax({
                            type: 'GET',
                            data: 'node_id='+ node_id + '&new_node_name=' + new_node_name + '&nodeType=' +nodeType,
                            url: $(":input[class=url_prefix][name=url_prefix]").val()+'/einode/renameNode',
                            async: false,
                            success: function(text){
                                $('.rename_node').replaceWith(
                                "<a class='brand brand-width rename_node' title='Click to rename' href='#'>"+text+"</a>" );
                                 renameNodeInTree(obj_id,text,nodeType); 
                                $('.fenetre').dialog('close');
                            },
                            error: function(){
                                alert('Error when renaming ! ');
                                $('.fenetre').dialog('close');
                            }
                        }); 
                    } 
                    else return false;    
                
            },
            'Cancel': function() {
                $(this).dialog('close');
            }
        }

    });
    
    
    $('.fenetre').dialog('open');
}

 
//Add a folder

function event_add_folder_old(){
    $('#modalDiagram').modal('hide');
    var elt=$(this).parent().next().find('.node_diagram');
    $('.fenetre').empty();
    $('.fenetre').append($("<input type='text' name='folder_name' id='folder_name'  />").show());
    var root_id = $(this).parent().find(":input[class=node_id][name=node_id]").val(); 

    
    $('.fenetre').dialog({
        title: 'New Folder' ,
        buttons:{
     
            'Confirm': function() {
                var folder_name = $(":input[id=folder_name][id=folder_name]").val();
                //Vérification du nom du noeud
                if(verifedNodeName(folder_name)){
                    return $.ajax({
                            type: 'GET',
                            data: 'root_id='+ root_id + '&folder_name=' + folder_name +
                                '&project_ref='+$(":input[id=project_ref][name=project_ref]").val()+
                                    '&project_id='+$(":input[id=project_id][name=project_id]").val()+
                                    '&profile_ref='+$(":input[id=profile_ref][name=profile_ref]").val()+
                                    '&profile_id='+$(":input[id=profile_id][name=profile_id]").val(),
                            url: $(":input[class=url_prefix][name=url_prefix]").val()+'/eifolder/create',
                            async: false,
                            success: function(content){
                                elt.empty();
                                elt.append(content);
                                $('.fenetre').dialog('close'); 
                                setToolTip("Folder created successfully.", false);
                            },
                                    
                            error: function(response){ 
                            if (response.status === '401') window.location.href = window.location.pathname; 
                            
                                setToolTip('Could not create the folder. Make sur the name was correct.', true);
                                $('.fenetre').dialog('close');
                            }
                        });
                }
                else return false;
                           
                
            },
            'Cancel': function() {
                $(this).dialog('close');
            }
        }

    });
     
    $('.fenetre').dialog('open');
}


//Verification d'un nom de scénario ou de dossier
//Ce dernier ne doit pas etre vide et ne peut etre appeler Root car le root est unique sur le système
function verifedNodeName(node_name){
    if(!$.trim(node_name).length>0 ) {
        
        alert('Name Can\'t be empty' );
        return false;
    }
    if($.trim(node_name.toUpperCase())=="ROOT"){
        alert('The Root name must be unique, change  name' );
        return false;
    }
    else{
        return true;
    }
       
}


//Mettre à jour le champ nom d'un noeud de l'arbre
function renameNodeInTree(obj_id,new_node_name,nodeType){
var id , new_name;
    $('#test_suits_diagram').find('.lien_survol_node').each(function(e){
       id= $(this).find(":input[class=obj_id][name=obj_id]").val();
       if (id === undefined) ;
       else{
           if(id==obj_id)   {
               if($.trim(nodeType)=="EiScenario") new_name='<img alt="" src="/images/boutons/test_suit_img.png"> '+new_node_name;
               if($.trim(nodeType)=="EiFolder")   new_name='<i class="cus-folder"></i> ' + new_node_name;
               $(this).find('.node_name').html(new_name);
           }
             
       }
        
    });  
    
} 

 
//Ajout d'un nouveau profil sur une version de scenario
function addNewProfilScenario( elt) { 
    return $.ajax({
        type: 'GET',
        url: elt.attr('itemref'),
        async: false,
        success: function(e) {
            if(e.status !== "error"){ 
                elt.toggleClass('btn-success');
                elt.toggleClass('add_profil_scenario');
            }
            setToolTip(e.message, e.status === "error");
            return false;
        },
        error: function(e) {
            elt.toggleClass('btn-error');
            setToolTip("Internal servor error : unable to set profile to the current version.", true);
        }
    }).responseText;
}

function EventAddProfilScenario(e) {
    e.preventDefault();
    var elt = $(this); 
    addNewProfilScenario(elt); 
}




//Fonction de suppression d'un folder
function event_delete_folder(e) {
    e.preventDefault();
    if (confirm('You are about to delete the folder and all its children.'))
        window.location.href = $("#delete_folder").attr("href");
}
function bindSortableEventOnFolder() {

    $("#folder_childs").sortable({
        axis: "y", // Le sortable ne s'applique que sur l'axe vertical
        containment: "#folder_childs", // Le drag ne peut sortir de l'élément qui contient la liste
        distance: 1, // Le drag ne commence qu'à partir de 10px de distance de l'élément
        // Evenement appelé lorsque l'élément est relaché
        update: function(event, ui) {
            var position = getPositionInFolder(ui.item);
            reoderElts(ui.item.find(".node_id").val(), position+1);
        }
    });
}

function getPositionInFolder(item) {
    return $("#folder_childs .draggable").index(item);
}

function reoderElts(node_id, new_position) {
    //On change les positions des 2 noeuds après avoir éffectuer le drag & drop
    return $.ajax({
        type: 'GET',
        data: 'node_id=' + node_id + '&new_position=' + new_position,
        url: $(":input[class=url_prefix][name=url_prefix]").val() + '/einode/reoderElts',
        async: false,
        success: function() {
            setToolTip("Folder's content has been updated.", false);
        },
        error: function() {
            setToolTip("Folder's content could not be updated.", true);
        }
    });
}
  



/* Report du fichier folder.js */ 
 
function  event_hide_node_childs(response, elt){
            elt.parent().find('.show_node_childs').show();
            elt.parent().find('.arbo_tree').hide();
            elt.hide(); 
}
function  event_show_node_childs(response, elt){ 
    tree = elt.parent().find('.arbo_tree');
    tree.empty().append(response);  
    elt.parent().find('.hide_node_childs').hide();
    elt.parent().find('.hide_node_childs').first().show();
    tree.show();
    elt.hide();
}

/**
 * Envoi le formulaire form au serveur afin d'enregistrer la fonction.
 * @param {type} form
 * @returns {undefined}
 */
function saveFormEvent(form) {
    var res = false;

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        async: false,
        data: form.serialize(),
        dataType: 'json',
        success: function(data) {

            if (data.status == "error")
                setToolTip(data.message, true);
            else
                setToolTip(data.message, false);

            if (data.content !== undefined)
                res = data.content;
            //Insertion de la ligne de jeux de données venant d'être crée dans l'arbre
            if(data.is_create_mode){
                $( "input[class='node_id'][value="+data.parent_id+"]" )
                .parent().parent()
                .find( ".node_diagram" )
                .first()
                .append(data.dataSetNodeLine);  
            }
        },
        error: function(e) {
            setToolTip("Unable to process the save.", true);
        }
    });

    return res;
}

function scenAjaxProcessErr(eltId,additionalDatas){
  setToolTip(additionalDatas.tooltipMsg, true);
}