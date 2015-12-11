var jsTreesOptions = typeof jsTreesOptions != "undefined" ? jsTreesOptions:{};
var lockRename = typeof lockRename != "undefined" ? lockRename:false;
var lockRemove = typeof lockRemove != "undefined" ? lockRemove:false;
var count = 0;

$(document).ready(function(){

    for( treeID in jsTreesOptions ){

        if( jsTreesOptions[treeID].treeType == "editMode"
            && typeof $("#".concat(treeID)).attr("id") != "undefined"
            && jsTreesOptions[treeID].loaded == false )
        {
            jsTreesOptions[treeID].lockRename = false;
            jsTreesOptions[treeID].lockRemove = false;

            createEditModeJsTreeAndEvents(treeID);
        }
    }
});

function createEditModeJsTreeAndEvents(treeID)
{
    jsTreesOptions[treeID].loaded = true;
    var authorizedPlugins = jsTreesOptions[treeID].authorizedPlugins;

    // Définition des options JSTree
    jsTreeOpt = {
        "core": {
            "check_callback": true
        },
        "state": {"key": jsTreesOptions[treeID].id},
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
        "contextmenu": {
            "items": createTreeContextualMenu
        },
        "plugins": typeof authorizedPlugins != "undefined" ? authorizedPlugins:["html_data", "types", "state", "dnd", "contextmenu", "wholerow"]
    };

    if( typeof jsTreesOptions[treeID].types.attr != "undefined" ){
        jsTreeOpt.types.attr = {
            "valid_children": ["leaf"],
            "icon": jsTreesOptions[treeID].types.attr.icon
        };
    }

    var initTreeOpts = jsTreesOptions[treeID].initTree;

    $(document).unbind("keydown");

    $(document)
        .bind("keydown", function(event){
            var tree = $.jstree.reference($("#".concat(treeID)));
            var selected = tree.get_selected();

            // SI F2.
            if( event.keyCode == 113 && jsTreesOptions[treeID].lockRename == false ){
                jsTreesOptions[treeID].lockRename = true;

                tree.edit(selected);
            }
            else if( event.keyCode == 46 && jsTreesOptions[treeID].lockRemove == false ){
                jsTreesOptions[treeID].lockRemove = true;

                $("#".concat(treeID)).trigger("before_delete.jstree", [tree, selected]);
            }
        })
        .bind("dnd_stop.vakata", function(event, oData){
            var defaultUrl = jsTreesOptions[treeID].actions.dragndrop.url;

            moveTreeNodeElement(event, oData, defaultUrl);
        })
    ;

    $("#".concat(treeID))
        // CONTROLE SUPPRESSION
        .bind("before_delete.jstree", function(event, tree, selectedNode){
            var node = tree.get_node(selectedNode);

            // Si l'action est autorisée.
            return controlActionAuthorization(event, treeID, "remove", node.type, function(){
                eventRemoveNode(treeID, node);
            });
        })
        // CONTROLE RENOMMAGE.
        .bind("dblclick.jstree", function (event) {
            var node = $(event.target).closest("li");
            var data = node.data("jstree");

            return controlActionAuthorization(event, treeID, "rename", data.type);
        })
        .bind("rename_node.jstree", function(obj, text){
            var typeNode = text.node.type;
            var tree = $.jstree.reference($("#".concat(treeID)));

            // Si l'action est autorisée.
            return controlActionAuthorization(obj, treeID, "rename", typeNode, function(){
                eventRenameNode(treeID, text);
            });
        })
        // CONTROLE CREATION D'UN NOEUD.
        .bind("create_node.jstree", function(event, obj){

            var tree = $.jstree.reference($("#".concat(treeID)));
            var parent = tree.get_node(obj.parent);
            var formatForm = obj.node.type == "node" ? nodeFormFormat:leafFormFormat;
            var defaultUrl = jsTreesOptions[treeID].actions.create.url;
            var target = jsTreesOptions[treeID].actions.create.target;
            var positionReelle = 1;

            // On essaye de trouver la position réelle en fonction du type de noeud.
            if( obj.position != 0 ){
                for( ind in parent.children ){
                    subnode = tree.get_node(parent.children[ind]);

                    if( subnode.type == obj.node.type && obj.node.id != subnode.id ){
                        positionReelle++;
                    }

                }
            }

            executeCreateTreeNodeElement(treeID, obj.node, obj.parent, obj.position, positionReelle, obj.node.text, obj.node.type, formatForm, defaultUrl, target);
        })
        .jstree(jsTreeOpt);

        var tree = $.jstree.reference($("#".concat(treeID)));

        if( typeof initTreeOpts != "undefined" && typeof initTreeOpts.openAll != "undefined" ){
            tree.open_all();
        }

        $(document).delegate("#"+treeID+" .jstree-anchor", "dblclick", function(event){
            var tree = $.jstree.reference($("#".concat(treeID)));
            var selected = tree.get_selected();
            jsTreesOptions[treeID].lockRename = true;

            tree.edit(selected);
        });
}

