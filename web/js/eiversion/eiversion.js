$(document).ready(function(){
     String.prototype.trim = function() {return $.trim(this)} 
    $(this).delegate( ".extend_all","click", event_extend_all);
    $(this).delegate( ".collapse_all","click", event_collapse_all);
    $(this).delegate( ".extend_version ","click", event_extend_version );
    $(this).delegate( ".collapse_version ","click", event_collapse_version );
    $(this).delegate( "#recherche_version","click", event_recherche_version);
    /* Switch sur le package d'une version avec rechargement de cette dernière */
    $(this).delegate("#switchToDefaultPackageAndReload","click" , function(){
        $.ajax({
        type :  "GET",
        url :  $(this).attr("itemref"),
        async: true, 
        dataType: "json",
        success : function (response){ 
            if(response.success)
                window.location.reload();
        }
    }).responseText;
    });
    $(this).delegate( "#versionStructure","click", function(){
        $('#openBlockPropertiesParameters').parent().removeClass('active'); 
        $('#versionProp').removeClass('active');

        $('#versionStructure').addClass('active');
    });
    $(this).delegate( "#versionProp","click", function(){
        $('#openBlockPropertiesParameters').parent().removeClass('active'); 
        $('#versionStructure').removeClass('active');
        
        $('#versionProp').addClass('active');
    });
    $(this).delegate( "#create_version","click", function(e){
        e.preventDefault();
        event_add_version();
    });
    $(this).delegate( "#delete_version_modal_opener","click", event_delete_version);
    $(this).delegate( "#create_version_clone","click", function(e){
        e.preventDefault();

        event_create_version_clone($(this));
    });
    $(this).delegate( ".rename_or_delete_version" , "mousedown" ,event_rename_or_delete_version);
    //Empecher le menu contextuel par defaut en cas de click droit sur la version à renommer
    $(".rename_or_delete_version").bind("contextmenu", function() {
    return false;
    });
     
    //empecher l'envoie du formulaire de création de version si le libelle est vide
    
    $('.fenetre').delegate('form', 'submit', function(e){
        if($("#ei_version_libelle").length >0){
            if ($("#ei_version_libelle").val().trim() != "") {
                return true;
            }
            else{
                fadeOutColor($("#ei_version_libelle"),'red', '#ffc3c3', 2000);
                return false;
            }
        }
        else{
            if($("#ei_scenario_nom_scenario").length >0){
                if ($("#ei_scenario_nom_scenario").val().trim() != "") {
                    return true;
                }
                else{
                    fadeOutColor($("#ei_scenario_nom_scenario"),'red', '#ffc3c3', 2000);
                    return false;
                }
            }
        }
        return false;
    });
});

function event_rename_or_delete_version(e){ 
    $(document)[0].oncontextmenu = function() {return false;} 
    if( e.button == 2 ) {
      $('.fenetre').empty();
      $('.fenetre').append($("<input type='text' name='new_name_version' id='new_name_version'  />").show());  
      $('.fenetre').dialog({
          
        title: 'Set version name or delete version !' ,
        buttons:{
            'Set Name': function() {
                 var id_version = $(":input[class=ei_version_id]").val();
                var new_name = $(":input[id=new_name_version][id=new_name_version]").val();
                //on teste si le nom du scénario est renseigné
                if(!$.trim(new_name).length>0) {
        
                    alert('Empty Name : Enter a the new name ');
                    return false;
                }
                return $.ajax({
                    type: 'GET',
                    data: 'id_version='+ id_version + '&new_name=' + new_name +
                                '&project_id='+$(":input[id=project_id][name=project_id]").val()+
                                '&project_ref='+$(":input[id=project_ref][name=project_ref]").val(),
                    url: $(":input[class=url_prefix][name=url_prefix]").val()+'/eiversion/rename',
                    async: false,
                    success: function(text){
                        $('.rename_or_delete_version').find('a').text(text);
                        $('.fenetre').dialog('close');
                        $(document)[0].oncontextmenu = function() {return true;} 
                    },
                    error: function(text){
                        alert("Unreachable project or test suite version ! try again");
                        $('.fenetre').dialog('close');
                        $(document)[0].oncontextmenu = function() {return true;} 
                    }
                });
                      

            },
            'Cancel': function() {
                 $(document)[0].oncontextmenu = function() {return true;} 
                $(this).dialog('close');
            }
        }
    }); 
    
      $('.fenetre').dialog('open');
    }
    else{
        $(document)[0].oncontextmenu = function() {return true;} 
    }
    
  }
  
function event_collapse_all(){
    $('#scrolled_box_version').find('.detail_fonction').hide();
    $('#scrolled_box_version').find('.params_fonction').hide();
    $('#scrolled_box_version').find('.fonction_moins_info').hide();
    $('#scrolled_box_version').find('.fonction_plus_info').css("display", "inline-block");
    $('#scrolled_box_version').find('.collapse_params').hide();
    $('#scrolled_box_version').find('.extend_params').show();
    $('#scrolled_box_version').find('.paramètres_version ').find('fieldset').hide();
    $('#donnees_version').find('.extend_all').show();
    
}

