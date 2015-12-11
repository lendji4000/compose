<?php

/**
 * Interface ISluggableXML
 */
interface ISluggableXML {

    /**
     * Retourne le nom de l'objet sous-forme de tag XML.
     *
     * @return mixed
     */
    public function getXMLTag();

    /**
     * @return mixed
     */
    public function getPath();

} 