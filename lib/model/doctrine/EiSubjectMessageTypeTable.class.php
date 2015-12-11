<?php

/**
 * EiSubjectMessageTypeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiSubjectMessageTypeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiSubjectMessageTypeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiSubjectMessageType');
    }
    //Récupération de la requête pour les types  de message  de sujet d'un projet
    public function getMessageTypeForProjectQuery($project_id, $project_ref,Doctrine_Connection $conn=null){
       if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $conn->createQuery()->from('EiSubjectMessageType')
             ->where('project_id= ? And project_ref=? ',
                     array($project_id,$project_ref)) ;
    }
    //Création des types  de message par défaut
    public function createDefaultMessageTypes($project_id, $project_ref,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        
        $subjectMessageTypes=$this->getMessageTypeForProjectQuery($project_id, $project_ref,$conn)->execute();
        
        if(count($subjectMessageTypes)==0){  // Alors aucun type n'existe et dans ce cas on crée ceux par défaut
          $this->createSubjectMessageType($project_id, $project_ref, 'Question',$conn) ;
          $this->createSubjectMessageType($project_id, $project_ref, 'Answer',$conn) ; 
          return 1;
        }
       return 0;     
    }
    
     //Création des types  de message
    public function createSubjectMessageType($project_id, $project_ref,$name,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection();
        $conn->insert($this->getInstance(),
                array('name'=>$name,
                      'project_id' =>$project_id,
                      'project_ref' => $project_ref,
                      'created_at'=> date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')));
    }
}