
$(document).ready(function(){
    
    //$(this).delegate( ".add_new_f","click", event_add_new_f);
    $(this).delegate('.checked_place_to_add','click' , event_checkched_place_to_add);
    $(this).delegate('.remove_occurence_fonction','click' , event_remove_occurence_fonction);
    $(this).delegate( ".fonction_moins_info","click", event_fonction_moins_info);
    $(this).delegate( ".fonction_plus_info","click", event_fonction_plus_info);
    $(this).delegate( ".delete_fonction","click", event_delete_fonction);
    $(this).delegate( ".collapse_params","click", event_collapse_params);
    $(this).delegate( ".extend_params","click", event_extend_params);
    $(this).delegate( ".desc_kal_plus_info","click", event_desc_kal_plus_info);
    $(this).delegate( ".desc_kal_moins_info","click", event_desc_kal_moins_info);
    
});

function event_desc_kal_moins_info(){
    $(this).parents('.details_fonction_observation').find('.desc_kal_fonction').hide();
    $(this).parents('.details_fonction_observation').find('.desc_kal_plus_info').show();
    $(this).hide();
}
    
function event_desc_kal_plus_info (){
    //alert('plus info');
    $(this).parents('.details_fonction_observation').find('.desc_kal_fonction').show();
    $(this).parents('.details_fonction_observation').find('.desc_kal_moins_info').show();
    $(this).hide();
}
function event_fonction_moins_info(){ 
    $(this).parentsUntil('.fonction').parent().find('.params_fonction').hide();
    $(this).parentsUntil('.fonction').find('.detail_fonction').hide();
    $(this).parentsUntil('.fonction').find('.fonction_plus_info').css("display", "inline-block");
    $(this).hide();
}
    
function event_fonction_plus_info (){
    $(this).parentsUntil('.fonction').find('.detail_fonction').show();
    $(this).parentsUntil('.fonction').parent().find('.params_fonction').show();
    $(this).parentsUntil('.fonction').find('.fonction_moins_info').css("display", "inline-block");
    $(this).parentsUntil('.fonction').find('.desc_kal_plus_info').hide();
    $(this).parentsUntil('.fonction').find('.desc_kal_moins_info').show();
    $(this).hide();
}

function event_delete_fonction(){
    var elt=$(this);
    var id_fonction = $(elt).children(":input[class=id_fonction][name=id_fonction]").val();
    //alert(id_fonction);
    $('.fenetre').empty(); //on vide le contenu précédent de la fenètre
    if ($('.fenetre').dialog('isOpen')) {
        $('.fenetre').empty();
    }
    deleteFonction(id_fonction,$(this));
    $(elt).parents('.detail_fonction').nextAll('.detail_fonction').find(":input[class=position_fonction]").each(function(){
        $(this).val(parseInt($(this).val()) - 1);
    });
}

//Retirer une occurence de fonction d'un scénario
function event_remove_occurence_fonction(){
    $(this).parent().parent().parent().parent().parent().next().remove();
    $(this).parent().parent().parent().parent().parent().remove();
    $(this).parents('.detail_fonction').nextAll('.detail_fonction').find(":input[class=position_fonction]").each(function(){
        $(this).val(parseInt($(this).val()) - 1);
    });
}
 
//Choix de la place ou l'on veut ajouter une fonction au scénario

function event_checkched_place_to_add(){
    $('.checked_place_to_add').removeClass("lighter");
    $(this).addClass("lighter");
}
 
//Suppression d'une fonction

function deleteFonction(id_fonction,elt){
    //ICI tu modifie les OPTIONS de ta boite de dialog que tu as definie au début de l execution du code dans $(document).ready();
    $('.fenetre').dialog({
        title: 'La fonction va etre supprimer du scénario!! etes-vous sur?' ,
        buttons:{
     
            'Confirm': function() {

                return $.ajax({
                    type: 'GET',
                    data: 'id='+ id_fonction,
                    url: $(":input[class=url_prefix][name=url_prefix]").val()+'/eifonction/delete',
                    async: false,
                    success: function(e){
                        $('.fenetre').dialog('close');
                        $(elt).parents('.detail_fonction').next().remove();
                        $(elt).parents('.detail_fonction').remove();
                    }
                });

            },
            'Annuler': function() {

                $(this).dialog('close');
            }
        }

    });
    //ICI TU OUVRE TON DIALOG
    $('.fenetre').dialog('open');
}




/* report du fichier  statistics.js */
$(document).ready(function(e){
    $(".statistics").bind('click', function(e){
       $.ajax({
        url: $(this).attr('data-stats'),
        async: false,
        dataType: 'html',
        success: function(e) {
            $("#corps").html(e);
        },
        error: function(e) {
            setToolTip("A problem occured trying to retrieve function's stats.", true);
        }
    });
        
    })
})


