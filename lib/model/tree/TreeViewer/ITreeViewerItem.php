<?php

/**
 * Interface ITreeViewerItem
 */
interface ITreeViewerItem
{
    /**
     * @param ITreeViewerItem $item
     * @return mixed
     */
    public function addItem(ITreeViewerItem $item);

    /**
     * @return mixed
     */
    public function getFormNameFormat();

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
     * Retourne le type l'élément.
     *
     * @return String
     */
    public function getType();

    /**
     * @return ITreeViewerItem[]
     */
    public function getChildren();

    /**
     * @return mixed
     */
    public function getXMLTag();

    /**
     * @param DOMDocument $documentXml
     * @param DOMDocument $parent
     */
    public function generateXML($documentXml, $parent);
} 