/**
 * Fichiers des fonctions JS utilisées sur l'écran d'édition d'une version
 * d'un scénario;
 * 
 * @author Grégory Elhaimer
 */

var ei_block_params;
var inputBlockValues = {};

function autocompletionParamsEvent() {
    if (typeof ei_block_params !== "undefined"){ 
        $(".fonction_form").find('textarea')
            .sew({values: ei_block_params, token: '#', elementFactory: elementFactory})
            .on("sew.element.click", function(event, obj){
                var form = $(this).parentsUntil(".fonction_form").parent();
                clearTimeout(timeoutId);
                timeoutId = setTimeout(function() {
                    saveFormEvent(form);
                }, 500);
            })
        ;
    }
}

var customItemTemplate = "<div><span />&nbsp;<small /></div>";

function elementFactory(element, e) {
    var template = $(customItemTemplate).find('span')
            .text('#' + e.val + "").end()
            .find('small')
            .text("(" + e.meta + ")").end();

    element.append(template);
}

/**
 * Event déclenché à l'ajout d'une fonction
 * au sein d'une version/sous version
 * @param event l'evenement
 */
function addFonctionEvent(event) {
    addEvent(event, $(this));
    $(".fonction_form").find('textarea').on('keyup', function() {
        var form = $(this).parentsUntil(".fonction_form").parent();
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            saveFormEvent(form);
        }, 500);
    });
    setPopOverEventParam();
}

/**
 * Déclarer un evenement pour l'objet listener en précisant quel
 * lien lui est associé dans le curseur.
 * 
 * @param event
 * @param clickedFonction la fonction cliquée (false si ce n'est pas une fonction)
 */
function addEvent(event, clickedFonction) {
    var url = getCurseur().find('.add_fonction_link').attr('href');

    if (clickedFonction)
        url = getUrlForAddFonction(clickedFonction, url);
    $.ajax({
        type: 'GET',
        url: url,
        async: false,
        success: function(res) {
            //insère la nouvelle version
            getCurseur().after(res);
            //replacement du curseur
            goToNextCurseur();
            setToolTip("Function added.", false);

            autocompletionParamsEvent();

            bindFunctionEvents();
        }
    });
}

/**
 * Place le curseur la ligne suivante à sa position actuelle
 */
function goToNextCurseur() {
    var curs = getCurseur();
    curs.nextAll('.checked_place_to_add').first().attr('class', 'checked_place_to_add lighter');
    curs.attr('class', 'checked_place_to_add');
}

/**
 * Retourne une URL formatée pour l'ajout d'une fonction dans la version du scenario
 * @param obj la fonction qui a été cliquée dans l'arbre
 * @param url la base de l'url
 * @return string url
 */
function getUrlForAddFonction(obj, url) {
    var conte = obj.parent().parent();
    var id_fct = conte.children('.obj_id').first().val();
    var ref_fct = conte.children('.ref_obj').first().val();

    return url + '/function_id/' + id_fct + '/function_ref/' + ref_fct;
}

/**
 * Retourne la position que le curseur représentent
 */
function getCurseurPosition() {
    var position = getCurseur().prevAll('.checked_place_to_add').size();
    if (typeof position === 'undefined')
        return 0;
    else
        return parseInt(position);
}

/**
 * Retourne l'objet représentant le curseur positonnée
 * @return l'objet représentant le curseur
 */
function getCurseur() {
    return $('.checked_place_to_add.lighter');
}

/**
 * Position le curseur
 * @param place l'objet qui devient le curseur
 */
function setCurseur(place) {
    getCurseur().attr('class', 'checked_place_to_add');
    place.attr('class', 'checked_place_to_add lighter');
}

function setCurseurAtFirst(){
    $(".checked_place_to_add").first().addClass("lighter");
}

/**
 *  Supprime l'élément a supprimer ainsi que le curseur qui le suit.
 *  @param obj l'objet
 *  @param sel le selecteur
 */
function deleteElement(obj, sel) {
    toHide = obj.parentsUntil(sel).parent().parent();
    toHide.next().remove();
    setCurseur(toHide.prev());
    toHide.remove();
}

/**
 * Event de suppression d'une fonction
 */
function deleteFunctionEvent(event) {
    event.preventDefault();
     var selector = $(this);
    $.ajax({
        url: $(this).attr('href'),
        async: false,
        dataType: 'json',
        success: function(e) {
            if (e.status === "ok") {
                deleteElement(selector, '.fonction');
                setToolTip(e.message, false);
            }
            else {
                setToolTip(e.message, true);
            }
        },
        error: function(e) {
            setToolTip("A problem occured trying to delete function.", true);
        }
    });


}



