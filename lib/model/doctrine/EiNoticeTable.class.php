<?php

/**
 * EiNoticeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiNoticeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiNoticeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiNotice');
    } 
     
     
    
    
    //Rechargement des éléments de type EiNotice pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();

        //Création de la collection d'objet EiNotice à ajouter
        $collection = new Doctrine_Collection("EiNotice");
        //Supréssion des notices n'existant plus
        $this->deleteNotFoundNotice();
        
        $items = $projets->getElementsByTagName("ei_notices");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_notices = $items->item(0)->getElementsByTagName("ei_notice");


            if($ei_notices->length > 0){
                foreach ($ei_notices as $ei_notice) {

                    $notice_id = $ei_notice->getAttribute("notice_id");
                    $notice_ref = $ei_notice->getAttribute("notice_ref");
                    //recherche du profil en base
                    if ($notice_id != null && $notice_ref != null) {
                        $q = Doctrine_Core::getTable('EiNotice')->findOneByNoticeIdAndNoticeRef($notice_id, $notice_ref);

                        if ($q && $q != null) {//si l'element existe , on fait une mise à jour 
                            $q->setName($ei_notice->getElementsByTagName("name")->item(0)->nodeValue);
                            $q->save($conn);
                        } else {//l'élément n'existe pas encore, et dans ce cas on le crée
                            $new_ei_notice = new EiNotice();
                            $new_ei_notice->setNoticeId($notice_id);
                            $new_ei_notice->setNoticeRef($notice_ref);
                            $new_ei_notice->setFunctionId($ei_notice->getElementsByTagName("function_id")->item(0)->nodeValue);
                            $new_ei_notice->setFunctionRef($ei_notice->getElementsByTagName("function_ref")->item(0)->nodeValue);
                            $new_ei_notice->setName($ei_notice->getElementsByTagName("name")->item(0)->nodeValue);

                            $collection->add($new_ei_notice);
                        }
                    }
                }
                if ($collection->getFirst())
                    $collection->save($conn); //Sauvegarde de la collection
                
                return 1;
            }
            return null;
        }
    }
    
    
    //Suppression des notices inexistant sur la plate forme de rédaction des scripts (script.kalifast.com)
    public function deleteNotFoundNotice( Doctrine_Connection $conn=null){
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        
        
        //Supression des profils de notice concernés
        $q1="
            delete FROM `ei_notice_profil` 
            WHERE  
            (notice_id, notice_ref)
            IN 
                (select notice_id, notice_ref FROM `ei_notice` 
                WHERE  
                (function_id,function_ref) 
                 In 
                    (SELECT function_id , function_ref from script_ei_function where is_active = 0 ) 
                )    ";
        $conn->execute($q1);
        
        //Supression des versions de notice concernées
        $q2="
            delete FROM `ei_version_notice` 
            WHERE  
            (notice_id, notice_ref) 
            IN 
                (select notice_id, notice_ref FROM `ei_notice` 
                WHERE  
                (function_id,function_ref) 
                 In 
                    (SELECT function_id , function_ref from script_ei_function where is_active = 0 ) 
                )    ";
        $conn->execute($q2);
         
        
        //Supression de la notice en soit
        $q3="
            delete FROM `ei_notice` 
            WHERE  
            (function_id,function_ref) 
             In 
            (SELECT function_id , function_ref from script_ei_function where is_active = 0 ) ";
        $conn->execute($q3);
    }
     
    public function getNoticePage($ei_version,$ei_project,$ei_profile,$lang ,$tab_notice=null){
        
        $this->tab_notice=$tab_notice; 
        
        if($this->tab_notice==null) $this->tab_notice=array() ; 
        
        if($ei_version==null) return $this->tab_notice;
        
        $objects=$ei_version->getOrderedContent();
        
        
        
        if(count($objects)>0){
            
            foreach ($objects as $object):
                if($object instanceof EiFonction) : 
                    $noticeVersion=$object->getVersionNoticeByProfil($ei_profile,$ei_project,$lang);  
                    $this->tab_notice[$object->__toString().'_'.$object->getId()]=array($object->getId() => $noticeVersion);  
                    else:  
                    $this->getNoticePage($object,$ei_project,$ei_profile,$lang ,$this->tab_notice);
                endif;
            endforeach;
             
        }
        
        return $this->tab_notice;
    }
    public function insertJsonFunction($arraytab,  Doctrine_Connection $conn = null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        //Si l'id du noeud ou du projet n'est pas renseigné 
        if(!isset($arraytab['n_id'])  || !isset($arraytab['n_ref'])  || !isset($arraytab['f_id']) || !isset($arraytab['f_ref']) ) return null;
        $stmt = $conn->prepare("INSERT INTO ei_notice (notice_id, notice_ref, function_id,function_ref,name,created_at,updated_at) "
                            ."VALUES (:notice_id, :notice_ref, :function_id,:function_ref,:name,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE notice_id=notice_id,notice_ref=notice_ref,name=:name,function_id=:function_id,function_ref=:function_ref,created_at=:created_at,updated_at=:updated_at"); 
            
                    $stmt->bindValue("notice_id", $arraytab['n_id']);
                    $stmt->bindValue("notice_ref", $arraytab['n_ref']); 
                    $stmt->bindValue("function_id", $arraytab['f_id']);
                    $stmt->bindValue("function_ref", $arraytab['f_ref']);
                    $stmt->bindValue("name", $arraytab['n_name']); 
                    $stmt->bindValue("created_at", $arraytab['n_creat']);
                    $stmt->bindValue("updated_at", $arraytab['n_updat']);
                    $stmt->execute(array());
                    return 1;
    }
}

