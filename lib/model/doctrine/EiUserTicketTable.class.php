<?php

/**
 * EiUserTicketTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiUserTicketTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiUserTicketTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiUserTicket');
    }
    //Rechargement des éléments de type EiUserTicket pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
         
        
        $items = $projets->getElementsByTagName("ei_user_tickets");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_user_tickets = $items->item(0)->getElementsByTagName("ei_user_ticket");
            $stmt = $conn->prepare("INSERT INTO ei_user_ticket (ticket_id, ticket_ref,user_ref,user_id,state) "
                            ."VALUES (:ticket_id, :ticket_ref,:user_ref,:user_id,:state) "
                            ."ON DUPLICATE KEY UPDATE ticket_id=ticket_id,ticket_ref=ticket_ref,user_ref=user_ref,user_id=user_id");

            if ($ei_user_tickets != null) {
                foreach ($ei_user_tickets as $ei_user_ticket) {

                    $ticket_id = $ei_user_ticket->getAttribute("ticket_id") ;
                    $ticket_ref = $ei_user_ticket->getAttribute("ticket_ref") ;
                    $user_id = $ei_user_ticket->getAttribute("user_id") ;
                    $user_ref = $ei_user_ticket->getAttribute("user_ref") ;
                    //recherche du profil en base
                    if ($ticket_id != null && $ticket_ref!=null && $user_id!=null && $user_ref !=null) { 
                        //l'élément n'existe pas encore, et dans ce cas on le crée
                        $stmt->bindValue("ticket_id", $ticket_id);
                        $stmt->bindValue("ticket_ref", $ticket_ref);
                        $stmt->bindValue("user_ref", $user_ref);
                        $stmt->bindValue("user_id", $user_id); 
                        $stmt->bindValue("state", $ei_user_ticket->getElementsByTagName("state")->item(0)->nodeValue);
                        $stmt->execute(array());   
                    }
                } 
                return 1;
            }
            return null;
        }
    }
    
    //Rechargement d'un ticket
    public function insertJsonFunction($arraytab, Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        //Si l'id du noeud ou du projet n'est pas renseigné 
        if(!isset($arraytab['ut_uid'])  || !isset($arraytab['p_id']) || !isset($arraytab['p_ref']) ) return null;
        $stmt = $conn->prepare("INSERT INTO ei_user_ticket (ticket_id, ticket_ref,user_ref,user_id,state,created_at,updated_at) "
                            ."VALUES (:ticket_id, :ticket_ref,:user_ref,:user_id,:state,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE ticket_id=ticket_id,ticket_ref=ticket_ref,user_ref=user_ref,user_id=user_id,created_at=:created_at,updated_at=:updated_at");
         
        $stmt->bindValue("ticket_id", $arraytab['t_id']);
                        $stmt->bindValue("ticket_ref", $arraytab['t_ref']);
                        $stmt->bindValue("user_ref", $arraytab['ut_uref']);
                        $stmt->bindValue("user_id", $arraytab['ut_uid']); 
                        $stmt->bindValue("state", $arraytab['ut_state']);
                        $stmt->bindValue("created_at", $arraytab['ut_creat']); 
                        $stmt->bindValue("updated_at", $arraytab['ut_updat']); 
                        $stmt->execute(array());    
                    return 1;
    }
    
    /* Suppression des liaisons user - ticket pour un projet donné */
    public function deleteUserTicketForProject($project_id, $project_ref , Doctrine_Connection $conn = null){
         if ($conn == null)  $conn = Doctrine_Manager::connection(); 
    }
}