/**
 * Fonction répondant à l'événement de redéfinition du nom du noeud.
 *
 * @param treeID
 * @param text
 */
function eventRenameNode(treeID, text){
    if( text.old != text.text ){
        var tree = $.jstree.reference($("#".concat(treeID)));
        var formatForm = text.node.type == "node" ? nodeFormFormat:leafFormFormat;
        var defaultUrl = jsTreesOptions[treeID].actions.rename.url;
        var target = jsTreesOptions[treeID].actions.rename.target;

        renameTreeNodeElement(treeID, formatForm, defaultUrl, text, target);
    }
}

/**
 * Fonction répondant à l'événement de suppression d'un noeud.
 *
 * @param treeID
 * @param node
 */
function eventRemoveNode(treeID, node){
    var defaultUrl = jsTreesOptions[treeID].actions.delete.url;
    var target = jsTreesOptions[treeID].actions.delete.target;

    removeTreeNodeElement(treeID, node, defaultUrl, target);
}

/**
 *
 * @param node
 * @returns {{create: {label: string, action: boolean, submenu: {createTag: {label: string, action: action}, createValue: {label: string, action: action}}}, delete: {label: string, action: action}, rename: {label: string, action: action}}}
 */
function createTreeContextualMenu(node)
{
    var treeId = getTreeIdFromContextMenu($(this));
    var tree = $.jstree.reference("#".concat(treeId));
    var selected = tree.get_selected();
    var rootItem = "Root".toUpperCase();
    var nodeItem = "Node".toUpperCase();
    var leafItem = "Attribute".toUpperCase();
    console.log(treeId);

    if( typeof jsTreesOptions != "undefined" && typeof jsTreesOptions[treeId] != "undefined" ){
        rootItem = jsTreesOptions[treeId].root.toUpperCase();
        nodeItem = jsTreesOptions[treeId].node.toUpperCase();
        leafItem = jsTreesOptions[treeId].leaf.toUpperCase();
    }

    var items =
    {
        create: {
            label: "New",
            action: false,
            submenu: {// sous menu de création
                createTag: {
                    label: nodeItem,
                    action: function() {
                        createTreeNodeElement(selected, tree, createNameForItem(tree, nodeItem, node), "node");
                    }
                },
                createValue: {
                    label: leafItem,
                    action: function() {
                        createTreeNodeElement(selected, tree, createNameForItem(tree, leafItem, node), "leaf");
                    }
                }
            }
        },
        delete: {
            label: "Delete",
            action: function() {
                $("#".concat(treeId)).trigger("before_delete.jstree", [tree, selected]);
            }
        },
        rename: {
            label: "Rename",
            action: function() {
                tree.edit(selected);
            }
        }
    };

    switch ( node.type ) {
        case "root":
            delete items.delete;
            break;
        case "leaf":
            delete items.create;
            break;
    }

    return items;
}

/**
 *
 * @param treeID
 * @param formatName
 * @param defaultUrl
 * @param text
 * @param target
 */
