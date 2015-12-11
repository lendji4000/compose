<?php

/**
 * EiDelivery
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage 
 */

class EiDelivery extends BaseEiDelivery {

     
    public function __toString() {
        return sprintf('%s', $this->getName());
    }
     
    //Récupération du projet correspondant à la livraison 
  public function getProject(){
      return Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($this->getProjectId(),$this->getProjectRef());
  }
    /**
     * Retourne le nom de la campagne raccourci.
     * @param int $size la taille totale de la chaine à retourner.
     * @return string
     * @throws InvalidArgumentException
     */
    public function getTroncatedName($size = 17) {

        if ($size <= 0)
            throw new InvalidArgumentException('Invalid size value to troncate delivery name. ' . $size . ' is not a valid value.');

        $name = $this->getName();
        if (strlen($name) > $size):
            return substr($name, 0, $size - 3) . '...';
        else:
            return $name;
        endif;
    }

    //Recherche de toutes les fonctions d'une livraison ayant subit des modifications 
    public function getFunctionsToMigrate($selectPart,$groupBy=true){
        //Construction du tableau de critères à prendre en compte
        $criteria=array(
            "project_id" => $this->getProjectId(),
            "project_ref" => $this->getProjectRef(),
            "delivery_id" => $this->getId(),
        );
        return $this->getTable()->getFunctionsToMigrate($criteria,$selectPart,$groupBy); 
    }
    //Recherche de tous les scenarios d'une livraison ayant subit des modifications 
    public function getScenariosToMigrate($selectPart,$groupBy=true){
        //Construction du tableau de critères à prendre en compte
        $criteria=array(
            "project_id" => $this->getProjectId(),
            "project_ref" => $this->getProjectRef(),
            "delivery_id" => $this->getId(),
        );
        return $this->getTable()->getScenariosToMigrate($criteria,$selectPart,$groupBy); 
    }
    /**
     * Retourne le nom de la livraison raccourci.
     * @param int $size la taille totale de la chaine à retourner.
     * @return string
     * @throws InvalidArgumentException
     */
    public function getTroncatedDescription($size = 55) {

        if ($size <= 0)
            throw new InvalidArgumentException('Invalid size value to troncate description name. ' . $size . ' is not a valid value.');

        $description = $this->getDescription();
        if (strlen($description) > $size):
            return substr($description, 0, $size - 3) . '...';
        else:
            return $description;
        endif;
    }

    //Recherche des campagnes d'une livraisons
    public function getDeliveryCampaigns() {
        return $this->getTable()->getDeliveryCampaigns($this->getId());
    }

    //Recherche des sujets d'une livraison
    /* @method     Doctrine_Collection     getDeliverySubjects()         Returns delivery subjects */
    public function getDeliverySubjects() {
        return $this->getTable()->getDeliverySubjects($this->getProjectId(),$this->getProjectRef(),$this->getId());
    }
    //Recherche des sujets d'une livraison (requête)
    /* @method     Doctrine_Query     getDeliverySubjectsQuery()         Returns delivery subjects  query */
    public function getDeliverySubjectsQuery() {
        return $this->getTable()->getDeliverySubjectsQuery($this->getProjectId(),$this->getProjectRef(),$this->getId());
    }
    /* Procedure de migration applicative d'une livraison (delivery process) */
    public function getDeliveryProcess(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getDeliveryProcess($this->getId(),$conn);
    }
    /* Récupération des resolutions de conflits de fonction éffectuées sur une livraison */
    public function getResolvedConflictsOnFunctions(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getResolvedConflictsOnFunctions($this->getId());
    }
    /* Récupération des resolutions de conflits de scenario éffectuées sur une livraison */
    public function getResolvedConflictsOnScenarios(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getResolvedConflictsOnScenarios($this->getId());
    }
    /* Fonction modifiées dans le cadre d'une livraison  et executées ou non */
    public function getExFunctions($exec ,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return Doctrine_Core::getTable("KalFunction")->getModifyAndExecDelFunctions($this,null,null,$exec,$conn);
    }
    /* Récupération des bugs ayant un impact */
    public function getBugsWithImpacts(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getBugWithImpactOrNot($this->getId(),true, $conn);
    }
    /* Récupération des bugs n'ayant pas  d'impact */
    public function getBugsWithoutImpacts(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getBugWithImpactOrNot($this->getId(),false, $conn);
    }
    /* Compter les fonctions impactées d'une livraison */
    public function countImpactedFunctions(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return $this->getTable()->countImpactedFunctions($this->getId(), $conn);
    }
    /* Gestion des fonctions exécutées dans les campagnes de la livraison (dernières eécutions de la campagne) */
    public function getExFuncInLastExDelCamps(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getExFuncInLastExDelCamps($this->getId(), $conn);
    }
    
