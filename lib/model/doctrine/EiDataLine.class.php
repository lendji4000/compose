<?php

/**
 * EiDataLine
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiDataLine extends BaseEiDataLine {

    private $dataLines;

    public function __construct($table = null, $isNewEntry = false) {
        parent::__construct($table, $isNewEntry);
        $this->dataLines = new Doctrine_Collection('EiDataLine');
    }

    public function addEiDataLine(EiDataLine $line) {
        $this->dataLines->add($line);
        $line->setEiDataLineParent($this);
    }

    /**
     * @param DOMDocument $xml
     * @param $parentTag
     *
     * @deprecated
     */
    public function generateOldXML(DOMDocument $xml, $parentTag) {
        $children = Doctrine_Core::getTable('EiDataLine')
            ->getChildrenAndEiScenarioStructures($this->getId());

        foreach ($children as $child) {
            $tag = $xml->createElement($child->getEiDataSetStructure()->getSlug());
            $tag->setAttribute("id", $child->getId());

            if ($child->getEiDataSetStructure()->getType() == EiDataSetStructure::$TYPE_NODE) {
                $child->generateOldXML($xml, $tag);
            } else {
                $value = $xml->createTextNode($child->getValeur());
                $tag->appendChild($value);
            }
            $parentTag->appendChild($tag);
        }

        $children->free(true);
    }

    /**
     * @param DOMDocument $xml
     * @param $parentTag
     */
    public function generateXML(DOMDocument $xml, $parentTag) {
        $children = $this->getNode()->getChildren();

        foreach ($children as $child) {
            $tag = $xml->createElement($child->getEiDataSetStructure()->getSlug());

            if ($child->getEiDataSetStructure()->getType() == EiDataSetStructure::$TYPE_NODE) {
                $child->generateXML($xml, $tag);
            } else {
                $value = $xml->createTextNode($child->getValeur());
                $tag->appendChild($value);
            }
            $parentTag->appendChild($tag);
        }

        $children->free(true);
    }
}