function renameTreeNodeElement(treeID, formatName, defaultUrl, text, target)
{
    // Récupération de l'arbre.
    var tree = $.jstree.reference($("#".concat(treeID)));
    var treeDom = $(treeID);
    // Sérialisation des données à envoyer.
    var dataForm = {};
    dataForm[formatName.replace("%s", "name")] = text.text;
    dataForm[formatName.replace("%s", "id")] = text.node.id;
    dataForm["sf_method"] = "put";

    if( text.old != text.text )
    {
        blockTree(treeDom);

        // Envoi de la requête AJAX.
        $.ajax({
            url: defaultUrl.replace(target, text.node.id),
            type: "POST",
            async: true,
            data: dataForm,
            success: function(data) {

                if (data.success == true)
                {
                    afficherMessageResultat(treeID, data, data.success);
                }
                else{
                    afficherMessageResultat(treeID, data, data.success);
                    // On restaure l'ancien nom du noeud.
                    tree.set_text(tree.get_node(text.node.id), text.old);
                }

                jsTreesOptions[treeID].lockRename = false;
                unblockTree(treeDom);

            },
            error: function(e) {

                afficherMessageResultat(treeID, {title: "Error !", message: "Unable to process the save: an internal error occured."}, false);

                // On restaure l'ancien nom du noeud.
                tree.set_text(tree.get_node(text.node.id), text.old);

                jsTreesOptions[treeID].lockRename = false;
                unblockTree(treeDom);
            }
        });
    }
    else{
        jsTreesOptions[treeID].lockRename = false;
    }
}

/**
 * Créer un node dans un arbre
 *
 * @param {type} node le noeud créer
 * @param {type} tree l'arbre dans lequel le faire
 * @param {type} text le text par defaut à afficher
 * @param {type} type le type du noeud
 * @returns {undefined}
 */
function createTreeNodeElement(node, tree, text, type) {

    var children = tree.get_children_dom(node);
    var position = 0;
    var index = 0;
    var firstType = false;
    var childType;

    if( children != false ){

        for( index = 0; index < children.length; index++ ){

            childType = tree.get_type(children[index]);

            if( firstType == false ){
                firstType = childType;
            }

            if( type != childType && position == 0 && firstType == type ){
                position = index;
            }
            else if( type == childType && firstType != type ){
                position = index + 1;
            }

        }
    }

    if( position == 0 ){
        position = "last";
    }

    var node_id = tree.create_node(node, {
        'text': text,
        'type': type
    }, position);

    tree.edit(node_id);
    tree.open_node(node);
}

/**
 *
 * @param event
 * @param oData
 * @param urlToPost
 */
function moveTreeNodeElement (event, oData, urlToPost)
{
    console.log(oData.data, oData.data.obj.context.id);

    // On récupère les noeuds.
    var noeuds = oData.data.nodes;
    var noeud, arbre, noeudParent, noeudP, noeudS;
    var oNoeuds = [];
    var oNoeud = {};
    var treeID = oData.data.obj.context.id;
    var treeDom = $("#".concat(treeID));

    // Récupération arbre.
    arbre = $.jstree.reference("#".concat(treeID));

    console.log("Arbre", arbre);

    try
    {
        // On parcours les noeuds afin de déterminer les différents déplacements.
        for( n in noeuds )
        {
            // Noeud courant.
            noeud = noeuds[n];
            oNoeud = {};

            // Récupération du contexte du noeud.
            noeudParent = arbre.get_parent(noeud);
            arbre.open_node(noeudParent);

            noeudP = arbre.get_prev_dom(noeud, true);
            console.log(noeudP);
            noeudP = noeudP != false ? noeudP.attr("id"):null;

            noeudS = arbre.get_next_dom(noeud, true);
            console.log(noeudS);
            noeudS = noeudS != false ? noeudS.attr("id"):null;

            console.log("Noeud : ", noeud, "Noeud Parent : ", noeudParent, "Noeud Précédent : ", noeudP, "Noeud Suivant : ", noeudS);

            oNoeud.id = noeud;
            oNoeud.parent = noeudParent;
            oNoeud.prev = noeudP;
            oNoeud.suiv = noeudS;

            oNoeuds[n] = oNoeud;
        }

        blockTree(treeDom);

        // Exécution de la requête AJAX permettant de supprimer un noeud.
        $.ajax({
            type: "post",
            url: urlToPost,
            data: JSON.stringify({ "noeuds": oNoeuds }),
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function(data, textStatus, jqXHR){
                afficherMessageResultat(treeID, data, true);

                unblockTree(treeDom);
            },
            error: function(error){
                afficherMessageResultat(treeID, data, false);

                unblockTree(treeDom);
            }
        });
    }
    catch (erreur){
        console.log("Erreur", erreur);

        unblockTree(treeDom);
    }
}

