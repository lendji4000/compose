var jsTreesOptions = typeof jsTreesOptions != "undefined" ? jsTreesOptions:{};
var oldSelected = typeof oldSelected != "undefined" ? oldSelected:[];
var lastSelected = typeof lastSelected != "undefined" ? lastSelected:[];

$(document).ready(function(){
    for( treeID in jsTreesOptions ){

        if( jsTreesOptions[treeID].treeType == "selectInHiddenInput"
            && typeof $("#".concat(treeID)).attr("id") != "undefined"
            && jsTreesOptions[treeID].loaded == false ){

            oldSelected[treeID] = [];
            createSelectInHiddenInputJsTreeAndEvents(treeID);
        }
    }
});

function createSelectInHiddenInputJsTreeAndEvents(treeID)
{
    oldSelected = [];
    lastSelected[treeID] = undefined;
    jsTreesOptions[treeID].loaded = true;

    $("#".concat(treeID))
        .bind("select_node.jstree", function(event, obj){
            var tree = $.jstree.reference("#".concat(treeID));
            var node = tree.get_node(obj.node.id);
            var nodeType = tree.get_type(node);
            var isNode = nodeType == "node";//tree.is_leaf(node);

            lastSelected[treeID] = typeof oldSelected[treeID] != "undefined" && typeof oldSelected[treeID][0] != "undefined" ? oldSelected[treeID][0]:undefined;

//            console.log(oldSelected, lastSelected, obj.node.id);

            if( isNode == false || in_array(obj.node.id, oldSelected[treeID]) ){
//                console.log("CAS 1");
                oldSelected[treeID] = [];
                tree.deselect_node(node);

                if( lastSelected[treeID] != node.id ){
                    tree.select_node(tree.get_node(lastSelected[treeID]));
                }
            }
            else if( isNode == false && obj.selected.length > 1 ){
//                console.log("CAS 2");
                for( i = 0; i < (obj.selected.length - 1); i++ ){
                    node = tree.get_node(obj.selected[i]);
                    tree.deselect_node(node);
                }
            }
            else if( obj.selected.length > 1 && isNode == true ){
//                console.log("CAS 3", jsTreesOptions[treeID].target, obj.node.id);
                tree.deselect_all();
                tree.select_node(node);

                $("#".concat(treeID)).closest("form").find("#".concat(jsTreesOptions[treeID].target)).val( obj.node.id );
            }
            else if( isNode == true ){
//                console.log("CAS 4", jsTreesOptions[treeID].target, obj.node.id);
                tree.select_node(node);

                $("#".concat(treeID)).closest("form").find("#".concat(jsTreesOptions[treeID].target)).val( obj.node.id );
            }

            oldSelected[treeID] = obj.selected;
        })
        .bind("deselect_node.jstree", function(event, obj){
            var tree = $.jstree.reference("#".concat(treeID));
            var node = tree.get_node(obj.node.id);

            if( lastSelected[treeID] == obj.node.id ){
                $("#".concat(jsTreesOptions[treeID].target)).val("");
            }
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
            oldSelected = [];
            tree.select_node(nodeSelected);

            $(this).closest(".modal").find(".btn-confirm-choice").click();
        }

    });

    if( typeof jsTreesOptions[treeID].selected != "undefined" && jsTreesOptions[treeID].selected.match(/[0-9]+/) ){
        var tree = $.jstree.reference("#".concat(treeID));
        var selected_node = jsTreesOptions[treeID].selected;

        if( typeof jsTreesOptions[treeID].baseID != "undefined" ){
            selected_node = treeID.replace(jsTreesOptions[treeID].baseID, "").concat("_").concat(selected_node);
        }

        tree.deselect_all();
        tree.select_node(tree.get_node(selected_node));
    }
}