<?php

class TreeViewerNode implements ITreeViewerItem
{
    /** @var int $id */
    private $id;

    /** @var int $parent_id */
    private $parent_id;

    /** @var String $name */
    private $name;

    private $value;

    /** @var String $type */
    private $type;

    /** @var TreeViewerNode[] $leafs */
    private $leafs;

    /**
     * @param $id
     * @param $name
     * @param $type
     */
    function __construct($id, $name, $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->value = "";
        $this->leafs = array();
    }

    /**
     * Indique si l'élément est racine.
     *
     * @return bool
     */
    public function isRoot()
    {
        return false;
    }

    /**
     * Indique si l'élément est un noeud.
     *
     * @return bool
     */
    public function isNode()
    {
        return $this->type == "node" ? true:false;
    }

    /**
     * Indique si l'élément est une feuille.
     *
     * @return bool
     */
    public function isLeaf()
    {
        return $this->type == "leaf" ? true:false;
    }

    /**
     * @return String
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
     * Retourne le type l'élément.
     *
     * @return String
     */
    public function getType()
    {
        return strtolower($this->type);
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
     * @return mixed
     */
    public function getFormNameFormat()
    {
        return "";
    }

    /**
     * @return ITreeViewerItem[]
     */
    public function getChildren()
    {
        return $this->leafs;
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
     * @param \TreeViewerNode[] $leafs
     */
    public function setLeafs($leafs)
    {
        $this->leafs = $leafs;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function addItem(ITreeViewerItem $item)
    {
        $this->leafs[] = $item;
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
        if( $this->isNode() ){
            $element = $documentXml->createElement($this->getXMLTag());

            /** @var TreeViewerNode $leaf */
            foreach( $this->getChildren() as $leaf){
                if( $leaf->isLeaf()){
                    $leaf->generateXML($documentXml, $element);
                }
            }

            $parent->appendChild($element);

            // On parcours les fils.
            /** @var EiNodeDataSet $child */
            foreach($this->getChildren() as $child){
                // On génère l'élément XSD relatif au noeud fils.
                if( $child->isNode() ){
                    $child->generateXML($documentXml, $element);
                }
            }
        }
        elseif( $this->isLeaf() ){
            $element = $documentXml->createElement($this->getXMLTag(), MyFunction::xml_entities($this->value));
            $parent->appendChild($element);
        }
    }
}