/**
*
* @param treeID
* @param node
* @param defaultUrl
* @param target
*/
function removeTreeNodeElement(treeID, node, defaultUrl, target)
{
    var tree = $.jstree.reference($("#".concat(treeID)));
    var idTree = tree.element.context.id;

    var rootItem = "Root";
    var nodeItem = "Node";
    var leafItem = "Attribute";

    if( typeof jsTreesOptions != "undefined" && typeof jsTreesOptions[treeID] != "undefined" ){
        rootItem = ucwords(jsTreesOptions[treeID].root);
        nodeItem = ucwords(jsTreesOptions[treeID].node);
        leafItem = ucwords(jsTreesOptions[treeID].leaf);
    }

    var modal = $("#remove_node_"+idTree+"_modal");
    var modalDefault = modal.clone();

    modal.on("hidden", function(){
        jsTreesOptions[treeID].lockRemove = false;
    });

    modal.html(modal.html().replace(new RegExp("#{nodeName}", 'g'), node.text).replace(new RegExp("#{nodeType}", 'g'), leafItem));

    $("#btn_remove_node_".concat(idTree)).click(function(event){
        event.preventDefault();

        executeRemoveTreeNodeElement(treeID, node, defaultUrl, target);
        modal.modal("hide");
        modal.html(modalDefault.html());

        jsTreesOptions[treeID].lockRemove = false;

    });

    $("#btn_cancel_remove_node_".concat(idTree)).click(function(event){
        event.preventDefault();

        modal.modal("hide");
        modal.html(modalDefault.html());

        jsTreesOptions[treeID].lockRemove = false;
    });

    modal.modal("show");
}

/**
 *
 * @param treeID
 * @param node
 * @param defaultUrl
 * @param target
 */
function executeRemoveTreeNodeElement(treeID, node, defaultUrl, target)
{
    // Récupération de l'arbre.
    var tree = $.jstree.reference($("#".concat(treeID)));
    var treeDom = $(treeID);

    blockTree(treeDom);

    // Envoi de la requête AJAX.
    $.ajax({
        url: defaultUrl.replace(target, node.id),
        type: "POST",
        async: true,
        success: function(data) {

            if (data.success == true)
            {
                afficherMessageResultat(treeID, data, data.success);

                tree.delete_node(node);
            }
            else{
                afficherMessageResultat(treeID, data, data.success);
            }

            unblockTree(treeDom);
        },
        error: function(e) {

            afficherMessageResultat(treeID, {title: "Error !", message: "Unable to process the save: an internal error occured."}, false);

            unblockTree(treeDom);
        }
    });
}

/**
 * Fonction permettant de générer un nouveau nom pour un noeud ou une feuille en respectant l'unicité du nom dans la
 * branche.
 *
 * @param tree
 * @param type
 * @param parent
 * @param index
 * @returns {*}
 */
function createNameForItem(tree, type, parent, index)
{

    var children = tree.get_children_dom(parent);
    var suffixe = typeof index == "undefined" ? "":" ".concat(index);
    var prefixe = "new ";
    var motif = prefixe.concat(type).concat(suffixe);
    var count = 0;
    var child = null;

    for( var i = 0; i < parent.children.length; i++ ){
        child = tree.get_node(parent.children[i]);

        if( motif == child.text ){
            return createNameForItem(tree, type, parent, typeof index != "undefined" ? index + 1:1);
        }
    }

    return motif;
}

