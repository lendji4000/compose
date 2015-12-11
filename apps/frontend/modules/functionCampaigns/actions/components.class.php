<?php

/** 
 * @author  Lenine DJOUATSA
 */
class functionCampaignsComponents extends sfComponentsKalifast {
      
    /* Cette fonction permet de rechercher la fonction (KalFunction) avec les paramètres renseignés.  */

    public function checkFunction(sfWebRequest $request,  EiProjet $ei_project) {
        $this->function_id = $request->getParameter('function_id');
        $this->function_ref = $request->getParameter('function_ref');

        if ($this->function_id != null || $this->function_ref != null) {
            //Recherche de la fonction en base
            $this->kal_function = Doctrine_Core::getTable('KalFunction')
                    ->findOneByFunctionIdAndFunctionRefAndProjectIdAndProjectRef(
                            $this->function_id,$this->function_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si la fonction n'existe pas , alors on retourne null
            if ($this->kal_function == null)
                $this->kal_function = null;
        }
        else {
            $this->function_id = null;
            $this->function_ref = null;
        }
    }
    public function executeSideBarFunction(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);  
        $this->checkFunction($request, $this->ei_project);
    }
    /* Composant permettant de retourner le chemin vers l'objet */
    public function executeBreadcrumb(sfWebRequest $request){
         $this->checkProject($request); //Récupération du projet
         $this->checkProfile($request, $this->ei_project);  
         $this->checkFunction($request, $this->ei_project); 
            //On n'oubli pas de renvoyer le chemin vers le scénario
           //$this->evaluatePathToObject($this->ei_scenario); 
    }
    public function executeSideBarHeaderObject(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project); 
        $this->checkFunction($request, $this->ei_project);
    } 

}

?>
