var nombreParamsBlock = 0;
var paramBlockInProcess = false;

$(document).ready(function() {
    $(this).delegate( ".addParamToBlockButton","click", addParamToBlock);
    $(this).delegate(".removeParamToBlockButton", "click", removeParamToBlock);
});

/**
 *
 * @param name
 * @returns {number}
 */
function getNewIdParamBlock(name){

    var name = typeof name != "undefined" ? name:".editBlockParamsFormList";
    var id = 0;

    // On récupère le dernier ID du paramètre.
    var derniereLigne = $(name.concat(" tbody tr:last-child td:first-child input"));

    if( typeof derniereLigne != "undefined" && typeof derniereLigne.attr("name") != "undefined" ){
        var numberLigne = derniereLigne.attr("name").match(/[0-9]+/);

        if( typeof numberLigne != "undefined" && typeof numberLigne[0] != "undefined" ){
            id = parseInt(numberLigne[0]) + 1;
        }
    }

    return id;
}

/**********          ECRAN VERSION/SCENARIO          **********/
/**************************************************************/

/**
 * Fonction permettant de calculer les éléments à envoyer à la méthode qui va se charger
 * de récupérer le formulaire pour le nouveau paramètre.
 *
 * @param event
 */
function addParamToBlock(event){

    event.preventDefault();

    var elt = $(this);
    var parametresBloc = elt.parents('.blockParamsFormList');
    var formParent = elt.parents(".editBlockParamsFormList");
    var formRoot = elt.parents("form");

    console.log(formRoot);

    if( parametresBloc.length != 0 ){
        addNewParamToBlock(elt.attr('href'), getNewIdParamBlock(".blockParamsFormList"), parametresBloc.find('tbody'), formRoot.find("input[name='typeBlock']"));
    }
    else if( formParent.length != 0 ){
        addNewParamToBlock(elt.attr('href'), getNewIdParamBlock(), formParent.find('tbody'), formRoot.find("input[name='typeBlock']"));
    }

}

/**
 * Méthode réalisant l'appel Web Service afin d'ajouter le nouveau paramètre au formulaire du block.
 *
 * @param uri
 * @param size
 * @param content
 */
function addNewParamToBlock(uri, size, content, caseType){

    var typeForm = typeof caseType.val() != "undefined" ? caseType.val():"EiBlock";

    $.ajax({
        type: 'GET',
        url: uri,
        data: 'size=' + size + '&type='+caseType.val() ,
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.success){
                content.append(response.html);
            }
        },
        error: function(response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}


/**
 * Fonction permettant de supprimer un paramètre d'un bloc sur le formulaire d'ajout rapide pour la plateforme centrale.
 *
 * @param event
 */
function removeParamToBlock(event){
    event.preventDefault();

    var parentFormId = $(this).parents("form").attr("id");

    $(this).parent().parent().remove();

    if( $(this).attr("data-id") != "" ){
        $("#blockParametersMappingIn").find("tr[data-id='"+$(this).attr("data-id")+"']").remove();
    }

    if( parentFormId == "EditEiBlockParamsForm" ){
        updateBlockToVersion(event);
    }
}

/**********          ECRAN DATA SETS         *********/
/*****************************************************/

function bindBlockParamEvents() {
    addBlockParamEvent();
    deleteBlockParamEvent();
    submitAddedBlockParamEvent();
}

function addBlockParamEvent() {
    $(".add_block_param").bind('click', function(e) {
        var link = $(this);
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            async: false,
            success: function(res) {
                $("#block_parameters").append(res);
                deleteNewBlockParamEvent();
                submitAddedBlockParamEvent();
            },
            error: function(res) {
                setToolTip("An error occured. Block's parameter creation form could not be retrieved.", true);
            }
        });
    });
}

function submitAddedBlockParamEvent() {
    $(".submit_block_param").unbind('click');
    $(".submit_block_param").bind('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('submit_block_param');
        var form = $(this).parent().parent().parent();
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            async: true,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status == "error") {
                    setToolTip(data.message, true);
                    $(this).toggleClass('submit_block_param');
                }
                else {
                    setToolTip(data.message, false);
                    form.attr('action', data.action);
                    var delLink = form.find(".delete-btn");
                    delLink.toggleClass('delete_block_param_old');
                    delLink.toggleClass('delete_block_param_new');
                    delLink.attr('href', data.link);
                    deleteBlockParamEvent();
                }
            },
            error: function(e) {
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });
    });
}

function deleteBlockParamEvent() {
    $(".delete_block_param_old").unbind('click');
    $(".delete_block_param_old").bind('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('delete_block_param_old');
        var link = $(this);
        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            async: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === "ok") {
                    setToolTip(res.message);
                    deleteBlockParamFromDOM(link);
                } else {
                    $(this).toggleClass('delete_block_param_old');
                }
            },
            error: function(res) {
                $(this).toggleClass('delete_block');
                setToolTip("An error occured. Block could not be deleted due to internal server error.", true);
            }
        });
    })
}

/**
 * 
 * @returns {undefined}
 */
function deleteNewBlockParamEvent() {
    $(".delete_block_param_new").bind('click', function(e) {
        e.preventDefault();
        deleteBlockParamFromDOM($(this));
    });
}

/**
 * 
 * @param {type} link
 * @returns {undefined}
 */
function deleteBlockParamFromDOM(link) {
    link.parent().parent().parent().remove();
}
