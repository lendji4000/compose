<?php

/**
 * EiVersionStructure
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiVersionStructure extends BaseEiVersionStructure implements ITestSetBlockParamGenerator
{
    // Constantes permettant d'identifier le type de noeud.
    public static $TYPE_FONCTION = "EiFonction";
    public static $TYPE_BLOCK = "EiBlock";
    public static $TYPE_FOREACH = "EiBlockForeach";
    public static $TYPE_BLOCK_PARAM = "EiBlockParam";

    /**
     * Retourne la liste des blocks de type BOUCLES : Foreach, For, While, DoWhile
     *
     * @return array
     */
    public static function getLoopTypes(){
        return array(self::$TYPE_FOREACH);
    }

    /**
     * Retourne la liste des blocks de type BLOCK : Boucles, Conditionnels & blocks normaux.
     *
     * @return array
     */
    public static function getBlockTypes(){
        return array_merge(array(self::$TYPE_BLOCK), self::getLoopTypes());
    }

    /**
     * Retourne la liste des élements de la structre.
     *
     * @return array
     */
    public static function getStructureTypes(){
        return array_merge(array(self::$TYPE_BLOCK_PARAM), self::getBlockTypes());
    }

    /**
     * Retourne la liste complète des types de blocks.
     *
     * @return array
     */
    public static function getAllTypes(){
        return array_merge(array(self::$TYPE_FONCTION, self::$TYPE_BLOCK_PARAM), self::getBlockTypes());
    }

    /**
     * Retourne la liste des blocks de type BLOCK : Boucles, Conditionnels & blocks normaux.
     *
     * @return array
     */
    public static function getBlockTypesWithQuotes(){
        $types = self::getBlockTypes();

        foreach( $types as $ind=>$type ){
            $types[$ind] = "'".$type."'";
        }

        return $types;
    }

    /**
     * Retourne true si EiVersionStructure est de type EiFonction
     * @return type
     */
    public function isEiFonction() {
        return $this->getType() == self::$TYPE_FONCTION;
    }

    /**
     * Retourne true si EiVersionStructure est de type EiBlock
     * @return type
     */
    public function isEiBlock() {
        return in_array($this->getType(), $this->getBlockTypes());
    }

    /**
     * Retourne true si EiVersionStructure est une boucle : Foreach / While / For.
     *
     * @return bool
     */
    public function isEiLoop(){
        return in_array($this->getType(), $this->getLoopTypes());
    }

    /**
     * Vérifie si le block est compris dans une boucle ou non.
     *
     * @return bool|EiVersionStructure
     */
    public function isInEiLoop(){
        return $this->getTable()->isInEiLoop($this->getEiVersionId(), $this->getLft(), $this->getRgt());
    }

    /**
     * Retourne vrai si EiVersionStructure est de type paramètre.
     *
     * @return bool
     */
    public function isEiBlockParam(){
        return $this->getType() == self::$TYPE_BLOCK_PARAM;
    }

    /**
     * Obtenir le nombre de fonctions appartenant directement au bloc.
     *
     * @return int
     */
    public function getNbFonctions()
    {
        $resultat = 0;

        if( $this->isEiBlock() ){
            $resultat = $this->getTable()->getNbFonctions($this->getId());
        }

        return $resultat;
    }

    /**
     * @param null $type
     * @return EiBlockDataSetMapping
     */
    public function getMappingDataSet($type = null){

        $type = $type == null ? EiBlockDataSetMapping::$TYPE_IN:$type;

        foreach( $this->getEiVersionStructureDataSetMapping() as $mapping ){
            if( $mapping->getType() == $type ){
                return $mapping;
            }
        }

        return null;
    }

    /**
     * @param DOMDocument $xsl
     * @param DOMElement $parentTag
     * @param bool $isFirstChild
     * @param string $rootNodeName
     * @param bool $deep
     * @param null $filteredFunctions
     * @param null $forcedXpath
     */
    public function generateXSLForTestSet(DOMDocument $xsl, $parentTag, $isFirstChild = true, $rootNodeName="", $deep = true, $filteredFunctions = null, $forcedXpath = null){
        //récupération de tous les enfants du noeud, orderBy(lft)
        $eiVersionStructures = Doctrine_Core::getTable('EiVersionStructure')
                ->getEiVersionStructureChildren($this->getId());

        // si l'on affiche le premier bloc, alors on préfixe le xpath
        // de la boucle foreach XSL par le nom de la version racine.
        if (!$this->getNode()->isRoot() && $isFirstChild) {
            $xpath = $rootNodeName . '/' . $this->getName();
            $isFirstChild = false;
        } 
        else {
            $xpath = $isFirstChild ? $rootNodeName . "/" . $this->getName():$this->getName();
        }

        if (!$this->getNode()->isRoot()) {
            $xslForeach = $xsl->createElement("xsl:for-each");
            $xslForeach->setAttribute("select", $xpath);
        } else {
            $xslForeach = $parentTag;
        }

        $str_name =  $this->getName();
        
        if($this->getNode()->isRoot())
            $rootName = "/".$str_name;
        else
            $rootName = null;
        
        //pour chaque noeud, si c'est une fonction, on l'interprete, sinon 
        //sinon on apelle generateXSL sur le eiVersionStructure.
        /** @var EiVersionStructure $str */
        foreach ($eiVersionStructures as $i => $str) {
            switch ($str->getType()) {
                case 'EiFonction':
                    if( $filteredFunctions == null || ($filteredFunctions != null && is_array($filteredFunctions) && in_array($str->getId(), $filteredFunctions)) ){
                        $str->getEiFonction()->generateXSLForTestSet($xsl, $xslForeach, $forcedXpath != null ? $forcedXpath:$rootName, $this->getNode()->isRoot());
                    }
                    break;

                case 'EiBlock':
                    if( $deep == true )
                        $str->generateXSLForTestSet($xsl, $xslForeach, $isFirstChild, $str_name);
                    break;

                case self::$TYPE_FOREACH:
                    if( $deep == true )
                        $str->generateXSLForTestSet($xsl, $xslForeach, $isFirstChild, $str_name);
                    break;
            }
        }

        if (!$this->getNode()->isRoot())
            $parentTag->appendChild($xslForeach);
    }

    /**
     * @param DOMDocument $xml
     * @param $parent_node
     */
    public function generateXML(DOMDocument $xml, $parent_node)
    {
        foreach($this->getEiVersionStructures() as $i => $str){
            $node = $xml->createElement($str->getSlug());
            $parent_node->appendChild($node);

            $str->generateXML($xml, $node);
        }
    }

    /**
     * @param EiTestSet $testSet
     * @param null $parent
     * @param null $index
     * @param string $path
     * @param bool $deep
     * @param Doctrine_Connection $conn
     * @return mixed|void
     */
    public function generateTestSetParameters(EiTestSet $testSet = null, $parent = null, $index = null, $path = "", $deep = true, Doctrine_Connection $conn = null)
    {
        // TODO: Implement generateTestSetParameters() method.
    }

    /**
     * @return Doctrine_Collection
     */
    public function getAllAscendantsParams(){
        /** @var EiBlockParamTable $tableBlockParam */
        $tableBlockParam = Doctrine_Core::getTable("EiBlockParam");

        return $tableBlockParam->getAllAscendantsParams($this->getRootId(), $this->getLevel());
    }

    public function isLast(){
        return $this->getTable()->isLast($this->getEiVersionId(), $this->getRgt(), $this->getLevel());
    }

    public function save(Doctrine_Connection $conn = null)
    {
        $this->setSlug(MyFunction::sluggifyForXML($this->getName()));

        parent::save($conn); // TODO: Change the autogenerated stub
    }
}
