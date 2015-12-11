<?php

class TestSetDataSetGenerator
{
    /** @var Chronometre */
    private $chronometre;

    /** @var EiTestSet $testSet */
    private $testSet;

    /** @var TreeExplorer $tree */
    private $tree;

    /** @var EiDataLine[] $lignes */
    private $lignes;

    /** @var ITreeExplorerOccurenceItem[] $references */
    private $references = array();

    /** @var array $repeatReferences */
    private $repeatReferences = array();

    /** @var int */
    private $root_id;

    /** @var EiTestSetDataSet */
    private $root;

    /** @var int  */
    private $maxRight = 2;

    private $registered = array();

    private $registeredChildren = array();

    private static $SQL_INSERT_ELEMENT = "INSERT INTO `ei_test_set_data_set` (`id`, `ei_test_set_id`, `ei_data_set_structure_id`, `parent_id`,
`index_repetition`, `type`, `name`, `slug`, `value`, `root_id`, `lft`, `rgt`, `level`, `created_at`, `updated_at`, `parent_index_repetition`)
VALUES (<{id}>, <{ei_test_set_id}>, <{ei_data_set_structure_id}>, <{parent_id}>, <{index_repetition}>, <{type}>, <{name}>, <{slug}>,
<{value}>, <{root_id}>, <{lft}>, <{rgt}>, <{level}>, NOW(), NOW(), <{parent_index_repetition}>);";

    private $sqlToInsert = array();

    /**
     * @param EiTestSet $testSet
     */
    public function __construct(EiTestSet $testSet)
    {
        $this->testSet = $testSet;
        $this->chronometre = new Chronometre();
    }

    public function generateDataSet()
    {
        $this->chronometre->debug("------------------------------------------------------------------------------------");
        $this->chronometre->debug("-----   DEBUT GENERATION DU JEU DE DONNEES");
        $this->chronometre->debug("------------------------------------------------------------------------------------");

        $timerGlobalStart = microtime(true);

        /** @var EiDataLineTable $tableJdd */
        $tableJdd = Doctrine_Core::getTable("EiDataLine");
        /** @var EiTestSetDataSetTable $tableJdtJdd */
        $tableJdtJdd = Doctrine_Core::getTable("EiTestSetDataSet");
        /** @var EiDataSetStructureTable $tableStrJdd */
        $tableStrJdd = Doctrine_Core::getTable("EiDataSetStructure");

        $this->chronometre->lancerChrono("Recuperation arbre");
        // On récupère l'arbre de la structure du JDD.
        $structureArbre = $tableStrJdd->getTreeArrayForITree($this->testSet->getEiScenarioId());
        // Fin & affichage
        $this->chronometre->arreterEtAfficherChrono();

        $this->chronometre->lancerChrono("IMPORTATION ARBRE");
        // On créer le TreeExplorer.
        $this->tree = new TreeExplorer();
        // On importe la structure de l'arbre.
        $this->tree->import($structureArbre);
        // Fin & affichage
        $this->chronometre->arreterEtAfficherChrono();

        $chronoGeneration = new Chronometre();

        $chronoGeneration ->lancerChrono("GENERATION JDD POUR JDT");

        // Si un jeu de données existe et que nous sommes à la première génération.
        if( $this->testSet->getEiDataSetId() != "" && $this->testSet->getEiTestSetDataSet()->count() == 0 )
        {
            $conn = Doctrine_Manager::connection();
            $chronoSub = new Chronometre();
            $datas = array();
            $nodes = array();

            $chronoSub->lancerChrono("PERFORMANCE - COPIE JDD -> JDD FOR JDT");
            $tableJdtJdd->copyDataLinesFromDataSet($this->testSet->getId(), $this->testSet->getEiDataSetId(), $conn);
            $chronoSub->arreterEtAfficherChrono();

            $chronoSub->lancerChrono("PERFORMANCE - RECUPERATION ELTS");
            $lines = $tableJdtJdd->getLines($this->testSet->getId());
            $chronoSub->arreterEtAfficherChrono();

            $chronoSub->lancerChrono("PERFORMANCE - TRAITEMENT ELTS");

            foreach($lines as $line){
                $data = array(
                    "id" => $line["id"],
                    "structure_id" => $line["ei_data_set_structure_id"],
                    "type" => $line["type"],
                    "lft" => $line["lft"],
                    "rgt" => $line["rgt"],
                    "level" => $line["level"]
                );

                $data = array_merge($data, $this->evaluateIndexes($nodes, $data["structure_id"], $data["lft"], $data["rgt"]));

                if( $data["type"] == EiDataSetStructure::$TYPE_NODE ){
                    array_unshift($nodes, $data);
                }

                $datas[] = $data;
            }
            $chronoSub->arreterEtAfficherChrono();

            try{
                // Début chrono.
                $chronoSub->lancerChrono("PERFORMANCE - SAUVEGARDE ELTS");

                // Début transaction
                $conn->beginTransaction();

                // Insertion des lignes.
                $tableJdtJdd->updateLinesIndexesFromTab($datas, $this->testSet->getId(), $conn);

                // COMMIT.
                $conn->commit();

                // Fin chrono.
                $chronoSub->arreterEtAfficherChrono();
            }
            catch( Exception $exc ){
                $conn->rollback();
            }
        }
        else{
            $this->lignes = $tableStrJdd->createQuery("q")
                ->from("EiDataSetStructure dss")
                ->where("dss.ei_scenario_id = ?", $this->testSet->getEiScenarioId())
                ->andWhere("dss.type = ?", EiDataSetStructure::$TYPE_LEAF)
                ->andWhere("dss.level = 1")
                ->orderBy("dss.lft")
                ->execute()
            ;

            $this->treatEmpty($this->tree->getRoot());

            $this->generate($this->tree->getRoot());

            if( $this->maxRight > 2 ){
                // Mise à jour du root_id pour les éléments.
                $this->root->setRgt($this->maxRight + 1);
                $this->root->save();
            }
        }
        // Fin & affichage
        $chronoGeneration->arreterEtAfficherChrono();

        // Arrêt du timer global.
        $timerGlobalEnd = microtime(true);

        // Affichage du chrono.
        $this->chronometre->afficherChrono("GENERATION DU JEU DE DONNEES", $timerGlobalStart, $timerGlobalEnd);
    }

    /**
     * @param ITreeExplorerItem $element
     */
    private function treatEmpty(ITreeExplorerItem $element)
    {
        $item = new TreeExplorerOccurrence("", 1);
        $item->setId(0);
        $item->setReference($element);

        $item->setLeft($element->getLeft());
        $item->setRight($element->getRight());

        if( $element->getParent() != null && isset($this->references[$element->getParent()->getId()]) ){
            $item->setParent($this->references[$element->getParent()->getId()]);
        }

        $element->addOccurrence($item);

        $this->references[$element->getId()] = $item;

        /** @var ITreeExplorerItem $child */
        foreach( $element->getChildren() as $child ){
            $this->treatEmpty($child);
        }
    }

    /**
     * Génère, à partir de l'arbre ITreeExplorer l'arborescence du jeu de données pour le JDT.
     *
     * @param ITreeExplorerItem $element
     */
    private function generate(ITreeExplorerItem $element, $isParentRoot = false)
    {
        $elt = null;

        try
        {
            // On parcourt toutes les occurences de l'élément de la structure.
            foreach( $element->getOccurrences() as $occurrence )
            {
                $pid = $occurrence->getParent() != null ? $occurrence->getParent()->getId():null;
                $pIndex = $occurrence->getParent() != null ? $occurrence->getParent()->getRepeatIndex():$occurrence->getRepeatIndex();
                // On crée l'objet.
                $elt = new EiTestSetDataSet();

                // Puis, on lui affecte les quelques éléments en notre possession.
                $elt->setEiTestSetId($this->testSet->getId());
                $elt->setEiDataSetStructureId($element->getId());
                $elt->setIndexRepetition( $occurrence->getRepeatIndex() );
                $elt->setParentIndexRepetition( $pIndex );
                $elt->setType( $element->getType() == "root" || $element->getType() == "node" ? EiDataSetStructure::$TYPE_NODE:EiDataSetStructure::$TYPE_LEAF );
                $elt->setName( $element->getName() );
                $elt->setSlug( $element->getSlug() );
                $elt->setValue($occurrence->getValue() != null ? $occurrence->getValue():"" );

                if( $occurrence->getParent() != null && isset($this->registered[$occurrence->getParent()->getId()]) ){
                    $elt->setParentId($this->registered[$occurrence->getParent()->getId()]->getId());
                }
                elseif( !(is_bool($isParentRoot) && $isParentRoot == false) ){
                    $elt->setParentId($isParentRoot->getId());
                }

                if( $element->getType() == "root" )
                {
                    $elt->setLft(1);
                    $elt->setRgt(2);
                    $elt->setLevel(0);

                    $elt->save();

                    $elt->setRootId($elt->getId());

                    $elt->save();

                    $this->root_id = $elt->getId();
                    $this->root = $elt;
                }
                elseif( ($pid != null || $pid == 0) && isset($this->registered[$pid]) )//&& (is_bool($isParentRoot) && $isParentRoot == false) )
                {
                    if( $element->getType() == "leaf" ){
                        $occurrence->setLeft($occurrence->getLeft() + (($pIndex - 1) * $occurrence->getRightNode()));
                        $occurrence->setRight($occurrence->getRight() + (($pIndex - 1) * $occurrence->getRightNode()));

                        if( $occurrence->getRight() > $this->maxRight ){
                            $this->maxRight = $occurrence->getRight();
                        }
                    }

                    $elt->setLft($occurrence->getLeft());
                    $elt->setRgt($occurrence->getRight());
                    $elt->setLevel($occurrence->getLevel());
                    $elt->setRootId($this->root_id);
                    $elt->save();

                    if( !isset($this->registeredChildren[$pid]) )
                    {
                        $this->registeredChildren[$pid] = array();
                        $this->registeredChildren[$pid][] = $elt;
                    }
                    else{
                        $this->registeredChildren[$pid][] = $elt;
                    }
                }

                $this->registered[$occurrence->getId()] = $elt;
            }

            /** @var ITreeExplorerItem $child */
            foreach( $element->getChildren() as $child ){
                $this->generate($child, $elt != null && $element->getType() == "root" ? $elt:false);
            }

        }
        catch(Exception $exc){
            print_r($exc);
        }
    }

    /**
     * @param $nodes
     * @param $dsId
     * @param $lft
     * @param $rgt
     * @return array
     */
    private function evaluateIndexes($nodes, $dsId, $lft, $rgt){
        $index = 1;
        $parentInd = 1;
        $parent = null;

        foreach($nodes as $node){
            if( $parent == null && $node["lft"] < $lft && $node["rgt"] > $rgt ){
                $parentInd = $node["index_repetition"];
                $parent = $node;
                break;
            }
        }

        foreach($nodes as $node){
            if( $dsId == $node["structure_id"] && $parent["lft"] < $node["lft"] && $parent["rgt"] > $node["rgt"] ){
                $index++;
            }
        }

        return array(
            "index_repetition" => $index,
            "parent_index_repetition" => $parentInd
        );
    }

} 