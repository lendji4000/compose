<?php

/**
 * EiTestSetBlockParam
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiTestSetBlockParam extends BaseEiTestSetBlockParam
{

    /** @var sfLogger */
    private $logger;

    /**
     * @return array
     */
    public function getAllParentsId(){
        $parents = array();
        /** @var EiTestSetBlockParam $element */
        $element = $this;

        /** @var EiTestSetBlockParam $parent */
        while( !is_bool($parent = $element->getNode()->getParent()) ){
            $parents[] = $parent->getId();
            $element = $parent;
        }

        return $parents;
    }

    /**
     * ADD block to stack.
     *
     * @param EiTestSetBlockStack $parentStack
     * @return EiTestSetBlockStack
     */
    public function addToStack(EiTestSetBlockStack $parentStack = null){

        $dataSetStr = null;
        $position = 1;

        if( $parentStack != null ){
            $position = $parentStack->getPosition() + 1;

            $dataSetStr = $this->getTable()->getDataFromDataSet($this, $parentStack);
        }
        else{
            $dataSetStr = $this->getTable()->getDataFromDataSet($this);
        }

        $stack = new EiTestSetBlockStack();
        $stack->setEiTestSet($this->getEiTestSet());
        $stack->setEiVersionStructure($this->getEiVersionStructure());
        $stack->setEiTestSetBlockParam($this);
        $stack->setEiTestSetDataSet($dataSetStr);
        $stack->setPath($this->getPath());
        $stack->setPosition($position);

        $stack->save();

        return $stack;
    }

    public function getBlockContext()
    {
        /** @var Doctrine_Node_NestedSet $node */
        $node = $this->getNode();
        // Définition du contexte initial.
        $contexte = "";
        /** @var Doctrine_Node_NestedSet[] $ancetres */
        $ancetres = $node->getAncestors();

        if( $ancetres !== false && $this->getEiVersionStructure()->isEiLoop() )
        {
            $contexteA = array();

            /** @var EiTestSetBlockParam $ancetre */
            foreach( $ancetres as $ancetre ){
                if( $ancetre->getEiVersionStructure()->isEiLoop() && $node->isDescendantOf($ancetre) ){
                    $contexteA[] = $ancetre->getIndexRepetition();
                }
            }

            $contexteA[] = $this->getIndexRepetition();

            $contexte = implode("-", $contexteA);
        }
        elseif( $ancetres !== false ){
            $contexte = "1";
        }

        return $contexte;
    }

    /**
     * Méthode permettant de regénérer les valeurs des éléments
     */
    public function regenerate()
    {
        // Si l'élément est un block, on le régénère.
        if( in_array($this->getEiVersionStructure()->getType(), array(EiVersionStructure::$TYPE_BLOCK, EiVersionStructure::$TYPE_FOREACH)) ){

            // On récupère la version de la structure.
            /** @var EiBlock|EiBlockForeach $versionStr */
            $versionStr = $this->getEiVersionStructure();
            // On récupère l'élément parent.
            $parent = $this->getEiTestSetBlockParamParent();
            // On récupère le xpath du père.
            $path = str_replace("/".$this->getName()."[". $this->getIndexRepetition() ."]", "", $this->getPath());

            // On regénère enfin les paramètres.
            $versionStr->generateTestSetParameters($this->getEiTestSet(), $parent, $this->getIndexRepetition(), $path);
        }
    }

    /**
     * Méthode permettant de synchroniser les valeurs des paramètres du block dans le jeu de données du JDT.
     */
    public function synchronizeWithDataSet()
    {
        $this->logger = sfContext::getInstance()->getLogger();

        /** @var EiTestSetBlockParam[] $childs */
        $childs =  $this->getNode()->getDescendants(1);

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   Parcours des " . count($childs) . " enfants.");

        if( $childs != null && (is_array($childs) || $childs instanceof Doctrine_Collection ) ){
            // Parcourt des enfants du block.
            foreach( $childs as $child )
            {
                $this->logger->info("----------------------------------------------------------");
                $this->logger->info("---   Parcours de ".$child->getName().".");

                // On traite uniquement les paramètres à synchroniser.
                if( $child->getType() == EiVersionStructure::$TYPE_BLOCK_PARAM )
                {
                    // On récupère les éléments mappés avec le paramètre.
                    /** @var EiBlockDataSetMapping[] $mappings */
                    $mappings = $child->getEiVersionStructure()->getEiVersionStructureDataSetMapping();

                    if( $mappings->count() > 0 )
                    {
                        $this->logger->info("----------------------------------------------------------");
                        $this->logger->info("---   Mapping détecté.");

                        /** @var EiBlockDataSetMapping $mapping */
                        foreach( $mappings as $mapping )
                        {
                            // On s'intéresse uniquement au cas où une synchronisation OUT a été définie.
                            // Si tel est le cas, on récupère l'élément du jeu de données du JDT correspondant.
                            if( $mapping->getType() == EiBlockDataSetMapping::$TYPE_OUT )
                            {
                                $structure = $this->getEiVersionStructure();
                                $mappingDs = $mapping->getEiDataSetStructureMapping();
                                $isForeach = $structure->getType() == EiVersionStructure::$TYPE_FOREACH;

                                $this->logger->info("----------------------------------------------------------");
                                $this->logger->info("---   Mapping out détecté avec ".$mappingDs." dans Foreach ? ".($isForeach ? "Oui":"Non").".");

                                if( $isForeach && !$mappingDs->getNode()->isDescendantOf($structure->getIteratorMapping()->getEiDataSetStructureMapping()) )
                                {
                                    /** @var EiTestSetDataSet $dataSetElement */
                                    $dataSetElement = EiTestSetDataSetTable::getInstance()->findOneByEiDataSetStructureIdAndIndexRepetitionAndEiTestSetId(
                                        $mapping->getEiDatasetStructureId(),
                                        1,
                                        $this->getEiTestSetId()
                                    );
                                }
                                else
                                {
                                    /** @var EiTestSetDataSet $dataSetElement */
                                    $dataSetElement = EiTestSetDataSetTable::getInstance()->findOneByEiDataSetStructureIdAndParentIndexRepetitionAndEiTestSetId(
                                        $mapping->getEiDatasetStructureId(),
                                        $this->getIndexRepetition(),
                                        $this->getEiTestSetId()
                                    );
                                }

                                if( $dataSetElement != null ){
                                    sfContext::getInstance()->getLogger()->info("----------------------------------------------------------");
                                    sfContext::getInstance()->getLogger()->info("---   MATCHED SYNC WITH VALUE " . $child->getValue());

                                    // Puis, on le met à jour avec la valeur du paramètre du block.
                                    $dataSetElement->setValue( $child->getValue() );
                                    $dataSetElement->setIsModified(true);
                                    $dataSetElement->save();
                                }
                            }

                        }
                    }
                    else{
                        $this->logger->info("----------------------------------------------------------");
                        $this->logger->info("---   Pas de mapping détecté.");
                    }
                }
            }
        }
    }

    /**********          GENERATION XML
    /******************************************************************************************************************/

    public function getXMLTag(){
        return $this->name;
    }

    /**
     * @param DOMDocument $documentXml
     * @param DOMDocument $parent
     */
    public function generateXML($documentXml, $parent)
    {
        /** @var EiTestSetBlockParam[] $descendants */
        $descendants = $this->getNode()->getDescendants(1);

        if( is_bool($descendants) && $this->getType() != EiVersionStructure::$TYPE_BLOCK_PARAM  ){
            $element = $documentXml->createElement($this->getXMLTag());
        }
        elseif( $this->getType() == EiVersionStructure::$TYPE_BLOCK_PARAM ){
            $element = $documentXml->createElement($this->getXMLTag(), $this->getValue());
        }
        elseif( method_exists($descendants, "toArray") ){
            $element = $documentXml->createElement($this->getXMLTag());

            /** @var EiBlockParam $descendant */
            foreach( $descendants as $descendant ){
                if( $descendant->getType() == EiVersionStructure::$TYPE_BLOCK_PARAM ){
                    $descendant->generateXML($documentXml, $element);
                }
            }
        }
        else{
            $element = $documentXml->createElement($this->getXMLTag());
        }

        $parent->appendChild($element);

        if( !is_bool($descendants) ){
            foreach( $descendants as $descendant ){
                if( $descendant->getType() != EiVersionStructure::$TYPE_BLOCK_PARAM ){
                    $descendant->generateXML($documentXml, $element);
                }
            }
        }
    }
}