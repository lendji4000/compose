<?php

/**
 * EiDataSet
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiDataSet extends BaseEiDataSet
{
    /** @var sfLogger */
    private $logger;

    /** @var \Doctrine_Collection Liste des data lines du JDD */
    private $EiDataLines;

    /** @var string Constante comportant le nom du modèle de la structure de données. */
    private static $NOM_TABLE_STRUCTURE = "EiDataSetStructure";

    /** @var EiDataSetStructureTable $tableStructure  */
    private $tableStructure = null;

    /** @var EiDataSetStructure $rootStr  */
    private $root_str = null;

    //*******************************************************//
    //**********          CONSTRUCTEUR(S)          **********//
    //*******************************************************//

    /**
     * Constructeur du JDD.
     *
     * @param null $table
     * @param bool $isNewEntry
     */
    public function __construct($table = null, $isNewEntry = false) {
        parent::__construct($table, $isNewEntry);

        $this->EiDataLines = new Doctrine_Collection('EiDataLine');
        $this->tableStructure = Doctrine_Core::getTable(self::$NOM_TABLE_STRUCTURE);
    }

    //***********************************************************//
    //**********          GETTER(S)/SETTER(S)          **********//
    //***********************************************************//
     
    public function addEiDataLine(EiDataLine $line){
        $this->EiDataLines->add($line);
        $line->setEiDataSet($this);
    }

    public function setRootStr($root_str) {
        $this->root_str = $root_str;
    }

    public function getFile(){
        return "";
    }

    //**************************************************//
    //**********          OPERATIONS          **********//
    //**************************************************//

    /**
     * @return EiDataLine
     */
    public function createRootDataLine()
    {
        $root_data_line = new EiDataLine();

        $root_data_line->setEiDataSetStructure($this->root_str);

        $root_data_line->setLft(1);
        $root_data_line->setRgt(2);
        $root_data_line->setLevel(0);
        $root_data_line->setEiDataSet($this);
        $root_data_line->save();

        $root_data_line->setRootId($root_data_line->getId());
        $root_data_line->save();

        return $root_data_line;
    }

    /**
     * @param $nom
     * @param $description
     * @param EiScenario $scenario
     * @throws Exception
     */
    public function createEmpty($nom, $description, EiNode $noeud, EiScenario $scenario){
        $this->name = $nom;
        $this->description = $description;
        $this->setEiNode($noeud);

        $root_str = $this->tableStructure->getRoot($scenario->getId());

        if (is_null($root_str))
            throw new Exception("Scenario's not found.");

        $this->setRootStr($root_str);
    }

    //*************************************************************//
    //**********          DATA LINES OPERATIONS          **********//
    //*************************************************************//

    /**
     * Return, if exists, the node corresponding to name & parent structure id.
     *
     * @param $name
     * @param $parent_str
     * @param $ei_scenario_strs
     * @return null
     */
    private function findInStructures($name, $parent_str, $ei_scenario_strs) {
        $res = null;

        foreach ($ei_scenario_strs as $str) {
            if ($str['slug'] == $name && $parent_str == $str['ei_dataset_structure_parent_id']) {
                $res = $str;
                break;
            }
        }

        return $res;
    }

    /**
     * @param Doctrine_Connection $conn
     * @throws Exception
     */
    public function createEmptyDataLines(Doctrine_Connection $conn = null, $depth = true)
    {
        /** @var EiNodeDataSet $root_str */
        $root_str = $this->tableStructure->getRoot($this->getEiNode()->getEiScenarioNode()->getObjId());

        if( $root_str != null ){
            $root_str->createEmptyDataLines($this, null, $depth);

            $this->root_str->save($conn);
        }
        else{
            throw new Exception("We can't process to data lines creation.");
        }
    }

    /**
     * Search and evaluate data set XML element and calculate lft/rgt/level.
     *
     * @param $listeElts
     * @param $compteur
     * @param $ei_dataset_structure
     * @param DOMElement $elt
     * @param $parent_id
     * @param $parent_strid
     */
    private function evaluateDataLines(&$listeElts, &$compteur, &$index, $ei_dataset_structure, $elt, $parent_strid = ""){
        $listeElts[$index] = array(
            "structure_id" => "",
            "name" => $elt->nodeName,
            "value" => "",
            "lft" => $compteur++,
            "rgt" => 0,
            "level" => 0
        );

        $myIndex = $index;

        if( ($eltStr = $this->findInStructures($elt->nodeName, $parent_strid, $ei_dataset_structure)) && $eltStr != null ){
            $listeElts[$myIndex]["structure_id"] = $eltStr["id"];
            $listeElts[$myIndex]["level"] = $eltStr["level"];

            if( $elt->hasAttribute("id") ){
                $listeElts[$myIndex]["id"] = $elt->getAttribute("id");
            }

            if( $elt->hasChildNodes() && $elt->childNodes->length > 1 ){
                foreach( $elt->childNodes as $child ){
                    if( $child->nodeName != "#text" ){
                        $this->evaluateDataLines($listeElts, $compteur, ++$index, $ei_dataset_structure, $child, $eltStr["id"]);
                    }
                }
            }
            else{
                $listeElts[$myIndex]["value"] = $elt->nodeValue;
            }
        }

        $listeElts[$myIndex]["rgt"] = $compteur++;
    }

    /**
     * TODO: Améliorer la gestion des max_execution_time
     * Create data lines from XML to Nested Tree.
     *
     * @param $file
     * @param null $root_data_line
     * @throws Exception
     */
    public function createDataLines($file, $root_data_line = null) {
        $maxExecutionTime = ini_get("max_execution_time");
        set_time_limit (0);
        $this->save();
        $xml = new DOMDocument();

        if( (is_file($file) && @$xml->load($file) === false) || (!is_file($file) && is_string($file) && @$xml->loadXML($file) === false) ){
            throw new Exception('Your XML file seems to be corrupted. Please check the syntax.');
        }

        //récupération de la racine.
        $root = $xml->documentElement;
        
        $root_data_line = $root_data_line === null ? $this->createRootDataLine():$root_data_line;

        //**************************************************//
        //***   ETAPE 1 : Récupération de la Structure   ***//
        //**************************************************//

        /** @var EiDataSetStructure[] $ei_dataset_structures */
        $ei_dataset_structures = $this->tableStructure
                ->createQuery('str')
                ->where("str.ei_scenario_id =  ?" , $this->root_str->getEiScenarioId())
                ->fetchArray()
        ;

        //********************************************//
        //***   ETAPE 2 : Analyse du fichier XML   ***//
        //********************************************//

        $compteur = 1;
        $index = 0;
        $listeElts = array();

        $this->evaluateDataLines($listeElts, $compteur, $index, $ei_dataset_structures, $root);

        //************************************//
        //***   ETAPE 4 : Calcul des IDs   ***//
        //************************************//

        /** @var EiDataLineTable $DataLinesTable */
        $DataLinesTable = Doctrine_Core::getTable("EiDataLine");
        $conn = Doctrine_Manager::connection();

        // On retire le root.
        $rootTab = array_shift($listeElts);

        $conn->beginTransaction();

        $DataLinesTable->insertDataLinesFromTab($listeElts, $root_data_line->getId(), $this->getId(), $conn);

        $root_data_line->setRgt($rootTab["rgt"]);
        $root_data_line->save($conn);

        $conn->commit();
        
        set_time_limit ( $maxExecutionTime );
    }

    /**
     * TODO: Améliorer la gestion des max_execution_time
     *
     * @param $file
     * @throws Exception
     */
    public function updateDataLines($file)
    {
        $maxExecutionTime = ini_get("max_execution_time");
        set_time_limit (0);
        $xml = new DOMDocument();

        if( (is_file($file) && @$xml->load($file) === false) || (!is_file($file) && is_string($file) && @$xml->loadXML($file) === false) ){
            throw new Exception('Your XML file seems to be corrupted. Please check the syntax.');
        }

        //récupération de la racine.
        $root = $xml->documentElement;

        //**************************************************//
        //***   ETAPE 1 : Récupération de la Structure   ***//
        //**************************************************//

        /** @var EiDataSetStructure[] $ei_dataset_structures */
        $ei_dataset_structures = $this->tableStructure
            ->createQuery('str')
            ->where("str.ei_scenario_id =  ?" , $this->getEiNode()->getEiScenarioNode()->getObjId())
            ->fetchArray()
        ;

        //********************************************//
        //***   ETAPE 2 : Analyse du fichier XML   ***//
        //********************************************//

        $compteur = 1;
        $index = 0;
        $listeElts = array();

        $this->evaluateDataLines($listeElts, $compteur, $index, $ei_dataset_structures, $root);

        //************************************//
        //***   ETAPE 4 : Calcul des IDs   ***//
        //************************************//

        /** @var EiDataLineTable $DataLinesTable */
        $DataLinesTable = Doctrine_Core::getTable("EiDataLine");
        $conn = Doctrine_Manager::connection();

        // On retire le root.
        $keys = array_keys($listeElts);
        $rootTab = $listeElts[$keys[0]];

        $conn->beginTransaction();

        $DataLinesTable->updateDataLinesFromTab($listeElts, $rootTab["id"], $this->getId(), $conn);

        $conn->commit();

        set_time_limit ( $maxExecutionTime );
    }
    
    /**
     * TODO: Améliorer la gestion des memory_limit.
     *
     * Génère le fichier XML relatif au jeu de données.
     * 
     * @return type
     * @throws Exception
     */
    public function generateXML(){
        $memoryLimit = ini_get("memory_limit");
        ini_set("memory_limit", "-1");

        $xml = new DOMDocument("1.0", "utf-8");
        $xml->formatOutput = true;

        // Récupéraiton de l'ID du scénario.
        $scenarioId = $this->getEiNode()->getEiScenarioNode()->getObjId();
        /** @var EiDataLine $rootDataLine */
        $rootDataLine = Doctrine_Core::getTable('EiDataLine')->getEiDataLineRoot($this->getId());
        // On récupère également le nombre de sous-noeuds que comporte chaque noeud.
        $dssNodesCount = Doctrine_Core::getTable("EiDataSetStructure")->getCountNodeChildren($scenarioId);
        // Tableau contenant la liste des noeuds déjà insérés dans le fichier XML avec comme index leur ID.
        $parents = array();
        // Tableau contenant la liste des index du tableau parents.
        $parentsKeys = array();
        // Compteur de parents.
        $parentsCount = 1;

        // Variable contenant le dernier parent.
        $lastParent = null;
        // Variable contenant l'avant-dernier parent.
        $beforeLastParent = null;

        // Récupération de la liste des lignes.
        $stack = $this->getTable()->getTreeArray($scenarioId, $this->getId());

        // On retire l'élément root traité ci-dessous.
        array_splice($stack, 0, 1);

        if($rootDataLine){
            $rootTag = $xml->createElement($rootDataLine->getEiDataSetStructure()->getSlug());
            
            $xml->appendChild($rootTag);

            $parentsKeys[$parentsCount] = $rootDataLine->getId();
            $parents[$rootDataLine->getId()] = array(
                "id" => $rootDataLine->getId(),
                "lft" => $rootDataLine->getLft(),
                "rgt" => $rootDataLine->getRgt(),
                "strId" => $rootDataLine->getEiDataSetStructureId(),
                "xml" => $rootTag
            );
            $lastParent = $parents[$rootDataLine->getId()];

            foreach( $stack as $element ){
                $tag = $xml->createElement($element["dss_slug"]);

                if ($element["dss_type"] == EiDataSetStructure::$TYPE_NODE) {
                    $parentsCount++;
                    $parentsKeys[$parentsCount] = $element["id"];
                    $parents[$element["id"]] = array(
                        "id" => $element["id"],
                        "lft" => $element["lft"],
                        "rgt" => $element["rgt"],
                        "strId" => $element["dss_id"],
                        "xml" => $tag
                    );
                    $beforeLastParent = $lastParent;

                    if( $lastParent != null && $lastParent["lft"] < $element["lft"] && $lastParent["rgt"] > $element["rgt"] ){
                        $beforeLastParent = $lastParent;
                    }

                    $lastParent = $parents[$element["id"]];
                }
                else {
                    $value = $xml->createTextNode($element["valeur"]);
                    $tag->appendChild($value);
                }

                $parent = null;

                // Si l'élément est contenu dans le dernier parent.
                if( $lastParent["lft"] < $element["lft"] && $lastParent["rgt"] > $element["rgt"] ){
                    $parent = $lastParent;
                }
                // Si l'élément est contenu dans l'avant dernier parent.
                elseif( $beforeLastParent != null && $beforeLastParent["lft"] < $element["lft"] && $beforeLastParent["rgt"] > $element["rgt"] ){
                    $parent = $beforeLastParent;
                }
                // Sinon, on recherche dans la liste des parents.
                else{
                    for($i = ($parentsCount - 1); $i >= 0; $i-- ){
                        $parentReversed = $parents[$parentsKeys[$i]];

                        if( $parentReversed["lft"] < $element["lft"] && $parentReversed["rgt"] > $element["rgt"] ){
                            $parent = $parentReversed;
                            break;
                        }
                    }
                }

                if( $parent != null ){
                    $parent["xml"]->appendChild($tag);

                    // Si le parent n'a pas de noeuds fils, on supprime ce qui le concerne.
                    if( $dssNodesCount[$parent["strId"]] == 0 ){
                        unset($parentsKeys[array_search($parent["id"], $parentsKeys)]);
                        unset($parents[$parent["id"]]);
                        $parentsCount = count($parents);
                    }
                }
            }


            $xml = $xml->saveXML();
            ini_set("memory_limit", $memoryLimit);

            return $xml;
        }
        else{
            ini_set("memory_limit", $memoryLimit);
            throw new Exception('Impossible de générer le XML du JDD : la lien de donné racine est introuvable');
        }
    }

    /**
     * Génère le fichier XML relatif au jeu de données.
     *
     * @deprecated
     *
     * @return type
     * @throws Exception
     */
    public function generateOldXML(){
        $xml = new DOMDocument("1.0", "utf-8");
        $xml->formatOutput = true;

        /** @var EiDataLine $rootDataLine */
        $rootDataLine = Doctrine_Core::getTable('EiDataLine')->getEiDataLineRoot($this->getId());

        if($rootDataLine){
            $rootTag = $xml->createElement($rootDataLine->getEiDataSetStructure()->getSlug());
            $rootTag->setAttribute("id", $rootDataLine->getId());

            $xml->appendChild($rootTag);

            $rootDataLine->generateOldXML($xml, $rootTag);

            return $xml->saveXML();

        }else{
            throw new Exception('Impossible de générer le XML du JDD : la lien de donné racine est introuvable');
        }
    }

    /**
     * @return array
     */
    public function getTreeArrayForITree(){
        /** @var EiDataLine $rootDataLine */
        $rootDataLine = Doctrine_Core::getTable('EiDataLine')->getEiDataLineRoot($this->getId());

        if( $rootDataLine == null ) return null;

        /** @var EiDataSetStructure $rootStr */
        $rootStr = $rootDataLine->getEiDataSetStructure();

        return $this->getTable()->getTreeArrayForITree($rootStr->getEiScenarioId(), $this->getId());
    }

    /**
     * Méthode permettant de vérifier puis compléter un jeu de données s'il manque des attributs.
     */
    public function completeDataSet()
    {
        /** @var EiDataSetStructureTable $dsTable */
        $dsTable = Doctrine_Core::getTable("EiDataSetStructure");
        /** @var EiDataLineTable $dlTable */
        $dlTable = Doctrine_Core::getTable("EiDataLine");
        // Récupération des éléments manquants.
        $eltsMqts = $dsTable->findMissingElementsInDataSet($this->getId(), $this->getEiNode()->getEiScenarioNode()->getObjId());

        if( $eltsMqts != null && $eltsMqts->count() > 0 ){
            // On parcourt la liste d'éléments manquants.
            /** @var EiDataSetStructure $elt */
            foreach( $eltsMqts as $elt ){
                // CAS ATTRIBUT.
                if( $elt->getType() == EiDataSetStructure::$TYPE_LEAF ){
                    $lignesConcernees = $dsTable->getRelatedDataSetLines($this->getId(), $elt->getEiDatasetStructureParentId());


                    if( count($lignesConcernees) > 0 ){
                        foreach( $lignesConcernees as $ligneConcernee ){
                            $parent = $dlTable->find($ligneConcernee["id"]);

                            $ligne = new EiDataLine();
                            $ligne->setEiDataSetId($this->getId());
                            $ligne->setEiDataSetStructureId($elt->getId());
                            $ligne->setRootId($elt->getRootId());

                            $ligne->save();

                            $ligne->getNode()->insertAsLastChildOf($parent);
                        }
                    }
                }
            }
        }
    }

    /**
     * Return count of lines
     */
    public function getCountOfLines(){
        return $this->getTable()->getCountOfLines($this->getId());
    }

    /**
     * @param Doctrine_Connection $conn
     */
    public function save(Doctrine_Connection $conn = null)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT SAUVEGARDE DATA SET");

        $isNew = $this->isNew();
        
        if ($isNew) {
            if($this->getEiNode() == null){
                $ei_node = new EiNode();
                $this->setEiNode($ei_node);
            }
            else{
                $ei_node = $this->getEiNode();
            }
        }

        parent::save($conn);

        if ($isNew) {
            $ei_node->setType('EiDataSet');
            $ei_node->setObjId($this->getId());
            $ei_node->setName($this->getName());

            if( $this->getEiDataSetTemplate() != null ){
                $ei_node->setRootId($this->getEiDataSetTemplate()->getEiNode()->getId());
            }

            $ei_node->save($conn);
        }else{
            $ei_node = $this->getEiNode();
            $ei_node->setName($this->getName());
            $ei_node->save($conn);
        }

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   FIN SAUVEGARDE DATA SET");
    }

}
