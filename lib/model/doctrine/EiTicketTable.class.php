<?php

/**
 * EiTicketTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiTicketTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiTicketTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiTicket');
    }
    //Rechargement des éléments de type EiTicket pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection(); 
        
        $items = $projets->getElementsByTagName("ei_tickets");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_tickets = $items->item(0)->getElementsByTagName("ei_ticket");

            $stmt = $conn->prepare("INSERT INTO ei_ticket (ticket_id, ticket_ref,project_ref,project_id,name,state,is_active,creator_id,creator_ref,created_at,updated_at) "
                            ."VALUES (:ticket_id, :ticket_ref,:project_ref,:project_id,:name,:state,:is_active,:creator_id,:creator_ref,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE ticket_id=ticket_id,ticket_ref=ticket_ref");
            if($ei_tickets->length > 0){
                foreach ($ei_tickets as $ei_ticket) {

                    $ticket_id = $ei_ticket->getAttribute("ticket_id");
                    $ticket_ref = $ei_ticket->getAttribute("ticket_ref"); 
                    //recherche du profil en base
                    if ($ticket_id != null && $ticket_ref) {  
                        $stmt->bindValue("ticket_id", $ticket_id);
                        $stmt->bindValue("ticket_ref", $ticket_ref);
                        $stmt->bindValue("project_ref", $project_ref);
                        $stmt->bindValue("project_id", $project_id);
                        $stmt->bindValue("name", $ei_ticket->getElementsByTagName("name")->item(0)->nodeValue);
                        $stmt->bindValue("state", $ei_ticket->getElementsByTagName("state")->item(0)->nodeValue);
                        $stmt->bindValue("is_active", $ei_ticket->getElementsByTagName("is_active")->item(0)->nodeValue);
                        $stmt->bindValue("creator_id", $ei_ticket->getElementsByTagName("creator_id")->item(0)->nodeValue);  
                        $stmt->bindValue("creator_ref",$ei_ticket->getElementsByTagName("creator_ref")->item(0)->nodeValue); 
                        $stmt->bindValue("created_at", $ei_ticket->getElementsByTagName("created_at")->item(0)->nodeValue);  
                        $stmt->bindValue("updated_at",$ei_ticket->getElementsByTagName("updated_at")->item(0)->nodeValue); 
                        $stmt->execute(array());   
                    }
                } 
                return 1;
            }
            return null;
        }
    }
    
    /* Suppression des tickets d'un projet donné */
    public function deleteProjectTickets($project_id, $project_ref , Doctrine_Connection $conn = null){ 
        if ($conn == null)  $conn = Doctrine_Manager::connection();
            $conn->getTable('EiTicket')->createQuery('t')
                ->delete()
                ->where('t.project_id=? And t.project_ref=?',
                        array($project_id,$project_ref)) 
                ->execute(); 
    }
    /* Migration d'un scénario sur un profil donnée */
    public function migrateBugScenario(EiTicket $ei_package,EiProjet $ei_project,EiProfil $ei_profile, EiScenario $ei_scenario,sfGuardUser $guard , Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        /* On recherche la version du scénario correspondant au package */ 
        $ei_version=$ei_scenario->findVersionForPackage($ei_package,$conn); 
        if($ei_version==null):
            return false;
        else:
            $ei_version=$ei_version->getFirst();
        endif;
//        On crée le lien entre la version du scénario et la version concernée
        $stmt = $conn->prepare("INSERT INTO ei_profil_scenario (profile_id, profile_ref, ei_scenario_id,ei_version_id,created_at,updated_at) "
                            ."VALUES (:profile_id, :profile_ref, :ei_scenario_id,:ei_version_id,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE ei_version_id=".$ei_version->getId());  
                    $stmt->bindValue("profile_id", $ei_profile->getProfileId());
                    $stmt->bindValue("profile_ref", $ei_profile->getProfileRef());
                    $stmt->bindValue("ei_scenario_id",$ei_scenario->getId());
                    $stmt->bindValue("ei_version_id", $ei_version->getId());
                    $stmt->bindValue("created_at",  date('Y-m-d H:i:s'));
                    $stmt->bindValue("updated_at", date('Y-m-d H:i:s')); 
                    $stmt->execute(array());
        return true;
    }
    
    public function migrateBugFunction(EiTicket $ei_ticket,EiProjet $ei_project,EiProfil $ei_profile, KalFunction $ei_function,sfGuardUser $guard){
        $result_file = new DOMDocument(); 
        //Appel du webservice  
        $result_update = self::loadResultOfWebServiceForMigration(
                        MyFunction::getPrefixPath(null) .
                        "/serviceweb/bug/migrateOne.xml",array(
                            'project_id'=>      $ei_project->getProjectId(),
                            'project_ref' =>    $ei_project->getRefId(),
                            'ticket_id'=>       $ei_ticket->getTicketId(),
                            'ticket_ref' =>     $ei_ticket->getTicketRef(),
                            'profile_id'=>      $ei_profile->getProfileId(),
                            'profile_ref' =>    $ei_profile->getProfileRef(),
                            'function_id'=>      $ei_function->getFunctionId(),
                            'function_ref' =>    $ei_function->getFunctionRef(),
                            'guard_id' => $guard->getId())  ); 
                
        //Récupération du projet pour traitement
        if ($result_update == null) return null;
        $result_file->loadXML($result_update);
        $result_file->save('result_migrate_bug.xml'); /* sauvegarde du fichier pour vérifier le bon fonctionnement du web service */
        $result_item=$result_file->documentElement;
        if ($result_item->getElementsByTagName("error")->item(0)):
            return -1;
        endif;
        if ($result_item->getElementsByTagName("process_error")->item(0)):
            return 0;
        endif; 
        if ($result_item->getElementsByTagName("process_ok")->item(0)):
            return true;
        endif; 
    }
    /* Migration de plusieurs scénarios d'un coup */
    public function MigrateManyScenarios(EiProjet $ei_project,EiProfil $ei_profile,sfGuardUser $guard,$tab_scenarios,Doctrine_Connection $conn = null){ 
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        if($tab_scenarios!=null){
                $tab_scenarios= explode('|',$tab_scenarios);
                foreach($tab_scenarios as $item): 
                                      $new_tab[]='('.$item.')'; 
                endforeach;
                foreach($new_tab as $tab): 
                                $finalTab[] = implode(',', explode(',', $tab)) ; //Restructuration de l'item sous forme (function_id,function_ref)
                endforeach;  

            $scenario_packages=$conn->execute("Select ei_scenario_id,package_id,package_ref,ei_version_id from ei_scenario_package where 
                    (ei_scenario_id,package_id,package_ref) IN (".implode(",", $finalTab).")");
            
            if(count($scenario_packages)>0):
                $stmt = $conn->prepare("INSERT INTO ei_profil_scenario (profile_id, profile_ref, ei_scenario_id,ei_version_id,created_at,updated_at) "
                            ."VALUES (:profile_id, :profile_ref, :ei_scenario_id,:ei_version_id,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE ei_version_id=:ei_version_id,created_at=created_at");
                foreach($scenario_packages as $item):
                    $stmt->bindValue("profile_id", $ei_profile->getProfileId());
                    $stmt->bindValue("profile_ref", $ei_profile->getProfileRef());
                    $stmt->bindValue("ei_scenario_id",$item['ei_scenario_id']);
                    $stmt->bindValue("ei_version_id", $item['ei_version_id']);
                    $stmt->bindValue("created_at",  date('Y-m-d H:i:s'));
                    $stmt->bindValue("updated_at", date('Y-m-d H:i:s')); 
                    $stmt->execute(array());
                endforeach;
            endif; 
            return true; 
        }
        return false;
    }
    
    public function MigrateManyFunctions(EiProjet $ei_project,EiProfil $ei_profile,sfGuardUser $guard,$tab_functions){
        $result_file = new DOMDocument(); 
        //Appel du webservice  
        $result_update = self::loadResultOfWebServiceForMigration(
                        MyFunction::getPrefixPath(null) .
                        "/serviceweb/bug/migrateMany.xml",array(
                            'project_id'=>      $ei_project->getProjectId(),
                            'project_ref' =>    $ei_project->getRefId(), 
                            'profile_id'=>      $ei_profile->getProfileId(),
                            'profile_ref' =>    $ei_profile->getProfileRef(), 
                            'guard_id' => $guard->getId(),
                            'tab_functions' => $tab_functions)  ); 
                
        //Récupération du projet pour traitement
        if ($result_update == null) return null;
        $result_file->loadXML($result_update);
        $result_file->save('result_migrate_many_bug.xml'); /* sauvegarde du fichier pour vérifier le bon fonctionnement du web service */
        $result_item=$result_file->documentElement; 
        if ($result_item->getElementsByTagName("process_error")->item(0)):
            return 0;
        endif; 
        if ($result_item->getElementsByTagName("process_ok")->item(0)):
            return true;
        endif; 
        
    }
    //Récupération des relations script-profil associées au ticket
    public function getAssociatedProfiles($ticket_id,$ticket_ref,Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q =
                "SELECT s.function_id, s.function_ref, s.script_id, sv.profile_id, sv.profile_ref
        FROM `ei_script_version` AS sv
        LEFT JOIN ei_script AS s ON s.script_id = sv.script_id
        LEFT JOIN ei_ticket AS t ON t.ticket_ref = s.ticket_ref
        AND t.ticket_id = s.ticket_id
        WHERE t.ticket_id =" . $ticket_id . "
        AND t.ticket_ref =" . $ticket_ref . "
        ORDER BY function_id, function_ref ASC
            ";
        return $conn->fetchAll($q);
    }
    //Récupération des relations version-profil associées au package
    public function getAssociatedProfilesForScenario($package_id,$package_ref,EiScenario $ei_scenario=null,Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q =
                "SELECT v.ei_scenario_id,ps.ei_version_id, ps.profile_id, ps.profile_ref
        FROM `ei_profil_scenario` AS ps
        LEFT JOIN ei_version AS v ON v.id = ps.ei_version_id
        LEFT JOIN ei_scenario_package AS sp  ON sp.ei_version_id=v.id 
        WHERE sp.package_id =" . $package_id . "
        AND sp.package_ref =" . $package_ref ;
        if($ei_scenario!=null):
            $q.=" And v.ei_scenario_id=".$ei_scenario->getId();
        endif;
        $q.=" ORDER BY v.ei_scenario_id ASC";
        return $conn->fetchAll($q);
    }
    
    //Récupération des relations script-profil associées au ticket
    public function getAssociatedProfilesForDelivery( $implicatedTikets,Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q =
                "SELECT s.function_id, s.function_ref, s.script_id, sv.profile_id, sv.profile_ref
        FROM `ei_script_version` AS sv
        LEFT JOIN ei_script AS s ON s.script_id = sv.script_id
        LEFT JOIN ei_ticket AS t ON t.ticket_ref = s.ticket_ref
        AND t.ticket_id = s.ticket_id
        WHERE (t.ticket_id,t.ticket_ref) IN " . $implicatedTikets. " 
        ORDER BY function_id, function_ref ASC
            ";
        return $conn->fetchAll($q);
    }
    //Récupération des relations version(scenario)-profil associées au ticket
    public function getVersionsScenarioProfiles( $implicatedTikets,Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        if($implicatedTikets==null ) return array();
        $q =
                "SELECT ps.profile_id, ps.profile_ref, ps.ei_scenario_id,ps.ei_version_id
        FROM `ei_profil_scenario` AS ps
        LEFT JOIN ei_scenario_package AS sp ON ps.ei_scenario_id=sp.ei_scenario_id And ps.ei_version_id=sp.ei_version_id 
        WHERE (sp.package_id,sp.package_ref) IN " . $implicatedTikets. "  
            ";
        return $conn->fetchAll($q);
    }
    /*
     * Traitement de l'envoi en post du fichier json pour mise à jour des commandes
     */
    public static function loadResultOfWebServiceForMigration($url,$params){
        if ($url == null)
            return null;
        $cobj = curl_init($url); // créer une nouvelle session cURL
        if ($cobj) { 
            curl_setopt_array($cobj, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS =>  $params
            )); 
            
            $xml = curl_exec($cobj); //execution de la requete curl 
            curl_close($cobj); //liberation des ressources 
            $xmlDoc = new DOMDocument("1.0", "utf-8");
            $xmlDoc->loadXML($xml);
            return $xmlDoc->saveXML();
        }
        return null;  
    }
    //Rechargement d'un ticket
    public function insertJsonFunction($arraytab, Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        //Si l'id du noeud ou du projet n'est pas renseigné 
        if(!isset($arraytab['t_id'])  || !isset($arraytab['p_id']) || !isset($arraytab['p_ref']) ) return null;
        $stmt = $conn->prepare("INSERT INTO ei_ticket (ticket_id, ticket_ref,project_ref,project_id,name,state,is_active,creator_id,creator_ref,created_at,updated_at) "
                            ."VALUES (:ticket_id, :ticket_ref,:project_ref,:project_id,:name,:state,:is_active,:creator_id,:creator_ref,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE ticket_id=ticket_id,ticket_ref=ticket_ref, name=:name,creator_id=:creator_id,creator_ref=:creator_ref,created_at=:created_at,updated_at=:updated_at");
        
        $stmt->bindValue("ticket_id", $arraytab['t_id']);
                        $stmt->bindValue("ticket_ref",  $arraytab['t_ref']);
                        $stmt->bindValue("project_ref",  $arraytab['p_ref']);
                        $stmt->bindValue("project_id",  $arraytab['p_id']);
                        $stmt->bindValue("name",  $arraytab['t_name']);
                        $stmt->bindValue("description",  $arraytab['t_desc']);
                        $stmt->bindValue("state",  $arraytab['t_state']);
                        $stmt->bindValue("is_active",  $arraytab['t_act']);
                        $stmt->bindValue("creator_id",  $arraytab['t_cid']);  
                        $stmt->bindValue("creator_ref", $arraytab['t_cref']); 
                        $stmt->bindValue("created_at", $arraytab['t_creat']); 
                        $stmt->bindValue("updated_at", $arraytab['t_updat']); 
                        $stmt->execute(array()); 
                    return 1;
    }
    
     
}