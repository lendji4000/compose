<?php

/**
 * Class TreeExplorer
 */
class TreeExplorer {

    /** @var TreeExplorerRoot $root */
    private $root;

    /** @var ITreeExplorerItem[] $created */
    private $created;

    /**
     *
     */
    function __construct()
    {

    }

    /**
     * @return TreeExplorerRoot
     */
    public function getRoot()
    {
        return $this->root;
    }


    /**
     * @param array $structure
     */
    public function import(array $structure = array())
    {
        // On récupère le root et on le retire de la structure.
        $this->root = $this->createElementFromArray($structure[0]);

        array_shift($structure);

        $this->treatImport($this->root, $structure);
    }

    /**
     * @param ITreeExplorerItem $parent
     * @param $childs
     */
    private function treatImport(&$parent, $childs)
    {
        if( !$parent->isLeaf() )
        {
            foreach( $childs as $child )
            {
                $elt = $this->createElementFromArray($child);

                if( $parent->getId() == $elt->getParentId() ){
                    $parent->addItem($elt);

                    $this->treatImport($elt, $childs);
                }
            }
        }
    }

    /**
     * @param array $element
     */
    private function createElementFromArray(array $element)
    {
        if( !isset($this->created[$element["id"]]) ){
            // Récupération du type.
            $type = $element["type"];
            // Création de l'élément.
            $elt = $type == "root" ? new TreeExplorerRoot($element["id"], $element["name"]):new TreeExplorerNode($element["id"], $element["name"], $element["type"]);

            $elt->setId($element["id"]);
            $elt->setName($element["name"]);
            $elt->setSlug($element["slug"]);
            $elt->setLeft($element["lft"]);
            $elt->setRight($element["rgt"]);

            if( isset($this->created[$element["parent_id"]]) ){
                $elt->setParent($this->created[$element["parent_id"]]);
            }

            $this->created[$element["id"]] = $elt;
        }
        else{
            $elt = $this->created[$element["id"]];
        }

        return $elt;
    }
}

?>