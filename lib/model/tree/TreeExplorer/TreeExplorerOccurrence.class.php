<?php

class TreeExplorerOccurrence implements ITreeExplorerOccurenceItem
{
    /** @var int $id */
    private $id;

    /** @var ITreeExplorerItem $reference */
    private $reference;

    /** @var ITreeExplorerOccurenceItem $parent */
    private $parent;

    /** @var String $value */
    private $value;

    /** @var int $repeat_index */
    private $repeat_index = 0;

    /** @var int */
    private $left = 0;

    /** @var int */
    private $right = 0;

    /** @var int */
    private $rightNode = 0;

    /** @var int */
    private $level = 0;

    /**
     * @param $value
     * @param int $repeat_index
     */
    function __construct($value, $repeat_index = 1)
    {
        $this->value = $value;
        $this->repeat_index = $repeat_index;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $index
     */
    public function setId($index)
    {
        $this->id = $index;
    }

    /**
     * @return ITreeExplorerItem
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param ITreeExplorerItem $reference
     * @return mixed
     */
    public function setReference(ITreeExplorerItem $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return ITreeExplorerOccurenceItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ITreeExplorerOccurenceItem $parent
     */
    public function setParent(ITreeExplorerOccurenceItem $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param String $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retourne le chemin complet vers l'élément.
     *
     * @return mixed
     */
    public function getPath()
    {
        return ($this->parent != null ? $this->parent->getPath():"") . "/" . $this->reference->getSlug() . "[".$this->repeat_index."]";
    }

    /**
     * @return mixed
     */
    public function getRepeatIndex()
    {
        return $this->repeat_index;
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function setRepeatIndex($index)
    {
        $this->repeat_index = $index;
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
     * @param int $rightNode
     */
    public function setRightNode($rightNode)
    {
        $this->rightNode = $rightNode;
    }

    /**
     * @return int
     */
    public function getRightNode()
    {
        return $this->rightNode;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}