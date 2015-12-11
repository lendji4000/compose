<?php

class TreeExplorerRoot implements ITreeExplorerItem
{
    /** @var int $id */
    private $id;

    /** @var ITreeExplorerItem $parent */
    private $parent;

    /** @var String $name */
    private $name;

    /** @var String $slug */
    private $slug;

    /** @var String $value */
    private $value;

    /** @var int */
    private $left = 0;

    /** @var int */
    private $right = 0;

    /** @var int $repeat_index */
    private $repeat_index = 0;

    /** @var ITreeExplorerOccurenceItem[] */
    private $occurences;

    /** @var TreeExplorerNode[] $nodes */
    private $nodes;

    /**
     * @param $id
     * @param $name
     * @param int $repeat_index
     */
    function __construct($id, $name, $repeat_index = 1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->repeat_index = $repeat_index;
        $this->nodes = array();
        $this->occurences = array();
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
     * @return ITreeExplorerItem
     */
    public function getParent(){
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent->getId();
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
     * @param String $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return String
     */
    public function getSlug()
    {
        return $this->slug;
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
     * @param int $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param int $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->right;
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
     * @param ITreeExplorerItem $parent
     */
    public function setParent(ITreeExplorerItem $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \TreeExplorerNode[] $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return mixed
     */
    public function addItem(ITreeExplorerItem $item)
    {
        $this->nodes[] = $item;
    }

    /**
     * @return ITreeExplorerOccurenceItem[]
     */
    public function getOccurrences()
    {
        return $this->occurences;
    }

    /**
     * @return ITreeExplorerOccurenceItem
     */
    public function getOccurrence($repeat_index = 1)
    {
        return isset($this->occurences[$repeat_index]) ? $this->occurences[$repeat_index] : array();
    }

    /**
     * @param ITreeExplorerOccurenceItem $item
     * @return mixed
     */
    public function addOccurrence(ITreeExplorerOccurenceItem $item)
    {
        $this->occurences[] = $item;

        return $this;
    }
}