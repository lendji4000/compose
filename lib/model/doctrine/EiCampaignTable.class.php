<?php

/**
 * EiCampaignTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiCampaignTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiCampaignTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiCampaign');
    }
    //Recherche d'une campagne par son nom ou son Id
    public function searchCampaignByIdOrName($nameOrId,$project_id,$project_ref,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        if($nameOrId==null) return null;
       $q= $conn->createQuery()->from('EiCampaign d') 
             ->where('project_id= ? And project_ref=? ',array($project_id,$project_ref))
             ->andWhere('d.id= ? OR d.name LIKE ? ', array($nameOrId , '%'.$nameOrId.'%'))
             ->execute(); 
       if(count($q)>0):
           return $q->getFirst();
       endif;
       return null;
    }
    
    //Récupération du noeud racine du graphe d'une campagne
    public function getRootCampaign(EiCampaign $ei_campaign){
        return Doctrine_Core::getTable('EiCampaignGraph')->getRootCampaign($ei_campaign);
    }
    //Récupération de la requête pour la liste des campagnes de tests  
    public function getCampaigns(EiCampaign $ei_current_campaign=null,Doctrine_Connection $conn=null ){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
      $q= $conn->createQuery()->from(' EiCampaign c')
             ->leftJoin('c.sfGuardUser a')
             ->leftJoin('c.CampaignDeliveries dc') 
             ->leftJoin('c.Campaigns sc')
             ->leftJoin('sc.EiSubject as s')
             ->leftJoin('dc.EiDelivery d') 
             ->leftJoin('c.campaignFunctions fctc') ;
      //Si on est en édition de campagne , alors on récupère les flags par rapport à la campagne courante
      if($ei_current_campaign!=null)
          $q=$q->leftJoin('c.flagCampaigns f')
               ->leftJoin('f.flagCampaign fc');
      return $q;
    }
    /* Récupération simple de toutes les campagnes du projet */
    public function getAllProjectCampaigns($project_id,$project_ref,  Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $conn->fetchAll("select * from ei_campaign where project_id=".$project_id." And project_ref=".$project_ref);
    }
    /*Récupération de la requête pour la liste des campagnes de tests d'un projet.
     * Ces campagnes ne sont ni dans les sujets , ni dans les livraisons
     */
    public function getProjectCampaignsList($project_id,$project_ref,EiCampaign $ei_current_campaign=null,Doctrine_Connection $conn=null ){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $q=$this->getCampaigns($ei_current_campaign,$conn);
        
       $q= $q->where('project_id= ? And project_ref=? ',array($project_id,$project_ref))
                 ->andWhere('sc.subject_id IS NULL')
                 ->andWhere('dc.delivery_id IS NULL')
                 ->andWhere('fctc.function_id IS NULL And fctc.function_ref IS NULL');   
      return $q;
    }
    /* Récupération du nombre de de campagnes solitaires d'un projet */
    public function getNbLonelyCampaigns($project_id,$project_ref,EiCampaign $ei_current_campaign=null,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getProjectCampaignsList($project_id, $project_ref,$ei_current_campaign, $conn)->count();
    }
    
    //Récupération de la requête pour la liste des campagnes de tests d'un projet (pour liste déroulante)
    public function getProjectCampaignsQuery($project_id,$project_ref,Doctrine_Connection $conn=null ){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $q= $conn->createQuery()->from(' EiCampaign c');
        return $q->where('project_id= ? And project_ref=? ',array($project_id,$project_ref)); 
    }
    
    //Récupération de la requête pour la liste des campagnes de tests d'un projet
    public function getProjectCampaigns($project_id,$project_ref,Doctrine_Connection $conn=null ){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        $q=$this->getCampaigns(null,$conn);
        return $q->where('project_id= ? And project_ref=? ',array($project_id,$project_ref)); 
    }
    //Récupération des campagnes de tests triés par critères
    public function sortCampaignByCriterias($q,$searchCampaignCriteria){
        $q=$q;
        //Ajout des critères de tri
     if(isset ($searchCampaignCriteria['title']) && $searchCampaignCriteria['title']!=null ): 
         $q->andWhere('c.name like ?','%'.$searchCampaignCriteria['title'].'%'); 
     endif;
      if(isset ($searchCampaignCriteria['author']) && $searchCampaignCriteria['author']!=null): 
          $q->andWhere('a.email_address like ?','%'.$searchCampaignCriteria['author'].'%'); 
      endif; 
      if(isset ($searchCampaignCriteria['delivery']) && $searchCampaignCriteria['delivery']!=null && $searchCampaignCriteria['delivery']!=0): 
          $q->andWhere('d.id = ?',$searchCampaignCriteria['delivery']); 
      endif;
      return $q->orderBy('c.created_at DESC'); 
    }
    //Pagination des campagnes de tests 
    public function paginateCampaigns($q , $first_entry,$max_campaign_per_page){ 
        return $q=$q->offset($first_entry)
             ->limit($max_campaign_per_page ) ;
    }
    
    /* Récupération des auteurs de campagnes de tests pour un projet donné . (sert pour les recherches ) */
    public function getCampaignAuthorsForProject(EiProjet $ei_project,  Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        $typehead=array();
        $campaignAuthors=$conn->createQuery()->from('sfGuardUser u')  
                ->select('u.email_address')
                ->leftJoin('u.campaignAuthor d')
                ->where('project_id= ? And project_ref=? ',
                        array($ei_project->getProjectId(),$ei_project->getRefId()))
                ->execute();
        //Parse subjet author for typehead 
        if(count($campaignAuthors)>0):
            foreach($campaignAuthors as $campaignAuthor):
            $typehead[]=$campaignAuthor->getEmailAddress();
            endforeach;
        endif;
        return $typehead;
    }

    /**
     * Méthode permettant de retourner la liste des scénarios (totale ou partielle) contenus dans une campagne.
     * start & end marquent l'intervalle de la liste des scénarios à retourner.
     *
     * @param EiCampaign $campagne
     * @param $start
     * @param $end
     */
    public function getCampaignScenarios(EiCampaign $campagne, $start = -1, $end = -1){
        $liste = Doctrine_Core::getTable("EiCampaignGraph")->getGraphHasChainedList($campagne);
        $newListe = array();
        $record = false;

        /** @var EiCampaignGraph $elt */
        foreach( $liste as $elt ){
            // Si l'élément parcouru correspond à l'id de départ, on commence à recenser les scénarios.
            if( $elt->getId() == $start ){
                $record = true;
            }

            // Si recensement & que le scénario n'est pas nul & qu'il s'agit pas d'un test manuel, on recense l'élément.
            if( $record == true && $elt->getEiScenario() != null && $elt->getEiCampaignGraphType()->getAutomate() == true ){
                $newListe[] = $elt;
            }

            // Si l'élément parcouru correspond à l'id de fin, on arrête le recensement des scénarios.
            if( $record === true && $elt->getId() == $end ){
                $record = false;
            }
        }

        return $newListe;
    }

    public function setNewOnErrorValue($block_type_id,$id, $conn) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction();
            $conn->update($this->getInstance(),array('on_error' => $block_type_id), array('id' => $id));
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
            return false;
        }
    }
}