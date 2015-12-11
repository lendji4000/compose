<?php

class myUser extends sfGuardSecurityUser
{ 
    public function getEiUser(){
        return $this->getGuardUser()->getEiUser();
    }
    public function getDefaultPackage($project_id,$project_ref){ 
        $ei_user=$this->getEiUser();
        $defPackRelation=Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
               $project_id,$project_ref,$ei_user->getUserId(),$ei_user->getRefId() );
        if($defPackRelation!=null):
            return  Doctrine_Core::getTable("EiTicket")->findOneByTicketRefAndTicketId(
                $defPackRelation->getTicketRef(),$defPackRelation->getTicketId());
        endif;
        return null;
    }
    /*Récupération de l'intervention par défaut d'un utiliateur */
    public function getDefaultIntervention($params){
        if( !(isset($params["project_id"]) && isset($params["project_ref"]) && isset($params["profile_id"]) && isset($params["profile_ref"]) &&  isset($params["profile_name"]))) return null;
        $ei_project=Doctrine_Core::getTable("EiProjet")->findOneByProjectIdAndRefId($params['project_id'],$params['project_ref']);
        /* Recherche de l'intervention par défaut */  
        $defInt= $this->getEiUser()->getDefaultIntervention($ei_project);  
        if($defInt!=null): 
            return $defInt;
        endif;
         return null;
    }
}