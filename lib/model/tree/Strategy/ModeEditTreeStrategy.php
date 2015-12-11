<?php

/**
 * Class ModeEditTreeStrategy
 * @package Strategy
 */
class ModeEditTreeStrategy implements ITreeStrategy
{
    /** @var array Tableau contenant les options de paramétrage. */
    private $options = array();

    /**
     *
     * @param ITree $tree
     * @param ITreeViewerItem $item
     * @return mixed|void
     */
    public function defaults(ITree $tree, ITreeViewerItem $item = null)
    {
        $this->options = array(
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => function(ITreeViewerItem $node, $type = "node"){
                    if( $node->isRoot() ){
                        return "<li data-id='".$node->getId()."' data-jstree='{\"type\":\"root\"}' id='".$node->getId()."' data-name-format='".$node->getFormNameFormat()."'>";
                    }
                    else{
                        return "<li data-id='".$node->getId()."' data-jstree='{\"type\":\"".$type."\"}' id='".$node->getId()."' data-name-format='".$node->getFormNameFormat()."'>";
                    }
            },
            'childClose' => '</li>',
            'nodeDecorator' => function (ITreeViewerItem $node) {
                return $node->getName();
            },
            "id" => "xmltree",
            "types" => array(
                "root" => array(
                    "icon" => TreeView::$TYPE_XML,
                    "name" => "root"
                ),
                "node" => array(
                    "icon" => TreeView::$TYPE_TAG,
                    "name" => "node"
                ),
                "leaf" => array(
                    "icon" => TreeView::$TYPE_XSL_VALUE,
                    "name" => "attribute"
                )
            )
        );
    }

    /**
     * Méthode permettant d'afficher l'arbre à partir de celui passé en paramètre.
     *
     * @param ITree $tree
     * @param array $options
     * @param $key
     *
     * @return mixed|string
     */
    public function render(ITree $tree, array $options, $key)
    {
        $this->defaults($tree, $tree->getRoot());

        $options = array_merge($this->options, $options);

        $build = function($tree) use (&$build, &$options) {
            $output = is_string($options['rootOpen']) ? $options['rootOpen'] : $options['rootOpen']($tree);
            $nodes = $tree instanceof ITreeViewerItem && $tree->isRoot() ? array($tree):$tree;

            if( $nodes != null ){
                /** @var ITreeViewerItem $node */
                foreach ($nodes as $node) {
                    $output .= is_string($options['childOpen']) ? $options['childOpen'] : $options['childOpen']($node, $node->getType());
                    $output .= $options['nodeDecorator']($node);

                    if( count($node->getChildren()) > 0 ){
                        $output .= $build($node->getChildren());
                    }

                    $output .= is_string($options['childClose']) ? $options['childClose'] : $options['childClose']($node);
                }
            }
            return $output . (is_string($options['rootClose']) ? $options['rootClose'] : $options['rootClose']($tree));
        };

        return html_entity_decode(sfOutputEscaper::unescape(get_component("eitreeviewer", "displayEditMode", array(
            "root" => $tree,
            "html" => $build($tree->getRoot()),
            "options" => $options,
            "tree" => $this
        ))), ENT_QUOTES, "UTF-8");
    }

    /**
     * @return mixed
     */
    public function importAssets()
    {
        use_javascript("jstree/js/jstree.min.js");
        use_javascript("treeManagement/jstree/common.js");
        use_javascript("treeManagement/jstree/editMode/editMode.js");

        use_stylesheet("jstree/themes/default/style.min.css");
        use_stylesheet("jstree/custom/custom.css");
    }
}