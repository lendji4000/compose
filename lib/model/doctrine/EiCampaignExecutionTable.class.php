<?php

/**
 * EiCampaignExecutionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiCampaignExecutionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiCampaignExecutionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiCampaignExecution');
    }

    /**
     * @param $campaign_id
     * @param $orders
     */
    public function getAllCampaignExecutions($campaign_id, $orders = array()){
        // Création de la requête SQL de récupération
        $sql = "SELECT * FROM ei_campaign_status_vw WHERE campaign_id = " . $campaign_id . " ORDER BY created_at DESC;";
        // Récupération des résultats.
        $resultats = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll($sql);

        // Création de la collection.
        $collection = new Doctrine_Collection("EiCampaignExecution");

        foreach( $resultats as $resultat ){
            $collection->add($this->createObjectStatusFromArray($resultat));
        }

        return $collection;
    }

    /**
     * @param $execution_id
     * @param bool $fullObj
     * @return EiCampaignExecution|null
     */
    public function findExecution($execution_id, $fullObj = false)
    {
        // Création de la requête SQL de récupération
        $sql = "SELECT * FROM ei_campaign_status_vw WHERE id = " . $execution_id . ";";
        // Récupération des résultats.
        $resultat = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchRow($sql);

        if( $resultat === false ){
            return null;
        }
        elseif( !$fullObj ){
            return $this->createObjectStatusFromArray($resultat);
        }
        else{
            $exec = $this->findOneById($execution_id);

            return $this->createObjectStatusFromArray($resultat, $exec);
        }
    }

    /**
     * @param $execution_id
     */
    public function closeExecution($execution_id)
    {
        // Mise à jour de l'élément de la pile si besoin.
        Doctrine_Core::getTable("EiExecutionStack")->updateState($execution_id);

        // Création de la requête SQL de récupération
        $sql = "SELECT COUNT(*) as count FROM ei_campaign_execution_graph WHERE id = " . $execution_id . " AND state = '".StatusConst::STATUS_CAMP_BLANK_DB."';";

        // Récupération des résultats.
        $resultat = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchRow($sql);

        if( $resultat === false ){
            return null;
        }
        elseif( $resultat["count"] == 0 ){
            Doctrine_Manager::getInstance()->getCurrentConnection()->execute("UPDATE ei_campaign_execution SET termine = TRUE WHERE id = ".$execution_id.";");
        }
    }

    /**
     * @param array $array
     * @param EiCampaignExecution $exec
     * @return EiCampaignExecution
     */
    private function createObjectStatusFromArray(array $array, EiCampaignExecution $exec = null){

        if( $exec == null ){
            // Création de l'objet.
            $exec = new EiCampaignExecution();
            $exec->fromArray($array);
        }

        $exec->setStatusName($array["status_nom"]);
        $exec->setStatusColor($array["status_color"]);
        $exec->setDuree($array["duree"]);
        $exec->setNbEtapesCamp($array["nb_step_camp"]);
        $exec->setNbEtapesExecution($array["nb_step_ex"]);
        $exec->setNbEtapesExecutees($array["nb_step_executed"]);
        $exec->setAuthorUsername($array["author_username"]);

        return $exec;
    }
    
    /* Récupération des fonctions executées sur une execution de campagne */
    public function getExecutionFunctions($execution_id, Doctrine_Connection $conn=null){
        if($execution_id==null) return array();
        if($conn==null) $conn = Doctrine_Manager::connection();         
        $q=" 
          select vw1.* ,vw2.id as subject_id,count(*) as nbBugs ,SUM(CASE WHEN vw2.close_del_state =1 THEN 1 ELSE 0 END) as nbOpenBugs,
            MAX(vw2.s_created_at) as last_bug_date_creation
            from campaign_execution_stat_vw vw1
            left join intervention_impacts_vw vw2 on vw1.function_id=vw2.function_id and vw1.function_ref=vw2.function_ref
            where vw1.execution_id=".$execution_id.
            " group by vw1.function_id , vw1.function_ref;
        "; 
    //throw new Exception ($q);
        return $conn->fetchAll($q);
         
    }
    
    /* Récupération des fonctions non executées sur une execution de campagne */
    public function getUnExecutedFunctions($project_id,$project_ref,$execution_id, Doctrine_Connection $conn=null){
        if( $execution_id==null) return array();
        if($conn==null) $conn = Doctrine_Manager::connection();     
        
        $q=" select distinct(t1.id) as t_id ,t1.obj_id as t_obj_id,t1.ref_obj as t_ref_obj, t1.name as function_name ,t1.path as t_path,k.criticity  as criticity ,
            MAX(sb.created_at) as last_creat_date ,count(DISTINCT(sb.id)) as nbSubject , SUM(   case when st.close_del_state=0 then 1 else 0 end)  as nbSubOpen 
                from ei_tree t1
                inner join    kal_function k on t1.obj_id=k.function_id and t1.ref_obj=k.function_ref
                left join ei_script s on s.function_id=k.function_id and s.function_ref=k.function_ref 
                            left join ei_subject sb on sb.package_id=s.ticket_id and sb.package_ref=s.ticket_ref
                            left join ei_subject_state st on st.id= sb.subject_state_id
                left  join 
                ( select t.id ,t.obj_id ,  t.ref_obj ,t.project_id ,t.project_ref         
                            from ei_tree t  
                            inner join ei_test_set_function tsf on tsf.function_id=t.obj_id and tsf.function_ref=t.ref_obj 
                            inner join ei_campaign_execution_graph ceg on ceg.ei_test_set_id=tsf.ei_test_set_id  
                            where     t.project_id =".$project_id."  and t.project_ref=".$project_ref."  and   ceg.execution_id = ".$execution_id."
                           
                 )as exTab
                 on t1.obj_id =exTab.obj_id and t1.ref_obj=exTab.ref_obj
                 where t1.project_id =".$project_id." and t1.project_ref=".$project_ref."  and t1.type like  '%Function%' and  exTab.obj_id is NULL
                      group by t1.id  
                      order by sb.created_at desc
                 "; 
        return $conn->fetchAll($q);
         
    }
    
    /* Récupération des détails d'une execution de campagne */
    
    public function getExecutionDetails($execution_id, Doctrine_Connection $conn=null){
        if($execution_id==null) return array();
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $q="select ce.id as ce_id, bt.name as bt_name,p.name as p_name,g.username as g_username, ce.termine as ce_termine,ce.created_at as ce_created_at , ".
            " count(distinct(cep.id)) as nbStep ,SUM(tsf.duree) as time
            from ei_campaign_execution ce 
           left join ei_campaign_execution_graph cep on cep.execution_id=ce.id and cep.execution_id=".$execution_id.
           " left join ei_test_set ts on cep.ei_test_set_id = ts.id ".
           "left join ei_test_set_function tsf on tsf.ei_test_set_id=ts.id ".
           " left join ei_profil p on p.profile_id=ce.profile_id and p.profile_ref=ce.profile_ref ".
           " left join sf_guard_user g on g.id=ce.author_id ".
           " left join ei_block_type bt on bt.id=ce.on_error ".
           " where ce.id=".$execution_id;
        return $conn->fetchAll($q);
    }

    //*******************************************************************//
    //**********     METHODES DE MAJ/EPURATION DES STATUTS     **********//
    //*******************************************************************//

    /**
     * Méthode permettant de passer les exécutions de JDT à terminée si sa dernière mise à jour date de plus de 1 heure.
     */
    public function closeUnterminatedTestSet(){
        $conn = Doctrine_Manager::connection();

        $sql1 = "
        SELECT execution_id
        FROM ei_campaign_execution_graph exg, ei_campaign_execution ex
        WHERE termine = 0
        AND execution_id = ex.id
        GROUP BY execution_id
        HAVING MAX(exg.updated_at) < (NOW() - INTERVAL 30 MINUTE);
        ";

        $ids = $conn->execute($sql1)->fetchAll(PDO::FETCH_COLUMN);

        if( is_array($ids) && count($ids) > 0 ){
            $sql2 = "
                UPDATE ei_campaign_execution
                SET termine = 1
                WHERE id IN (".implode(",", $ids).")
            ";

            $conn->exec($sql2);
        }

        $this->cleanStepStatus();
    }

    /**
     * Méthode permettant de mettre à jour correctement le statut des étapes d'une exécution.
     */
    public function cleanStepStatus(){
        $conn = Doctrine_Manager::connection();

        $sql3 = "
            UPDATE ei_campaign_execution_graph, ei_test_set
            SET state = CASE WHEN status = 'AB' OR status = 'NA' Then status = 'Processing' WHEN status = 'KO' Then 'Ko' ELSE 'Ok' END
            WHERE status != CASE WHEN state = 'Processing' OR state = 'Blank' Then 'AB' WHEN state = 'Ko' Then 'KO' ELSE 'OK' END
            AND ei_test_set_id = ei_test_set.id
        ";

        $conn->exec($sql3);
    }
}