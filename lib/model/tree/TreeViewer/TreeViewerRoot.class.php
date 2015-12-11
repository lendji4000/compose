<?php

class TreeViewerRoot implements ITreeViewerItem
{
    /** @var int $id */
    private $id;

    /** @var int $parent_id */
    private $parent_id;

    /** @var String $name */
    private $name;

    /** @var string $value */
    private $value;

    /** @var TreeViewerNode[] $nodes */
    private $nodes;

    /**
     * @param $id
     * @param $name
     */
    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = "";
        $this->nodes = array();
    }

    /**
     * Indique si l'élément est racine.
     *
     * @return bool
     */
    public function isRoot()
    {
        return true;
    }

    /**
     * Indique si l'élément est un noeud.
     *
     * @return bool
     */
    public function isNode()
    {
        return false;
    }

    /**
     * Indique si l'élément est une feuille.
     *
     * @return bool
     */
    public function isLeaf()
    {
        return false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Retourne le nom de l'élément.
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retourne le type l'élément.
     *
     * @return String
     */
    public function getType()
    {
        return "root";
    }

    /**
     * @return mixed
     */
    public function getFormNameFormat()
    {
        return "";
    }

    /**
     * @return \TreeViewerNode[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @return ITreeViewerItem[]
     */
    public function getChildren()
    {
        return $this->nodes;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \TreeViewerNode[] $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return mixed
     */
    public function addItem(ITreeViewerItem $item)
    {
        $this->nodes[] = $item;
    }

    /**********          GENERATION XML
    /******************************************************************************************************************/

    public function getXMLTag(){
        return $this->name;//MyFunction::sluggifyForXML($this->name);
    }

    /**
     * @param DOMDocument $documentXml
     * @param DOMDocument $parent
     */
    public function generateXML($documentXml, $parent)
    {
        $element = $documentXml->createElement($this->getXMLTag());
        $parent->appendChild($element);

        // On parcours les fils.
        /** @var TreeViewerNode $child */
        foreach($this->getChildren() as $child){
            // On génère l'élément XSD relatif au noeud fils.
            $child->generateXML($documentXml, $element);
        }
    }
}