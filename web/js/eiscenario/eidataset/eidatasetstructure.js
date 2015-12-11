var classe_close_element_tree = "close_datasetstructure_node";
var classe_open_element_tree = "open_datasetstructure_node";

$(document).ready(function() { 
    bindDataSetStructureEvents();
    bindDataSetStructureTreeEvents();
}); 
/**********     EVENEMENTS
 **********************************************************************************************************************/

/**
 * Fonction permettant de recenser tous les évènements relatifs à la gestion de la structure.
 */
function bindDataSetStructureEvents(){

    // EVENEMENTS NOEUDS
    removeDataSetChildNodeEvent();
    updateDataSetNodeEvent();
    changeDataSetNodeScopeEvent();

    // EVENEMENTS CHILD NODES
    addDataSetChildNodeEvent();

    // EVENEMENTS FEUILLES
    addDataSetNodeLeafEvent();
    deleteDataSetNodeLeafEvent();
}

function updateDataSetStructureTree(hideCloseNode)
{
    if(typeof hideCloseNode != "undefined" && hideCloseNode == true){
        $(".".concat(classe_close_element_tree)).hide();
    }
}

function bindDataSetStructureTreeEvents()
{
    updateDataSetStructureTree(true);

    $(document).delegate(".".concat(classe_close_element_tree), 'click', function(e) {
        closeBlock($(this));
    });

    $(document).delegate(".".concat(classe_open_element_tree), 'click', function(e) {
        openBlock($(this));
    });
}

/**********     CRUD NODE
 **********************************************************************************************************************/

function removeDataSetChildNodeEvent(){

    $(document).delegate('.delete_datasetstructure_node', 'click', function (event){
        event.preventDefault();

        var element = $(this);
        var link = $(this).attr("data-href");
        var closestChildNode = element.closest(".ei_datastructure_childnode");
        var childNodeId = closestChildNode.attr('ei_datasetstructure_node');
        var modal = element.parentsUntil('.modal').parent();

        $.ajax({
            type: 'GET',
            url: link,
            async: false,
            dataType: 'json',
            success: function(data) {

                if (data.success == true)
                {
                    setToolTip(data.message);

                    modal.modal('toggle');

                    closestChildNode.remove();
                    $("#scenario_structure").find('li[ei_datasetstructure_node=' + childNodeId + ']').remove();
                }

            },
            error: function(data) {
                setToolTip("An error occured. Node could not be deleted due to internal server error.", true);
            }
        });
    });

    $(document).delegate('.delete_datasetstructure_node_new', 'click', function (event){

        event.preventDefault();

        var form = $(this).closest('.new_datasetstructure_childnode');
        form.remove();

    });

}

/**
 * Soumet le formulaire d'édition d'un noeud, contenant le nom et sa description.
 *
 * @returns {undefined}
 */
function updateDataSetNodeEvent() {

    $(document).delegate('.submit_datasetstructure_node', 'click', function(e) {
        e.preventDefault();
        $.ajax({
            url: $('.update_datasetstructure_node').attr('action'),
            type: "POST",
            async: true,
            data: $('.update_datasetstructure_node').serialize(),
            dataType: 'json',
            success: function(data) {
                setToolTip(data.message, data.status === "error");
                if(data.status !== 'error')
                    $(".current_dataset_node").find("a").text($('.update_datasetstructure_node').find("#ei_node_data_set_name").val());
            },
            error: function(e) {
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });
    });
}

/**
 * Méthode permettant d'ajouter un formulaire de création d'un child node.
 *
 * @param element
 */
function addDataSetChildNodeEvent(){

    $(document).delegate(".add_datasetstructure_node", 'click', function(e) {

        e.preventDefault();

        var link = $(this);

        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            dataType: 'json',
            async: false,
            success: function(res) {
                link.parent().after(res.html);
            },
            error: function(res) {
                setToolTip("An error occured. Child node creation form could not be retrieved.", true);
            }
        });
    });

    $(document).delegate(".new_datasetstructure_childnode", "submit", function(event){

        event.preventDefault();

        form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            async: false,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                // Affichage du message d'erreur si formulaire en échec.
                setToolTip(data.message, !data.success);

                if(data.success == true)
                {
                    // 1) On remplace le formulaire par le rendu du bloc.
                    form.replaceWith(data.html);

                    // 2) Mise à jour de l'arbre.
                    //insertion en milieu/fin
                    if ( data.insert_after != false && data.insert_first == false )
                    {
                        $("li[ei_datasetstructure_node=" + data.insert_after + "]").after(data.itemTree);
                        $("li[ei_datasetstructure_node=" + data.insert_after + "]").next().find('.'.concat(classe_close_element_tree)).first().hide();
                    }
                    //insertion au debut
                    else if( data.insert_after == false && data.insert_first != false )
                    {
                        var node = $("li[ei_datasetstructure_node=" + data.insert_first + "]");

                        if (node.children().last().attr('class') === "opened") {
                            node.children().last().prepend(data.itemTree);
                        }
                        else {
                            node.append('<ul class="opened" ></ul>');
                            node.children().last().prepend(data.itemTree);
                        }

                        node.children().last().find('.'.concat(classe_close_element_tree)).first().hide();
                        node.children().first().children('.'.concat(classe_open_element_tree)).first().removeClass('hidden');
                        node.children().first().children('.'.concat(classe_open_element_tree)).first().hide();
                        node.children().first().children('.'.concat(classe_close_element_tree)).first().show();
                    }
                }
                else if(data.success == false)
                {
                    form.replaceWith(data.html);
                }
            },
            error: function(e) {
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });

    });
}

