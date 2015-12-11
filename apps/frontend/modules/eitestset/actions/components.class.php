<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eitestsetComponents extends sfComponentsKalifast {
    
    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request,EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null ) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id,$ei_project->getProjectId(),$ei_project->getRefId()); 
        }

        else $this->ei_scenario=null; 
    }  
    
    public function executeSideBarScenario(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $this->checkEiScenario($request,$this->ei_project) ; 
        //Récupération des versions du scénario
                $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                    ->findByEiScenarioId($this->ei_scenario->getId());
    }
    /* Composant permettant de retourner le chemin vers l'objet */
    public function executeBreadcrumb(sfWebRequest $request){
         $this->checkProject($request); //Récupération du projet
         $this->checkProfile($request, $this->ei_project); 
         $this->checkEiScenario($request,$this->ei_project) ; 
         //On n'oubli pas de renvoyer le chemin vers le scénario
         $this->evaluatePathToScenario($this->ei_scenario);
    }
    public function executeSideBarHeaderObject(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $this->checkEiScenario($request,$this->ei_project) ; 
    } 


}

?>
