<?php

/**
 * EiCampaignExecutionGraphTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiCampaignExecutionGraphTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiCampaignExecutionGraphTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiCampaignExecutionGraph');
    }

    /**
     * Méthode permettant de générer la liste séquentielle des scénarios à exécuter.
     *
     * @param EiCampaignExecution $execution
     * @param Doctrine_Connection $conn
     * @return Doctrine_Collection
     */
    public function getGraphHasChainedList(EiCampaignExecution $execution, Doctrine_Connection $conn=null){

        $query = Doctrine_Query::create()
            ->select("graphs.*")
            ->from("EiCampaignExecutionGraph graphs")
            ->where("graphs.execution_id = ?", $execution->getId())
            ->orderBy("graphs.position asc");

        return $query->execute();
    }

    /**
     * @param EiCampaignExecution $execution
     * @return array
     */
    public function getTestSetExecutionInfos(EiCampaignExecution $execution){
        // Création de la requête SQL de récupération
        $sql = "SELECT id, duree, status_nom, status_color FROM ei_test_set_status_vw WHERE id IN (" .
            "SELECT ei_test_set_id FROM ei_campaign_execution_graph WHERE execution_id =" . $execution->getId() .
            ");"
        ;
        // Récupération des résultats.
        $resultats = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll($sql);
        $datas = array();

        foreach( $resultats as $resultat ){
            $datas[$resultat["id"]] = $resultat;

            unset($datas[$resultat["id"]]["id"]);
        }

        return $datas;
    }
}