/**
 * Méthode permettant de recenser les événements relatifs à la navigation dans les noeuds.
 */
function changeDataSetNodeScopeEvent()
{
    $(document).delegate(".go_to_block_eidatasetstructure", "click", function(event){
        event.preventDefault();

        // Récupération de l'élément dans une variable locale.
        var link = $(this);
        var closestChildNode = link.closest(".ei_datastructure_childnode").attr('ei_datasetstructure_node');
        var closestLi = link.closest("li").attr('ei_datasetstructure_node');

        if(link.attr('ei_datasetstructure_node') != undefined){
            node_id = link.attr('ei_datasetstructure_node');
        }
        else if( typeof closestLi != "undefined" ){
            node_id = closestLi;
        }
        else if( typeof closestChildNode != "undefined"){
            node_id = closestChildNode;
        }
        else{
            node_id = null;
        }

        $.ajax({
            type: 'GET',
            url: link.attr("href"),
            dataType: 'json',
            async: false,
            success: function(data) {
                $("#block").html(data.html);
//                $('#path_block').html(data.path);

                toggleDataSetStructureNode($('li[ei_datasetstructure_node='+node_id+']').find('.go_to_block_eidatasetstructure').first());
            },
            error: function(res) {
                setToolTip("An error occured. The node could not be retrieved.", true);
            }
        });
    });
}

/**********     CRUD LEAF
 **********************************************************************************************************************/

/**
 * Fonction permettant d'ajouter une feuille au noeud.
 *
 */
function addDataSetNodeLeafEvent(){

    $(document).delegate(".addLeafToNodeButton", "click", function(e) {
        var link = $(this);
        e.preventDefault();

        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            async: false,
            dataType: 'json',
            success: function(res)
            {
                $(".nodeDataSetLeavesFormList tbody.contentForm").append(res.html);
            },
            error: function(res) {
                setToolTip("An error occured. Node's leaf creation form could not be retrieved.", true);
            }
        });
    });

    $(document).delegate(".submit_dataset_node_leaf", "click", function(e){
        e.preventDefault();

        form = $(this).parents("form").first();

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            async: false,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                // Affichage du message d'erreur si formulaire en échec.
                setToolTip(data.message, !data.success);

                if(data.success == true){
                    form.parents("tr").first().replaceWith(data.html);
                }
            },
            error: function(e) {
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });
    });

}

/**
 * Fonction permettant de supprimer une feuille d'un noeud d'un jeu de données.
 */
function deleteDataSetNodeLeafEvent() {

    $(document).delegate(".delete_dataset_node_leaf_old", 'click', function(e) {
        e.preventDefault();

        element = $(this);

        $.ajax({
            type: 'GET',
            url: element.attr('href'),
            async: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === "ok") {
                    setToolTip(res.message);
                    removeDataSetNodeLeafFromDOM(element);
                }
            },
            error: function(res) {
                setToolTip("An error occured. Leaf could not be deleted due to internal server error.", true);
            }
        });
    })

    $(document).delegate(".delete_dataset_node_leaf_new", 'click', function(e) {
        removeDataSetNodeLeafFromDOM($(this));
    });
}

/**
 *
 * @param element
 */
function removeDataSetNodeLeafFromDOM(element){
    element.parents(".addLeafToNodeForm").first().parent().parent().remove();
}

/**
 *
 * @param newNode
 */
function toggleDataSetStructureNode(newNode){
    var icon = $('.current_datasetstructure_node').find('i').last().first();

    $('.current_datasetstructure_node').toggleClass('current_datasetstructure_node');

    icon.remove();
    newNode.parent().toggleClass('current_datasetstructure_node');
    newNode.append(icon);
}