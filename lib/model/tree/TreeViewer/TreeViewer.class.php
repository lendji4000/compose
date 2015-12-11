<?php

/**
 * Class TreeViewer
 */
class TreeViewer implements ITree {

    /** @var TreeViewerRoot $root */
    private $root;

    /** @var string $table */
    private $table;

    /**
     * @param $formNameFormat
     */
    function __construct($table = "", $formNameFormat = "")
    {
        $this->table = $table;
        $this->formNameFormat = $formNameFormat;
    }

    /**
     * @return string
     */
    public function getTable(){
        return $this->table;
    }

    /**
     * @return string
     */
    public static function getFormNameFormat()
    {
        return "";
    }

    /**
     * @return ITreeViewerItem
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
     * @param ITreeViewerItem $parent
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
        // Récupération du type.
        $type = $element["type"];
        // Création de l'élément.
        $elt = $type == "root" ? new TreeViewerRoot($element["id"], $element["name"]):new TreeViewerNode($element["id"], $element["name"], $element["type"]);

        $elt->setId($element["id"]);
        $elt->setName($element["name"]);
        $elt->setParentId($element["parent_id"]);
        $elt->setValue( isset($element["value"]) ? $element["value"]:"" );

        return $elt;
    }

    public function generateXML()
    {
        // Création du document.
        $document = new DOMDocument("1.0", "utf-8");
        $document->formatOutput = true;

        $this->root->generateXML($document, $document);

        return $document->saveXML();
    }
}

?>