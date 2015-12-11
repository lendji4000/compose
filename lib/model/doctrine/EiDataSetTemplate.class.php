<?php

/**
 * EiDataSetTemplate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiDataSetTemplate extends BaseEiDataSetTemplate
{
    /** @var sfLogger */
    private $logger;

    /** @var EiDataSetStructure $rootStr  */
    private $root_str = null;

    public function setRootStr($root_str) {
        $this->root_str = $root_str;
    }

    public function getRootStr(){
        return $this->root_str;
    }

    /**
     * @return array
     */
    public function getEiDataSetsReverse(){
        $versions = array();

        foreach( $this->getEiDataSets() as $version ){
            array_unshift($versions, $version);
        }

        return $versions;
    }

    /**
     * Méthode permettant de mettre à jour le jeu de données de rérérence.
     *
     * TODO: Améliorer l'approche de mise à jour du jeu de données dans la table EiCampaignGraph.
     */
    public function updateCampaignGraphDataSet(Doctrine_Connection $conn = null)
    {
        if( $conn == null ) $conn = Doctrine_Manager::connection();

        if( $this->getEiDataSet() != null && $this->getEiDataSet()->getId() != "" ){
            $sql = "UPDATE ei_campaign_graph SET data_set_id = ".$this->getEiDataSet()->getId()." ";
            $sql.= "WHERE data_set_id IN (SELECT id FROM ei_data_set WHERE ei_data_set_template_id = ".$this->getId().")";

            $conn->execute($sql);
        }
    }


    /***************     UTILITAIRES DE CREATION A VIDE
     *******************************************************************************************************************/

    /**
     * @param $nom
     * @param $description
     * @param EiNode $noeud
     * @param EiScenario $scenario
     * @throws Exception
     */
    public function createEmpty($nom, $description, EiNode $noeud, EiUser $user)
    {
        // On affecte le nom, la description, le noeud.
        $this->name = $nom;
        $this->description = $description;
        $this->setEiNode($noeud);

        // On affecte également l'utilisateur.
        $this->setUserId($user->getUserId());
        $this->setUserRef($user->getRefId());
    }

    /***************     SURCHARGES
     *******************************************************************************************************************/

    /**
     * Surcharge de la méthode save permettant de mettre à jour la table EiNode.
     *
     * @param Doctrine_Connection $conn
     */
    public function save(Doctrine_Connection $conn = null)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT SAUVEGARDE TEMPLATE");

        $isNew = $this->isNew();

        /** @var EiNode $ei_node */
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
            $ei_node->setType(EiNode::$TYPE_DATASET_TEMPLATE);
            $ei_node->setObjId($this->getId());
            $ei_node->setName($this->getName());
            $ei_node->save($conn);
        }
        elseif(!$isNew){
            $ei_node = $this->getEiNode();
            $ei_node->setName($this->getName());
            $ei_node->save($conn);
        }

        $this->updateCampaignGraphDataSet($conn);

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   FIN SAUVEGARDE TEMPLATE");
    }

}