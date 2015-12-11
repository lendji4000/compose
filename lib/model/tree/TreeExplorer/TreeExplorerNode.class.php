<?php

class TreeExplorerNode implements ITreeExplorerItem
{
    /** @var int $id */
    private $id;

    /** @var ITreeExplorerItem $parent */
    private $parent;

    /** @var String $name */
    private $name;

    /** @var String $slug */
    private $slug;

    /** @var String $type */
    private $type;

    /** @var int */
    private $left = 0;

    /** @var int */
    private $right = 0;

    /** @var TreeViewerNode[] $leafs */
    private $leafs;

    /** @var ITreeExplorerOccurenceItem[] */
    private $occurences;

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
        $this->leafs = array();
        $this->occurences = array();
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
     * @return ITreeExplorerItem
     */
    public function getParent()
    {
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
        return strtolower($this->type);
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
     * @param ITreeExplorerItem $parent
     */
    public function setParent(ITreeExplorerItem $parent)
    {
        $this->parent = $parent;
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
    public function addItem(ITreeExplorerItem $item)
    {
        $this->leafs[] = $item;
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