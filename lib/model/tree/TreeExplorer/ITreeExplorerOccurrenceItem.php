<?php

/**
 * Interface ITreeExplorerOccurenceItem
 */
interface ITreeExplorerOccurenceItem
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $index
     * @return mixed
     */
    public function setId($index);

    /**
     * @return ITreeExplorerItem
     */
    public function getReference();

    /**
     * @param ITreeExplorerItem $reference
     * @return mixed
     */
    public function setReference(ITreeExplorerItem $reference);

    /**
     * @return ITreeExplorerOccurenceItem
     */
    public function getParent();

    /**
     * @param ITreeExplorerOccurenceItem $parent
     * @return mixed
     */
    public function setParent(ITreeExplorerOccurenceItem $parent);

    /**
     * @return String
     */
    public function getValue();

    /**
     * Retourne le chemin complet vers l'élément.
     *
     * @return mixed
     */
    public function getPath();

    /**
     * @return mixed
     */
    public function getRepeatIndex();

    /**
     * @return mixed
     */
    public function getLeft();

    /**
     * @return mixed
     */
    public function getRight();

    /**
     * @return mixed
     */
    public function getRightNode();

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @param int $index
     * @return mixed
     */
    public function setRepeatIndex($index);
} 