/**
 * Sélectionne la fonction (changement de background)
 * @returns {undefined}
 */
function selectFonction() {
    focusFct = $(this).children('.fonction');
    $('.selectedColor').toggleClass('selectedColor');
    $(this).children('.fonction').toggleClass('selectedColor');
}

/**
 * Création d'un effet visuel comme témoins de la sauvegarde
 * 
 * @param {type} obj
 * @param {type} colorStart
 * @param {type} colorEnd
 * @param {type} speed
 * @returns {undefined}
 */
function fadeOutSuccess(obj, colorStart, colorEnd, speed) {
    obj.toggleClass('selectedColor');
    obj.css('background-color', colorStart).animate({backgroundColor: colorEnd}, speed, function() {
        focusFct.addClass('selectedColor');
    });
}

function bindFunctionEvents() {
    autocompletionParamsEvent();
    autoSaveEvent(".fonction_form", 'textarea');
    autoSaveEvent("#donnees_version", 'textarea');
    autoSaveEvent(".fonction_form", "select");
    autoSaveEventForVersionName();
    $(document).on('focus', '.fonction_form', selectFonction);
    setPopOverEventParam();
}

var timeoutId = 0;
var focusFct = null;

function autoSaveEvent(selector, input) {
    if( input == "select" ){
        event = "change";
    }
    else{
        event = "keyup";
    }

    $(selector).find(input).on(event, function(e) {
        var form = $(this).parentsUntil(selector).parent();
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            var res = saveFormEvent(form);
            
        }, 500);
    });
}

function autoSaveEventForVersionName() {
    $("#donnees_version").find('input').on('keyup', function(e) {
        var form = $(this).parentsUntil("#donnees_version").parent();
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            saveFormEvent(form);
            var chevron = $(".active_version").find('a').children('i').first();
            $(".active_version").find('a').text($("#donnees_version").find('input').val());
            $(".active_version").find('a').append(chevron);
        }, 500);
    });
}

function getParameterByName(href, name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(href);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/*************************************************
 * AJOUT D'UN BLOCK DANS LA VERSION.
 */
/* Ajout d'un block sur la plate forme centrale à partir de compose */
function addBlockToVersion(event){
    event.preventDefault();

    var url = getCurseur().find('.add_block_link').attr('href');
    var element = $(this);
    var typeBlock = getParameterByName(element.attr("href"), "type");

    if( typeof url == "undefined" ){
        alert("There is a problem. Please, make sure cursor is set.");
    }
    else{

        if( typeBlock != "" ){
            url += "?type="+typeBlock;
        }

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            async: false,
            success: function(response) {
                $('.addBlockToVersionModalBody').empty().append(response.html);
                $('#addBlockToVersionModal').modal('show');
            },
            error: function(response) {
                if (response.status == '401')
                    window.location.href = window.location.pathname;

                alert('Error ! Problem when processing');
            }
        });
    }
}

function saveAndInsertBlockToVersion(event){
    event.preventDefault(); 
    var url = getCurseur().find('.add_block_link').attr('href');
    var element = $(this);
    var form = $("#EiBlockForm");

    blockElement(form);

    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: form.serialize(),
        async: true,
        success: function(res)
        {
            // SI echéc, on affiche la mise à jour du formulaire.
            if( res.success == false )
            {
                $("#EiBlockForm").replaceWith(res.html);
            }
            // SINON, on ajoute les éléments dans la structure du scénario & de la version.
            else if( res.success == true )
            {
                //=> Ajout dans la structure de la version.
                $("#menu_blocks").html(res.menu_structure);
                getCurseur().after(res.html);
                goToNextCurseur();

                //=> Modification de l'arbre de la structure du scénario.

                // SI block ajouté en milieu/fin...
                if ( res.insert_first == false && res.insert_after != false )
                {
                    $("li[ei_block=" + res.insert_after + "]").after(res.itemTree);
                    $("li[ei_block=" + res.insert_after + "]").next().find('.close_block').first().hide();
                }
                // SINON Si block ajouté au début...
                else if( res.insert_after == false && res.insert_first != false )
                {
                    var block = $("li[ei_block=" + res.insert_first + "]");

                    if (block.children().last().attr('class') === "opened") {
                        block.children().last().prepend(res.itemTree);
                    }
                    else {
                        block.append('<ul class="opened" ></ul>');
                        block.children().last().prepend(res.itemTree);
                    }

                    block.children().last().find('.close_block').first().hide();

                    //block.children('.padding-left').first().removeClass('padding-left');
                    block.children().first().children('.open_block').first().removeClass('hidden');
                    block.children().first().children('.open_block').first().hide();
                    block.children().first().children('.close_block').first().show();
                }

                unblockElement(form);

                changeBlockScopeEvent();
                deleteBlockEvent();

                //=> On masque la fenêtre modale.
                $('#addBlockToVersionModal').modal('hide');

                //=> Et on indique à l'utilisateur que l'opération s'est déroulée avec succès.
                setToolTip("Block added.", false);
            }
        },
        error: function(event){
            unblockElement(form);
        }
    });
}

