<?php

class ModeSelectTreeStrategy implements ITreeStrategy
{
    /** @var array Tableau contenant les options de paramétrage. */
    private $options = array();

    /**
     * Méthode permettant le paramétrage de l'arbre.
     *
     * @param ITree $tree
     * @param ITreeViewerItem $item
     * @return mixed|void
     */
    public function defaults(ITree $tree, ITreeViewerItem $item)
    {
        $this->options = array(
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => function(ITreeViewerItem $node, $type = "node", $key = null){

                    $id = $key == null ? $node->getId():$key."_".$node->getId();

                    if( $node->isRoot() ){
                        return "<li data-id='".$node->getId()."' data-jstree='{\"type\":\"root\"}' id='".$id."' data-name-format='".$node->getFormNameFormat()."'>";
                    }
                    else{
                        return "<li data-id='".$node->getId()."' data-jstree='{\"type\":\"".$type."\"}' id='".$id."' data-name-format='".$node->getFormNameFormat()."'>";
                    }
                },
            'childClose' => '</li>',
            'nodeDecorator' => function ($node) {
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
                    "icon" => TreeView::$TYPE_VALUE,
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

        if( $key != null && !isset($options["baseId"]) ){
            $options["id"] = $this->options["id"]."_".$key;
            $options["baseId"] = $this->options["id"];
        }
        elseif( $key != null && isset($options["baseId"]) ){
            $options["id"] = $options["baseId"]."_".$key;
        }

        if( $key != null && isset($options["selected"]) ){
            $options["selected"] = isset($options["selected"][$key]) ? $options["selected"][$key]:"";
        }

        $options = array_merge($this->options, $options);

        $build = function($tree) use (&$build, &$options, $key) {
            $output = is_string($options['rootOpen']) ? $options['rootOpen'] : $options['rootOpen']($tree);
            $nodes = $tree instanceof ITreeViewerItem && $tree->isRoot() ? array($tree):$tree;

            /** @var ITreeViewerItem $node */
            foreach ($nodes as $node) {
                $output .= is_string($options['childOpen']) ? $options['childOpen'] : $options['childOpen']($node, $node->getType(), $key);
                $output .= $options['nodeDecorator']($node);

                if( count($node->getChildren()) > 0 ){
                    $output .= $build($node->getChildren());
                }

                $output .= is_string($options['childClose']) ? $options['childClose'] : $options['childClose']($node);
            }
            return $output . (is_string($options['rootClose']) ? $options['rootClose'] : $options['rootClose']($tree));
        };

        return html_entity_decode(sfOutputEscaper::unescape(get_component("eitreeviewer", "displaySelectMode", array(
            "root" => $tree,
            "html" => $build($tree->getRoot()),
            "options" => $options,
            "key" => $key,
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
        use_javascript("treeManagement/jstree/selectMode/selectMode.js");

        use_stylesheet("jstree/themes/default/style.min.css");
        use_stylesheet("jstree/custom/custom.css");
    }
} 