function event_extend_all (){
    $('#scrolled_box_version').find('.detail_fonction').show();
    $('#scrolled_box_version').find('.params_fonction').show();
    $('#scrolled_box_version').find('.fonction_moins_info').css("display", "inline-block");
    $('#scrolled_box_version').find('.fonction_plus_info').hide();
    $('#scrolled_box_version').find('.collapse_params').show();
    $('#scrolled_box_version').find('.extend_params').hide();
    $('#scrolled_box_version').find('.collapse_all').show();
   
}
function event_extend_version(){
    $(this).parent().parent().find('.params_fonction').show();
    $(this).parent().parent().find('.fonction_moins_info').show();
    $(this).parent().parent().find('.fonction_plus_info').hide();
    $(this).parent().parent().find('.collapse_params').show();
    $(this).parent().parent().find('.extend_params').hide();
    $(this).parent().parent().find('.paramètres_version ').find('fieldset').show();
    $(this).parent().parent().find('.collapse_all').show();
}
function event_collapse_version(){
    $(this).parent().parent().find('.params_fonction').hide();
    $(this).parent().parent().find('.fonction_moins_info').hide();
    $(this).parent().parent().find('.fonction_plus_info').show();
    $(this).parent().parent().find('.collapse_params').hide();
    $(this).parent().parent().find('.extend_params').show();
    $(this).parent().parent().find('.paramètres_version ').find('fieldset').hide();
    $(this).parent().parent().find('.extend_all').show();
}
//Suppréssion d'une version
function event_delete_version(e){
    e.preventDefault();
    
    if($(".selected_profile").length === 0){
        $($(this).attr('href')).modal('show');
    }
    else
        setToolTip('You can not delete version assigned to profile(s).', true);
}
            
            
function event_recherche_version(){
    var recherche=$(this).val();
    var id_scenario= $('#id_scenario').val();
    var tab=new Array;
    tab['id_scenario']=id_scenario;
    tab['libelle']=recherche;
    if(recherche.length>2){
        $('#resultat_recherche_version').append(getVersion(tab));
    }
}


function getVersion(tab){ 
    return $.ajax({
        type :  "GET",
        url : $(":input[class=url_prefix][name=url_prefix]").val()
                +"/eiversion/searchVersion" 
                + '?id_scenario=' + tab['id_scenario'] 
                + '&libelle=' + tab['libelle'],
        async: false,
        data : tab,
        success : function (){  }
    }).responseText;
}
   
/**
 * Renvoie l'URL vers laquelle l'action de creation de version doit envoyer
 * @param module le module
 * @param action l'action a déclencher
 * @param id_scenario l'identifiant du scenario
 */
function getCreateVersionUrl(id_scenario){
        var profile_name = "profil";
        var profile_id = '0';
        var profile_ref= '0';
    if($(':input[name=profile_id]').val() != 0){
        profile_name = $(":input[id=profile_name][name=profile_name]").val();
        profile_id = $(":input[id=profile_id][name=profile_id]").val();
        profile_ref = $(":input[id=profile_ref][name=profile_ref]").val();
    }
        
    return $(":input[class=url_prefix][name=url_prefix]").val()
            +"/eiprojet/"
            +$(":input[id=project_id][name=project_id]").val()+"/"
            +$(":input[id=project_ref][name=project_ref]").val()+"/"
            +profile_name+"/"
            +profile_id+"/"
            +profile_ref
            +'/eiscenario/'+id_scenario +'/eiversion/new';
}   

//Ajout d'une nouvelle version à un scénario
function addNewVersion(){
    return $.ajax({
        type: 'POST',
        url: $("#create_version").attr('href'),
        async: false,
        success: function(e){
        }
    }).responseText;
}

function event_add_version(){
    $('.fenetre').empty(); //on vide le contenu précédent de la fenètre
    $('.fenetre').dialog({
        title: 'Create a new version' ,
        buttons:{
            'Create': function(e) {
                if($('.fenetre').find('#ei_version_libelle').val().length > 0)
                    $('.fenetre').children('form').first().submit();
                else
                    alert("Name can not be empty.");
                
            },
            'Cancel': function() {
                $(this).dialog('close');
            }
        }

    });
        
    $('.fenetre').append(addNewVersion());
     $('.fenetre').dialog({
        height:250
    }); //Adaptation de la taille de la fenêtre à celle du formulaire 
    if ($('.fenetre').dialog('isOpen')) {
        $('.fenetre').empty();
    }
   
    
    $('.fenetre').dialog('open');
}
 

function event_create_version_clone(clicked){
    var new_name = $(":input[id=new_name_version]").val();
    var uri = clicked.attr("href").substring(0,clicked.attr("href").length-1);
    
    //on teste si le nom de la version est renseigné
    if(!$.trim(new_name).length>0) {
        
        alert("Version's name can not be empty.");
        return false;
    }
    else{
        window.location.href = uri+'&new_name=' + new_name;
    }

}