function registerInputTextValue(event){
    inputBlockValues[$(this).attr("id")] = $(this).val();
}

function evaluateInputTextValue(event){
    if( $(this).val() != inputBlockValues[$(this).attr("id")] ){
        updateBlockToVersion(event);
    }
}

/**
 * Evénement déclenché lors de la soumission du formulaire de mise à jour du block.
 *
 * @param event
 */
function updateBlockToVersion(event){
    // On désactive le comportement par défaut de l'élément.
    event.preventDefault();

    // Récupération du formulaire.
    var form = $("#EditEiBlockParamsForm");

    form.block({
        message: '<h1>Processing</h1>',
        css: { border: '3px solid #a00' }
    });

    $.ajax({
        type: "POST",
        url: form.attr("action"),
        dataType: 'json',
        data: form.serialize(),
        async: true,
        success: function(data)
        {
            if( typeof data.html != "undefined" ){
                form.replaceWith(data.html);
            }

            // SI succès, on met à jour le nom du block.
            if( data.success == true )
            {
                $("li[ei_block=" + data.id + "] div.current_block").replaceWith($(data.itemTree).html());
                $("li[ei_block=" + data.id + "]").find(".close_block").first().hide();
                bindTreeEvents(false);
                changeBlockScopeEvent();
                setToolTip(data.message, false);

                console.log("EiBlockParams", ei_block_params);

                // Mise à jour des selects de synchronisation OUT.
                // On récupère les paramètres de block.
                var paramsToInsert = [{
                    value: "",
                    text: ""
                }];
                var selectedParam;

                $("input:regex(id,ei_block_EiBlockParams_[0-9]+_name)").each(function(event){
                    paramsToInsert.push({
                        value: $("#"+$(this).attr("id").replace("name", "id")).val(),
                        text: $(this).val()
                    });
                });

                console.log(paramsToInsert);

                // On retire tout ce qu'il y a dans les selects.
                $("select:regex(id,ei_fonction_mappings_[0-9]+_ei_param_block_id)").each(function(event){
                    selectedParam = $(this).val();
                    $(this).empty();

                    for( indParam in paramsToInsert ){
                        $(this).append($("<option></option>")
                            .attr("value", paramsToInsert[indParam].value)
                            .text(paramsToInsert[indParam].text)
                            .prop("selected", selectedParam == paramsToInsert[indParam].value ? "selected":"")
                        );
                    }
                });

                $("textarea.field").sew.refreshValues(ei_block_params);

            }
            else{
                setToolTip(data.message, true);
            }

            form.unblock();
        },
        error: function(handler){
            form.unblock();
        }
    });
}

$(document).ready(function() {
    $(document).delegate('.get_path_function', 'click', addFonctionEvent);
    $(document).delegate('.fonction_delete', 'click', deleteFunctionEvent);

    $(document).delegate('.addBlockToVersion', 'click', addBlockToVersion);
    $(document).delegate("#saveBlockToVersion", 'click', saveAndInsertBlockToVersion);
    $(document).delegate("#EiBlockForm", 'submit', saveAndInsertBlockToVersion);
    $(document).delegate("#EditEiBlockParamsForm input, #EditEiBlockParamsForm textarea", "focus", registerInputTextValue);
    $(document).delegate("#EditEiBlockParamsForm input, #EditEiBlockParamsForm textarea", "blur", evaluateInputTextValue);

    bindFunctionEvents();
});

function blockElement(element){
    element.block({
        message: '<h1>Processing</h1>',
        css: { border: '3px solid #a00' }
    });
}

function unblockElement(element){
    element.unblock();
}
