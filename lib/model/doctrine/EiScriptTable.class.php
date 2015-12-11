<?php

/**
 * EiScriptTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiScriptTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object EiScriptTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('EiScript');
    }
 
    

    //Rechargement des éléments de type EiScript pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();

        //Création de la collection d'objet EiScript à ajouter 
           $stmt = $conn->prepare("INSERT INTO ei_script (script_id, ticket_id, ticket_ref,function_id,function_ref,description,remark) "
                            ."VALUES (:script_id, :ticket_id, :ticket_ref,:function_id,:function_ref,:description,:remark) "
                            ."ON DUPLICATE KEY UPDATE script_id=script_id,ticket_id=:ticket_id,ticket_ref=:ticket_ref,function_id=:function_id,function_ref=:function_ref"); 
               
        $items = $projets->getElementsByTagName("ei_scripts");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_scripts = $items->item(0)->getElementsByTagName("ei_script"); 
            $date=date("Y-m-d H:i:s");

            if($ei_scripts->length > 0){
                foreach ($ei_scripts as $ei_script) {

                    $script_id = $ei_script->getAttribute("script_id");
                    $stmt->bindValue("script_id", $script_id);
                    $stmt->bindValue("ticket_id", $ei_script->getElementsByTagName("ticket_id")->item(0)->nodeValue);
                    $stmt->bindValue("ticket_ref", $ei_script->getElementsByTagName("ticket_ref")->item(0)->nodeValue);
                    $stmt->bindValue("function_id", $ei_script->getElementsByTagName("function_id")->item(0)->nodeValue);
                    $stmt->bindValue("function_ref", $ei_script->getElementsByTagName("function_ref")->item(0)->nodeValue);
                    $stmt->bindValue("description", $ei_script->getElementsByTagName("description")->item(0)->nodeValue);
                    $stmt->bindValue("remark", $ei_script->getElementsByTagName("remark")->item(0)->nodeValue);
                    $stmt->execute(array());
                }
                    //On procède par sql brute pour éviter des allocations mémoires lourdes au serveur
                //if($collection->getFirst()) $collection->save($conn); //Sauvegarde de la collection
                $this->deleteNotFoundScript();
                return 1;
            }
             return null;
        }
    }
    
    
    //Suppression des scripts inexistant sur la plate forme de rédaction des scripts (script.kalifast.com)
    public function deleteNotFoundScript( Doctrine_Connection $conn=null){
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q="
            delete FROM `ei_script` 
            WHERE 
             script_id 
             Not In ( select  script_id from script_ei_script where 1)
            And 

            (function_id,function_ref) 
             In 
            (SELECT function_id , function_ref from script_ei_function where 1 ) ";
        $conn->execute($q);
    }
    
    /*
     * Récupération d'un script pour un profil et une fonction 
     */
    
    public function getScriptObjectForProfile(KalFunction $function, EiProfil $profile){ 
        if($function==null || $profile==null ) return null; 
        $q= $this->getInstance()->createQuery('s')  
                ->where('KalFunction.function_id=s.function_id And KalFunction.function_ref=s.function_ref')
                ->AndWhere('KalFunction.function_id=? And KalFunction.function_ref=? ', Array($function->getFunctionId(),$function->getFunctionRef()))
                ->AndWhere('EiScriptVersion.script_id=s.script_id And EiScriptVersion.profile_id=? And EiScriptVersion.profile_ref=?',
                        Array($profile->getProfileId(),$profile->getProfileRef()))
                ->execute();
        if(count($q)==1) return $q->getFirst();
          
        return null;
    }
    
    public function getScriptsForProfile(KalFunction $function, EiProfil $profile){
        if($function==null|| $profile==null ) return null;
        return $this->getInstance()->createQuery('s') 
                ->select('s.script_id')
                ->where('KalFunction.function_id=s.function_id And KalFunction.function_ref=s.function_ref')
                ->AndWhere('KalFunction.function_id=? And KalFunction.function_ref=? ', Array($function->getFunctionId(),$function->getFunctionRef()))
                ->AndWhere('EiScriptVersion.script_id=s.script_id And EiScriptVersion.profile_id=? And EiScriptVersion.profile_ref=?',
                        Array($profile->getProfileId(),$profile->getProfileRef()))
                ->AndWhere('EiFunctionHasCommande.script_id=s.script_id')
                ->fetchArray();
    }
    
    public function insertJsonFunction($arraytab,  Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        //Si l'id du noeud ou du projet n'est pas renseigné 
        if(!isset($arraytab['s_id'])  || !isset($arraytab['f_id']) || !isset($arraytab['f_ref']) ) return null;
        $stmt = $conn->prepare("INSERT INTO ei_script (script_id, ticket_id, ticket_ref,function_id,function_ref,description,remark,created_at,updated_at) "
                            ."VALUES (:script_id, :ticket_id, :ticket_ref,:function_id,:function_ref,:description,:remark,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE script_id=script_id,ticket_id=:ticket_id,ticket_ref=:ticket_ref,function_id=:function_id,function_ref=:function_ref,created_at=:created_at,updated_at=:updated_at"); 
            
                    $stmt->bindValue("script_id", $arraytab['s_id']);
                    $stmt->bindValue("ticket_id", $arraytab['s_tid']);
                    $stmt->bindValue("ticket_ref", $arraytab['s_tref']);
                    $stmt->bindValue("function_id", $arraytab['f_id']);
                    $stmt->bindValue("function_ref", $arraytab['f_ref']);
                    $stmt->bindValue("description", $arraytab['s_desc']);
                    $stmt->bindValue("remark", $arraytab['s_remark']);
                    $stmt->bindValue("created_at", $arraytab['s_creat']);
                    $stmt->bindValue("updated_at", $arraytab['s_updat']);
                    $stmt->execute(array());
                    return 1;
    }
}