function executeCreateTreeNodeElement(treeID, node, parent, position, positionReelle, text, type, formatName, defaultUrl, target){

    // Récupération de l'arbre.
    var tree = $.jstree.reference($("#".concat(treeID)));
    var treeDom = $(treeID);
    // Sérialisation des données à envoyer.
    var dataForm = {};
    dataForm[formatName.replace("%s", "name")] = text;
    dataForm["sf_method"] = "post";
    dataForm["position"] = position;
    dataForm["positionRelle"] = positionReelle;
    dataForm["type"] = type;

    blockTree(treeDom);

    // Envoi de la requête AJAX.
    $.ajax({
        url: defaultUrl.replace(target, parent),
        type: "POST",
        async: true,
        data: dataForm,
        success: function(data) {

            console.log("Success Create Tree Node Element", data, node);

            if (data.success == true)
            {
                afficherMessageResultat(treeID, data, data.success);

                tree.set_id(node, data.id)
                $("#".concat(data.id)).attr("data-id", data.id);
            }
            else{
                afficherMessageResultat(treeID, data, data.success);

                $("#".concat(node.id)).addClass("error");
            }

            unblockTree(treeDom);
        },
        error: function(e) {

            afficherMessageResultat(treeID, {title: "Error !", message: "Unable to process the save: an internal error occured."}, false);

            unblockTree(treeDom);
        }
    });
}

/**
 * Fonction permettant de déterminer si une action est authorisée sur un certain type de noeud.
 *
 * @param treeID
 * @param action
 * @param nodeType
 */
function isActionAuthorized(treeID, action, nodeType)
{
    var authorization = jsTreesOptions[treeID].authorizations[action];
    var isAuthorized = true;

    // Si action désactivée...
    if( typeof authorization != "undefined" && !in_array(nodeType, authorization) ){
        isAuthorized = false;
    }

    return isAuthorized;
}

/**
 * Méthode permettant d'arrêter la propagation d'un événement si l'action n'est pas authorisée.
 *
 * @param event
 * @param treeID
 * @param action
 * @param nodeType
 * @param callback
 * @param rollback
 * @returns {*}
 */
function controlActionAuthorization(event, treeID, action, nodeType, callback, rollback){
    var authorized = isActionAuthorized(treeID, action, nodeType);

    if( authorized == false ){
        event.stopImmediatePropagation();

        if( typeof rollback != "undefined" ) rollback();
    }
    else{
        if( typeof callback != "undefined" ) callback();
    }

    return authorized;
}

/**
 *
 * @param treeID
 * @param data
 * @param isSuccess
 */
function afficherMessageResultat(treeID, data, isSuccess){
    var style = jsTreesOptions[treeID].styleMessageResultat;

    if( typeof style != "undefined" && style == 2 ){
        console.log("CAS MESSAGE 2", data.title, data.message, !isSuccess, $("#"+jsTreesOptions[treeID].styleMessageObject));
        insertFormMessage(data.title, data.message, !isSuccess, $(jsTreesOptions[treeID].styleMessageResultatObject), 2000);
    }
    else{
        setToolTip(data.message, !isSuccess);
    }

}

function ucwords(str) {
    //  discuss at: http://phpjs.org/functions/ucwords/
    // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // improved by: Waldo Malqui Silva
    // improved by: Robin
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    //    input by: James (http://www.james-bell.co.uk/)
    //   example 1: ucwords('kevin van  zonneveld');
    //   returns 1: 'Kevin Van  Zonneveld'
    //   example 2: ucwords('HELLO WORLD');
    //   returns 2: 'HELLO WORLD'

    return (str + '')
        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
        });
}

function blockTree(tree){
    tree.block({
        message: '<h1>Processing</h1>',
        css: { border: '3px solid #a00' }
    });
}

function unblockTree(tree){
    tree.unblock();
}