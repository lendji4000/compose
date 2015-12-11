<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version3 extends Doctrine_Migration_Base
{
    public function up()
    {
        /* Gestions des sujets ayant les mêmes packages .*/
        $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction();
            
        /* Récupération des différents projets pour l'ajout des nouveaux statuts */
        $ei_projects = $conn->getTable('EiProjet')->findAll();
        if (count($ei_projects) > 0):
            foreach ($ei_projects as $ei_project):
                $rejected = Doctrine_Core::getTable('EiSubjectState')->findOneByNameAndProjectIdAndProjectRef(
                        'Rejected', $ei_project->getProjectId(), $ei_project->getRefId());
                $analysed = Doctrine_Core::getTable('EiSubjectState')->findOneByNameAndProjectIdAndProjectRef(
                        'Analysed', $ei_project->getProjectId(), $ei_project->getRefId());
                if ($rejected == null && $analysed == null):
                    $conn->execute("INSERT INTO  ei_subject_state ( name, color_code, display_in_home_page, display_in_search, display_in_todolist, close_del_state, project_id, project_ref, created_at, updated_at) "
                            . "VALUES ('Analysed', '#DEA33F', '1', '1', '1','0'," . $ei_project->getProjectId() . "," . $ei_project->getRefId() . ", '".date("Y-m-d H-i-s")."', '".date("Y-m-d H-i-s")."'), "
                            . "('Rejected', '#B9B5AF', '0', '0', '0', '1'," . $ei_project->getProjectId() . "," . $ei_project->getRefId() . ", '".date("Y-m-d H-i-s")."', '".date("Y-m-d H-i-s")."');");
                endif;
                /* Gestion des nouvelles priorités */
                $blocking = Doctrine_Core::getTable('EiSubjectPriority')->findOneByNameAndProjectIdAndProjectRef(
                        'Blocking', $ei_project->getProjectId(), $ei_project->getRefId());
                if($blocking == null):
                    $conn->execute("INSERT INTO  ei_subject_priority ( name, color_code, display_in_home_page, display_in_search, display_in_todolist, project_id, project_ref, created_at, updated_at) "
                            . "VALUES ('Blocking', '#DEA33F', '1', '1', '1'," . $ei_project->getProjectId() . "," . $ei_project->getRefId() . ", '".date("Y-m-d H-i-s")."', '".date("Y-m-d H-i-s")."');");
                endif;
            endforeach;

        endif;

        //Mise à jour par défaut des statuts de bug et livraison des projets
        //$this->log('Mise à jour par défaut des statuts de bug et livraison des projets');
        //$this->log('Mise à jour par défaut des statuts de   livraison des projets');
        $conn->execute('UPDATE ei_delivery_state SET color_code="#36a9e1",display_in_home_page=1,display_in_search=1,close_state=0 WHERE name="Open" ');
        $conn->execute('UPDATE ei_delivery_state SET color_code="#D8473D",display_in_home_page=0,display_in_search=0,close_state=1 WHERE name="Close" ');
        //$this->log('Tâche éffectuée');
        //$this->log('Mise à jour par défaut des statuts de   bug des projets');
        $conn->execute('UPDATE ei_subject_state SET color_code="#53567C",display_in_home_page=1,display_in_search=1,display_in_todolist=1,close_del_state=0 WHERE name="New" ');
        $conn->execute('UPDATE ei_subject_state SET color_code="#DFBE37",name="In development",display_in_home_page=1,display_in_search=1,display_in_todolist=1,close_del_state=0 WHERE name="In dev" ');
        $conn->execute('UPDATE ei_subject_state SET color_code="#36a9e1",display_in_home_page=1,display_in_search=1,display_in_todolist=1,close_del_state=0 WHERE name="In test"');
        $conn->execute('UPDATE ei_subject_state SET color_code="#6E719D",name="Need information",display_in_home_page=1,display_in_search=1,display_in_todolist=1,close_del_state=0 WHERE name="In dispute" ');
        $conn->execute('UPDATE ei_subject_state SET color_code="#D8473D",name="Test invalid",display_in_home_page=1,display_in_search=1,display_in_todolist=1,close_del_state=0 WHERE name="Test invalid" ');
        $conn->execute('UPDATE ei_subject_state SET color_code="#58A155",name="Test valid",display_in_home_page=0,display_in_search=0,display_in_todolist=0,close_del_state=0 WHERE name="Valid"');
        $conn->execute('UPDATE ei_subject_state SET color_code="#838483",name="Closed",display_in_home_page=0,display_in_search=0,display_in_todolist=0,close_del_state=1 WHERE name="Close" ');
        $conn->execute('UPDATE ei_subject_state SET color_code="#B9B5AF",name="Rejected",display_in_home_page=0,display_in_search=0,display_in_todolist=0,close_del_state=1 WHERE name="Rejected" ');
        
        /* Mise à jour des couleurs de priorités */
        $conn->execute('UPDATE ei_subject_priority SET color_code="#FF340D",name="Blocking" WHERE name="Blocking" ');
        $conn->execute('UPDATE ei_subject_priority SET color_code="#E81E0D",name="High" WHERE name="High"');
        $conn->execute('UPDATE ei_subject_priority SET color_code="#E8A10D",name="Medium" WHERE name="Medium" ');
        $conn->execute('UPDATE ei_subject_priority SET color_code="#E8DC0D",name="Low"  WHERE name="Low" ');

        //$this->log('Tâche éffectuée');
        //$this->log('Mise à jour des statuts éffectuée avec success ...');    
            
            
        /* Mise à jour des tables de Log et Test set pour la prise en compte des itérations  , function_id et function_ref */
        
         //Mise à jour des ei_log_function
            $conn->execute("update ei_log_function lf   set lf.function_id=(select tsf1.function_id from ei_test_set_function tsf1 where lf.ei_test_set_function_id= tsf1.id) "); 
            $conn->execute("update ei_log_function lf   set lf.function_ref=(select tsf1.function_ref from ei_test_set_function tsf1 where lf.ei_test_set_function_id= tsf1.id)  ");
        //Mise à jour ei_log_param à partir des ei_log_function
            $conn->execute("update ei_log_param lp  set lp.function_id=(select lf1.function_id from ei_log_function lf1 where lp.ei_log_function_id= lf1.id)  "); 
            $conn->execute("update ei_log_param lp  set lp.function_ref=(select lf1.function_ref from ei_log_function lf1 where lp.ei_log_function_id= lf1.id)  "); 
        // Mise à jour des ei_test_set_param
            $conn->execute(" update ei_test_set_param tsp set tsp.function_ref=(select tsf.function_ref from ei_test_set_function tsf where tsp.ei_test_set_function_id= tsf.id)  ");     
            $conn->execute(" update ei_test_set_param tsp set tsp.function_id=(select tsf.function_id from ei_test_set_function tsf where tsp.ei_test_set_function_id= tsf.id)   ");   
 
   
        /* Suppression des tickets générés par le bug de création d'un bug et donc ne servant à rien.
         * Script ammené à être utiliser qu'une seule fois */
            //$this->log('Mise à null des package_id , package_ref de bugs n ayant pas de ticket associé');
            $conn->execute("update ei_subject s left join ei_ticket t 
                            on s.package_id =t.ticket_id and s.package_ref=t.ticket_ref
                            set s.package_id =NULL and s.package_ref=NULL
                            where CONCAT('Package_S',s.id)!=t.name"); 
            $conn->execute("update ei_subject s  left join ei_ticket t on s.package_id=t.ticket_id and s.package_ref=t.ticket_ref
            set package_id=NULL , package_ref=NULL 
            where   t.ticket_id IS   NULL OR   t.ticket_ref is   NULL  ");
//             $this->log('Suppression des tickets non associes a des scripts');
//            $conn->execute('delete from ei_ticket where (ticket_id,ticket_ref)  not in (select ticket_id,ticket_ref from ei_script)');
            //$this->log('Suppression des liaisons user-ticket non associes a des tickets');
            $conn->execute('delete from ei_user_ticket  where (ticket_id,ticket_ref) not in (select ticket_id,ticket_ref from ei_ticket )');
            $conn->execute('delete sp  from ei_scenario_package sp left join ei_subject s on sp.package_id=s.package_id and sp.package_ref=s.package_ref '.
                            ' where s.package_id is NULL or s.package_ref is NULL');
            $conn->execute('delete sp  from ei_scenario_package sp left join ei_ticket t on sp.package_id=t.ticket_id and sp.package_ref=t.ticket_ref '.
                            ' where t.ticket_id is NULL or t.ticket_ref is NULL');
        
         
        
       
            $subjs = $conn->fetchAll("select DISTINCT(s.id) as s_id, s.name as s_name, s.package_id as s_package_id,s.package_ref as s_package_ref,
                    s2.id as s2_id,s2.name as s2_name,s2.package_id as s2_package_id,s2.package_ref as s2_package_ref,
                    t.ticket_id as t_ticket_id,t.ticket_ref as t_ticket_ref,t.name as t_name from
                    ei_subject s left join ei_ticket t on t.ticket_id=s.package_id and t.ticket_ref=s.package_ref 
                    left join ei_subject s2 on s.package_id=s2.package_id and s.package_ref=s2.package_ref where s.id <> s2.id and t.ticket_id is not null  ");
            if (count($subjs) > 0):
                foreach ($subjs as $subj):
                    if ($subj['t_name'] != "Package_S" . $subj['s_id']):
                        $ids[] = $subj['s_id'];
                    endif;
                endforeach;
                $str = "(" . implode(',', $ids) . ")"; //echo $str;
                if (isset($ids) && count($ids) > 0):
                    $conn->execute("update ei_subject set package_id =NULL , package_ref =NULL where id IN " . $str);
                endif;
            endif;

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }
	
	public function postUp() {
		$conn=Doctrine_Manager::getInstance()->getCurrentConnection(); 
	}


    public function down()
    { 
    }
}