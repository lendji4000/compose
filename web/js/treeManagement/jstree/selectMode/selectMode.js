var jsTreesOptions = typeof jsTreesOptions != "undefined" ? jsTreesOptions:{};
var oldSelected = typeof oldSelected != "undefined" ? oldSelected:[];

$(document).ready(function(){
    $(document).delegate(".btn-select-param-mapping", "click", openDataSetWindowSelector);
});

function createSelectModeJsTreeAndEvents(treeID)
{
    oldSelected[treeID]= [];

    $("#".concat(treeID))
        .bind("select_node.jstree", function(event, obj){
            var tree = $.jstree.reference("#".concat(treeID));
            var node = tree.get_node(obj.node.id);
            var isLeaf = tree.is_leaf(node);


            if( isLeaf == false || in_array(obj.node.id, oldSelected[treeID]) ){
                tree.deselect_node(node);
            }
            else if( isLeaf == false && obj.selected.length > 1 ){
                for( i = 0; i < (obj.selected.length - 1); i++ ){
                    node = tree.get_node(obj.selected[i]);
                    tree.deselect_node(node);
                }
            }
            else if( obj.selected.length > 1 && isLeaf == true ){
                tree.deselect_all();
                tree.select_node(node);
            }

            oldSelected[treeID] = obj.selected;
        })
        .jstree({
            "core": {
                "check_callback": true
            },
            "types": {
                "#": {
                    "valid_children": ["root"],
                    "icon": ""
                },
                "root": {
                    "valid_children": ["node", "leaf"],
                    "icon": jsTreesOptions[treeID].types.root.icon
                },
                "node": {
                    "valid_children": ["leaf", "node"],
                    "icon": jsTreesOptions[treeID].types.node.icon
                },
                "leaf": {
                    "valid_children": [],
                    "icon": jsTreesOptions[treeID].types.leaf.icon
                }
            },
            "plugins": ["html_data", "types", "wholerow"]
        });

    $.jstree.reference("#".concat(treeID)).open_all();

    $(".jstree-anchor").dblclick(function(event){
        var treeID = $(this).parents(".jstree").first().attr("id");
        var tree = $.jstree.reference("#".concat(treeID));
        var nodeSelected = tree.get_node($(this).closest("li").attr("id"));

        if( tree.is_leaf(nodeSelected) ){
            tree.deselect_all();
            oldSelected[treeID] = [];
            tree.select_node(nodeSelected);

            $(this).closest(".modal").find(".btn-confirm-choice").click();
        }

    });

    if( typeof jsTreesOptions[treeID].selected != "undefined" && jsTreesOptions[treeID].selected.match(/[0-9]+/) ){
        var tree = $.jstree.reference("#".concat(treeID));
        var selected_node = treeID.replace(jsTreesOptions[treeID].baseID, "").concat("_").concat(jsTreesOptions[treeID].selected);

        tree.deselect_all();
        tree.select_node(tree.get_node(selected_node));
    }
}

/**
 * Fonction permettant de déclencher l'apparition de la fenêtre de sélection d'un attribut du jeu de données.
 *
 * @param event
 */
function openDataSetWindowSelector(event){
    event.preventDefault();

    // Déclaration de la fenêtre modale.
    var modal = $(this).next(".modal");
    // On clone la fenêtre afin de la réinitialiser plus tard.
    var modalDefault = modal.clone();
    var urlToPost = $(this).attr("href");

    // Changement du titre de la fenêtre.
    modal.html(modal.html().replace("#{name}", $(this).attr("data-name")));

    createSelectModeJsTreeAndEvents(modal.attr("data-tree-id"));

    // Déclaration des événements en cas d'annulation ou de confirmation.
    modal.find(".btn-cancel").click(function(event){
        event.preventDefault();

        modal.modal("hide");
        modal.html(modalDefault.html());
    });

    modal.find(".btn-confirm-choice").click(function(event){
        event.preventDefault();

        var treeID = modal.find("div.jstree").attr("id");
        var tree = $.jstree.reference("#".concat(treeID));
        var selected = tree.get_selected();
        var node = selected.length == 0 ? null:tree.get_node(selected);

        if( typeof selected != "undefined"){
            selected = selected.length == 0 ? null:selected[0];

            modal.block();

            jsTreesOptions[treeID]["selected"] = node != null ? "".concat(node.data.id):"";

            // Envoi de la requête AJAX afin de récupérer l'arbre.
            $.ajax({
                url: jsTreesOptions[treeID].actions.doSelect.url,
                type: "POST",
                async: true,
                data: JSON.stringify({ "node": node != null ? node.data.id:null }),
                dataType: "json",
                success: function(data)
                {
                    if (data.success == true)
                    {
                        setToolTip(data.message);

                        modal.parent().find(".mapping-name-slot").text(data.path);
                    }

                    modal.unblock();
                    modal.modal("hide");
                    modal.html(modalDefault.html());
                },
                error: function(e) {

                    setToolTip("Unable to process the tree load: an internal error occured.", true);

                    modal.unblock();
                    modal.modal("hide");
                    modal.html(modalDefault.html());
                }
            });
        }
    });

    modal.modal("show");

//    $("body").block();

    // Envoi de la requête AJAX afin de récupérer l'arbre.
//    $.ajax({
//        url: $(this).attr("href"),
//        type: "GET",
//        async: true,
//        success: function(data)
//        {
//            if (data.success == true)
//            {
//                modal.find(".modal-body").html(data.html);
//
//                // Affichage de la fenêtre.
//                modal.modal("show");
//            }
//
//            $("body").unblock();
//        },
//        error: function(e) {
//
//            setToolTip("Unable to process the tree load: an internal error occured.", true);
//
//            $("body").unblock();
//        }
//    });
}