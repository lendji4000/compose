<?php

/**
 * Interface ITreeExplorerItem
 */
interface ITreeExplorerItem
{
    /**
     * @param ITreeExplorerItem $item
     * @return mixed
     */
    public function addItem(ITreeExplorerItem $item);

    /**
     * Indique si l'élément est racine.
     *
     * @return bool
     */
    public function isRoot();
    /**
     * Indique si l'élément est un noeud.
     *
     * @return bool
     */
    public function isNode();

    /**
     * Indique si l'élément est une feuille.
     *
     * @return bool
     */
    public function isLeaf();

    /**
     * @return String
     */
    public function getId();

    /**
     * @return ITreeExplorerItem
     */
    public function getParent();

    /**
     * @return String
     */
    public function getParentId();

    /**
     * Retourne le nom de l'élément.
     *
     * @return String
     */
    public function getName();

    /**
     * @return String
     */
    public function getSlug();

    /**
     * Retourne le type l'élément.
     *
     * @return String
     */
    public function getType();

    /**
     * @param int $left
     */
    public function setLeft($left);

    /**
     * @return int
     */
    public function getLeft();

    /**
     * @param int $right
     */
    public function setRight($right);

    /**
     * @return int
     */
    public function getRight();

    /**
     * @return ITreeExplorerItem[]
     */
    public function getChildren();

    /**
     * @return ITreeExplorerOccurenceItem[]
     */
    public function getOccurrences();

    /**
     * @return ITreeExplorerOccurenceItem
     */
    public function getOccurrence($repeat_index = 1);

    /**
     * @param ITreeExplorerOccurenceItem $item
     * @return mixed
     */
    public function addOccurrence(ITreeExplorerOccurenceItem $item);
} 