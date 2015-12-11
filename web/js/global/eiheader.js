$(document).ready(function(){
     $(this).delegate("#inline_checkbox_all","click",function(){
         if($(this).attr("checked")){
             $("#inline_checkbox_success").attr("checked",false);
             $("#inline_checkbox_failed").attr("checked",false);
             $("#inline_checkbox_never_plan").attr("checked",false);
             $("#inline_checkbox_aborted").attr("checked",false);
         }
        
    });
    $(this).delegate("#inline_checkbox_success,#inline_checkbox_failed,#inline_checkbox_never_plan,#inline_checkbox_aborted","click",function(){
         if($(this).attr("checked")){
             $("#inline_checkbox_all").attr("checked",false); 
         }
        
    });
    $("#button_token_api_show").click(function(event){
        event.preventDefault();

        $.ajax({
            type: "GET",
            context: this,
            dataType: 'json',
            url: $("input[name=url_to_get_api_key]").val(),
            error:function(data)
            {
                console.log( data );

                enableAllApiTokenInput();
            },
            success:function(data)
            {
                console.log(data);

                enableAllApiTokenInput();

                $("#input_token_api").val(data.token).select();
            },
            beforeSend: function(){
                disableAllApiTokenInput();
            }
        });


        return false;
    });

    $("#button_token_api_generate").click(function(event){
        event.preventDefault();

        displayConfirmMessage($(this).attr("data-confirm"), regenerateAccessToken);

        return false;
    });
});

function disableAllApiTokenInput(){
    $("#input_token_api").attr("disabled", "disabled");
    $("#button_token_api_generate").attr("disabled", "disabled");
    $("#button_token_api_show").attr("disabled", "disabled");
}

function enableAllApiTokenInput(){
    $("#input_token_api").attr("disabled", false);
    $("#button_token_api_generate").attr("disabled", false);
    $("#button_token_api_show").attr("disabled", false);
}

/**
 * Méthode effectuant l'appel AJAX permettant de regénérer le TOKEN.
 */
function regenerateAccessToken()
{
    $.ajax({
        type: "GET",
        context: this,
        dataType: 'json',
        url: $("input[name=url_to_generate_api_key]").val(),
        error:function(data)
        {
            console.log( data );

            enableAllApiTokenInput();
        },
        success:function(data)
        {
            console.log(data);

            enableAllApiTokenInput();

            $("#input_token_api").val(data.token).select();
        },
        beforeSend: function(){
            disableAllApiTokenInput();
        }
    });
}

/**
 *
 * @param message
 * @param callback
 */
function displayConfirmMessage(message, callback)
{
    //***************************************************//
    //*****     Confirmation de la regénération     *****//
    //***************************************************//

    if (!$('#dataConfirmModal').length) {
        $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Confirmation</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">No</button><a class="btn btn-danger" id="dataConfirmOK">Yes</a></div></div></div></div>');
    }

    $('#dataConfirmModal').find('.modal-body').text(message);

    $('#dataConfirmOK').click(function(event){
        callback();

        $('#dataConfirmModal').modal("hide");
    });

    $('#dataConfirmModal').modal({
        show:true
    });
}

/* Gestion des changements du coverage d'une campagne */ 
function progress(elementID, indicateur)
{

    document.getElementById(indicateur).style.width = "0%";


    var indicator = document.getElementById(indicateur);
    var valeur = document.getElementById(elementID).value;

    if (!(/^[0-9]{0,3}$/.test(valeur))) {
        document.getElementById(elementID).value = 0;
        valeur = 0;
    }
    valeur = Math.min(100, valeur);
    indicator.innerHTML = valeur + "%";
    indicator.style.width = valeur + "%";


    if (valeur <= 50)
        indicator.style.backgroundColor = "rgb(255," + Math.ceil((valeur * 2 * 255) / 100) + ",0)";

    else
        indicator.style.backgroundColor = "rgb(" + Math.ceil(((100 - valeur) * 2 * 255) / 100) + ", 255,0)";
}

jQuery.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ?
                matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^s+|s+$/g,''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
}