<?php

/**
 * EiSubjectStateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiSubjectStateTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiSubjectStateTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiSubjectState');
    }
    //Récupération de la requête pour les statuts de sujet d'un projet
    public function getSubjectStateForProjectQuery($project_id, $project_ref,Doctrine_Connection $conn=null){
       if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $conn->createQuery()->from('EiSubjectState')
             ->where('project_id= ? And project_ref=? ',
                     array($project_id,$project_ref)) ;
    }
    //Récupération des statuts de projet différent de "Close"
    public function getUnCloseStateForProject ($project_id, $project_ref,Doctrine_Connection $conn=null){
       if($conn==null) $conn = Doctrine_Manager::connection(); 
        $q=$this->getSubjectStateForProjectQuery($project_id, $project_ref,$conn);
          return $q->andWhere('name <> "Close"');
    }
    
    //Création des statuts de sujet par défaut  s'ils n'existent pas encore
    public function createDefaultSubjectStates($project_id, $project_ref,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        
        $states=$this->getSubjectStateForProjectQuery($project_id, $project_ref,$conn)->execute();
        
        if(count($states)==0){  // Alors aucun statut n'existe et dans ce cas on crée ceux par défaut
          $this->createState($project_id, $project_ref, 'New','#53567C',1,1,1,0,$conn) ;
          $this->createState($project_id, $project_ref, 'Analysed','#DEA33F',1,1,1,0,$conn) ; //Nouveau statut
          $this->createState($project_id, $project_ref, 'In development','#DFBE37',1,1,1,0,$conn) ; //Anciennement "In dev"
          $this->createState($project_id, $project_ref, 'In test','#36a9e1',1,1,1,0,$conn) ;
          $this->createState($project_id, $project_ref, 'Need information','#6E719D',1,1,1,0,$conn) ; //Anciennement "In dispute"
          $this->createState($project_id, $project_ref, 'Test valid','#58A155',0,1,0,0,$conn) ; //Anciennement "Valid"
          $this->createState($project_id, $project_ref, 'Test invalid','#D8473D',1,1,1,0,$conn) ; //Anciennement "Invalid"
          $this->createState($project_id, $project_ref, 'Rejected','#B9B5AF',0,0,0,1,$conn) ; //Nouveau statut
          $this->createState($project_id, $project_ref, 'Closed','#838483',0,0,0,1,$conn) ; //Anciennement "Close"
          return 1;
        }
       return 0;     
       
    }
    
    //Création d'un statut de sujet pour un projet donné
    public function createState($project_id, $project_ref,$state,$color_code,$display_in_home_page,$display_in_search,$display_in_todolist,$close_del_state,$conn){
        if($conn==null) $conn = Doctrine_Manager::connection();
        $conn->insert($this->getInstance(),
                array('name'=>$state,
                      'color_code' => $color_code,
                      'display_in_home_page'=>$display_in_home_page,
                      'display_in_search'=>$display_in_search,
                      'display_in_todolist'=>$display_in_todolist,
                      'close_del_state'=>$close_del_state,
                      'project_id' =>$project_id,
                      'project_ref' => $project_ref,
                      'created_at'=> date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')));
    }
    /* Récupération de la liste déroulante des statuts de sujet pour la recherche */
    public function getSubjectStateForSearchBox(EiProjet $ei_project,  Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();  
        //Récupération des status
         return $this->getSubjectStateForProjectQuery($ei_project->getProjectId(), $ei_project->getRefId(),$conn)->execute(); 
    }
    
   
}