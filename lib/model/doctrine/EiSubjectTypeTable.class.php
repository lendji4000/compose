<?php

/**
 * EiSubjectTypeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiSubjectTypeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiSubjectTypeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiSubjectType');
    } 
    //Récupération de l'id d'un type de sujet (EiSubjectType) en fonction du nom et du projet
    public function getSubjectTypeId(EiProjet $ei_project, $name,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $type=Doctrine_Core::getTable('EiSubjectType')->findOneByProjectIdAndProjectRefAndName(
                $ei_project->getProjectId(),$ei_project->getRefId(),$name );
        if($type!=null) return $type->getId(); 
    }
    //Récupération de la requête pour les types de sujet d'un projet
    public function getSubjectTypeForProjectQuery($project_id, $project_ref,Doctrine_Connection $conn=null){
       if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $conn->createQuery()->from('EiSubjectType')
             ->where('project_id= ? And project_ref=? ',
                     array($project_id,$project_ref)) ;
    }
    
    //Création des types  de sujet par défaut  s'ils n'existent pas encore
    public function createDefaultSubjectTypes($project_id, $project_ref,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        
        $subjectTypes=$this->getSubjectTypeForProjectQuery($project_id, $project_ref,$conn)->execute();
        
        if(count($subjectTypes)==0){  // Alors aucun type n'existe et dans ce cas on crée ceux par défaut
          $this->createSubjectType($project_id, $project_ref, 'Defect',$conn) ;
          $this->createSubjectType($project_id, $project_ref, 'Service Request',$conn) ;
          $this->createSubjectType($project_id, $project_ref, 'Enhancement',$conn) ;
          $this->createSubjectType($project_id, $project_ref, 'Kalifast',$conn) ;
          return 1;
        }
       return 0;     
    }
    
    //Création d'un type de sujet pour un projet donné
    public function createSubjectType($project_id, $project_ref,$type,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection();
        $conn->insert($this->getInstance(),
                array('name'=>$type,
                      'project_id' =>$project_id,
                      'project_ref' => $project_ref,
                      'created_at'=> date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')));
    }
    
    /* Récupération de la liste déroulante des types de sujet pour la recherche */
    public function getSubjectTypeForSearchBox(EiProjet $ei_project,  Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        //On crèe les types de sujet par défaut du projet si ces dernières n'existent pas encore
//        $this->createDefaultSubjectTypes($ei_project->getProjectId(), $ei_project->getRefId(), $conn);
        //On récupère les sujets du projet
        return $this->getSubjectTypeForProjectQuery($ei_project->getProjectId(), $ei_project->getRefId(),$conn)->execute(); 
    }
}