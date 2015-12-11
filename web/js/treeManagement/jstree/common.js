/**
 * Méthode permettant de récupérer l'objet JS TREE depuis le contexte menu.
 *
 * @param contextMenuPlugin
 * @returns {jsTree|null}
 */
function getTreeFromContextMenu(contextMenuPlugin){

    // On récupère l'identifiant de l'arbre depuis le contexte du plugin.
    treeName = $(contextMenuPlugin.get(0).element.context).attr("id");
    // A partir de l'ID, on récupère le JSTREE.
    tree = $.jstree.reference("#"+treeName);

    // Puis, on retoure l'arbre.
    return tree;
}

/**
 *
 * @param contextMenuPlugin
 * @returns {*|jQuery}
 */
function getTreeIdFromContextMenu(contextMenuPlugin){

    // On récupère l'identifiant de l'arbre depuis le contexte du plugin.
    treeName = $(contextMenuPlugin.get(0).element.context).attr("id");

    return treeName;
}

function in_array(val, array){

    var resultat = false;

    for( index in array ){
        if( array[index] == val ){
            resultat = true;
        }
    }

    return resultat;
}