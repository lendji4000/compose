<?php

/**
 * EiScriptVersionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiScriptVersionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiScriptVersionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiScriptVersion');
    }
    //Rechargement des éléments de type EiScriptVersion pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();

        //Création de la collection d'objet EiScriptVersion à ajouter
        $collection = new Doctrine_Collection("EiScriptVersion");
        $stmt = $conn->prepare("INSERT INTO ei_script_version (script_id, profile_id, profile_ref,project_ref,project_id,num_version) "
                            ."VALUES (:script_id, :profile_id, :profile_ref,:project_ref,:project_id,:num_version) "
                            ."ON DUPLICATE KEY UPDATE script_id=script_id"); 
        $items = $projets->getElementsByTagName("ei_script_versions");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_script_versions = $items->item(0)->getElementsByTagName("ei_script_version");
            $values="";
            $date=date("Y-m-d H:i:s");
            if($ei_script_versions->length > 0){
                foreach ($ei_script_versions as $ei_script_version) {

                    $script_id = $ei_script_version->getAttribute("script_id");
                    $profile_id = $ei_script_version->getAttribute("profile_id");
                    $profile_ref = $ei_script_version->getAttribute("profile_ref"); 
                    $stmt->bindValue("script_id", $script_id);
                    $stmt->bindValue("profile_id", $profile_id);
                    $stmt->bindValue("profile_ref", $profile_ref);
                    $stmt->bindValue("project_ref", $project_ref);
                    $stmt->bindValue("project_id", $project_id);
                    $stmt->bindValue("num_version", $ei_script_version->getElementsByTagName("num_version")->item(0)->nodeValue); 
                    $stmt->execute(array()); 
                } 
                $this->deleteNotFoundScriptProfile($conn);
                return 1;
            }
            return null;
        }
    }
    
    //Suppression des relations script- profil inexistant sur la plate forme de rédaction des scripts (script.kalifast.com)
    public function deleteNotFoundScriptProfile( Doctrine_Connection $conn=null){
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        $q="
            delete FROM `ei_script_version` 
            WHERE 
             (script_id,profile_id,profile_ref) 
             Not In 
             ( select  script_id,profile_id,profile_ref from script_ei_script_version 
             where script_id in (select script_id from script_ei_script where 1)
             )
             AND script_id 
              In ( select  script_id from script_ei_script where 1)
             
             ";
        $conn->execute($q);
    }
    
    public function insertJsonFunction($arraytab, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();  
        if (!isset($arraytab['s_id']) || !isset($arraytab['sv_pid']) || !isset($arraytab['sv_pref']) || !isset($arraytab['p_id']) || !isset($arraytab['p_ref']))
            return null; 
        $stmt = $conn->prepare("INSERT INTO ei_script_version (script_id, profile_id, profile_ref,project_ref,project_id,num_version,created_at,updated_at) "
                            ."VALUES (:script_id, :profile_id, :profile_ref,:project_ref,:project_id,:num_version,:created_at,:updated_at) "
                            ."ON DUPLICATE KEY UPDATE script_id=script_id,created_at=:created_at,updated_at=:updated_at"); 
        $stmt->bindValue("script_id", $arraytab['s_id']);
                    $stmt->bindValue("profile_id", $arraytab['sv_pid']);
                    $stmt->bindValue("profile_ref", $arraytab['sv_pref']);
                    $stmt->bindValue("project_ref", $arraytab['p_ref']);
                    $stmt->bindValue("project_id", $arraytab['p_id']);
                    $stmt->bindValue("num_version", $arraytab['sv_numv']); 
                    $stmt->bindValue("created_at", $arraytab['sv_creat']); 
                    $stmt->bindValue("updated_at", $arraytab['sv_updat']); 
                    $stmt->execute(array());
        
        /* On crée des ei_params */ 
        /*$ei_functions=Doctrine_Core::getTable('EiFonction')->findByFunctionIdAndFunctionRef($arraytab['f_id'],$arraytab['f_ref']);
        //Si on trouve des fonctions utilisant le nouveau paramètre, alors on crée les paramètres asscociés sur kalifast
        if(count($ei_functions)>0):
            foreach($ei_functions as $ei_function){
                $ei_param=new EiParam();
                $ei_param->setIdFonction($ei_function->getId());
                $ei_param->setParamId($p->getParamId());
                $ei_param->setValeur($p->getDefaultValue());
                $ei_param->save($conn);
            }
        endif;*/
        return 1;
    }
    
    
}
 