    public function getExFunctWithAtLeastFiveDiffParams(Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection(); 
        return $this->getTable()->getExFunctWithAtLeastFiveDiffParams($this->getId(), $conn);
    }
    /* Récupération de la dernière itération pour un profil et une livraison */
    public function getLastIterationForProfile(EiProfil $ei_profile,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return Doctrine_core::getTable("EiIteration")->getLastIterationForProfile($this->getProjectId(),$this->getProjectRef(),$this->getId(),$ei_profile->getProfileId(),$ei_profile->getProfileRef(),$conn);
    }
    /* Exécution des fonctions impactées par une livraison  (en récupérant les variations des paramètres ) */
    public function getImpactedFunctionsStatsWithParams($iteration_id=null,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
      $q="select del.t_id,del.t_obj_id,del.t_ref_obj,del.t_name,del.t_path, del.s_delivery_id as delivery_id,  del.f_function_id as function_id,
          del.f_function_ref as function_ref,f_criticity,exec.iteration_id,exec.nbExOk,exec.nbExKo , param.nbMinDistinctParams 
            from ei_delivery_impacted_functions_distinct_vw del  
            left   join ei_log_function_nb_ex_vw exec on exec.function_id=del.f_function_id   
            and exec.function_ref=del.f_function_ref and s_delivery_id=".$this->getId();
      
      if($iteration_id!=null):
          $q.=" and exec.iteration_id =  ".$iteration_id;
      endif; 
      $q.=" left   join ei_log_param_in_count_distinct_min_vw param on param.function_id=exec.function_id and param.function_ref=exec.function_ref and param.iteration_id=exec.iteration_id
              where s_delivery_id=".$this->getId() ; 
     return  $conn->fetchAll($q);
    }
    /* Récupération de la progression des bugs d'une livraison par statut */ 
    public function getBugsByStates( Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        return $conn->fetchAll("select  SUM(IF(s.delivery_id=".$this->getId().",1,0)) as nbBugs   ,st.id as state_id, st.name as st_name,st.color_code  , s.id as subject_id, s.name as subject_name,s.delivery_id as delivery_id 
            from ei_subject_state   st 
            left join ei_subject s on s.subject_state_id=st.id  where st.project_id=".$this->getProjectId()."  and st.project_ref=".$this->getProjectRef()." group by st.id "); 
            
    }
    /* Récupération des fonctions impactées exécutées ou non dans le cadre d'une livraison sur plusieurs iérations */
    public function getDelStatsForManyIterations(array $ei_iterations,Doctrine_Connection $conn=null){
        if($conn==null) $conn = Doctrine_Manager::connection();
        if(count($ei_iterations)>0):
          $q = "select del.t_id,del.t_obj_id,del.t_ref_obj,del.t_name,del.t_path, del.s_delivery_id as delivery_id,  del.f_function_id as function_id,
          del.f_function_ref as function_ref,f_criticity,exec.iteration_id,exec.nbExOkFinal as nbExOk,exec.nbExKoFinal as nbExKo , param.nbMinDistinctParams 
            from ei_delivery_impacted_functions_distinct_vw del  
            left   join" .
                    " (select * , SUM(nbExOK) as nbExOkFinal ,SUM(nbExKo) as nbExKoFinal "
                    . "from ei_log_function_nb_ex_vw where iteration_id IN (" . implode(',', $ei_iterations) . ") group by function_id, function_ref) as exec " .
                    "    on exec.function_id=del.f_function_id   
            and exec.function_ref=del.f_function_ref and s_delivery_id=" . $this->getId();

            $q.=" and exec.iteration_id IN  (" . implode(',', $ei_iterations) . ")";
            $q.=" left   join ei_log_param_in_count_distinct_min_vw param on param.function_id=exec.function_id and param.function_ref=exec.function_ref and param.iteration_id=exec.iteration_id
              where s_delivery_id=" . $this->getId();
           // throw new Exception($q);
          return $conn->fetchAll($q);  
          else :
              return null;
        endif; 
    }
}
