<?php

/**
 *Composant générique pour les composants kalifast
 * @author Lenine DJOUATSA
 */
class sfComponentsKalifast extends sfComponents {

    /*
     * On nettoie les variables de session du projet pour qu'elle ne nuisent pas
     *  à une nouvelle tentative de connexion
     */

    public function cleanSessionVar(){
        //Variables de session projet
         $this->getUser()->setAttribute("current_project_ref", null);
         $this->getUser()->setAttribute("current_project_id",null); 
         //Variable de session profil
         $this->getUser()->setAttribute("current_profile_name",null);
         $this->getUser()->setAttribute("current_profile_ref",null);
         $this->getUser()->setAttribute("current_profile_id",null); 
    }
    
    /* Cette fonction permet de rechercher les paramètres du projet(project_id et project_ref ).  */
    public function checkProjectParameters(sfWebRequest $request) {
        $this->project_id = $request->getParameter('project_id');
        $this->project_ref = $request->getParameter('project_ref');
      
        if ($this->project_id == null || $this->project_ref==null)  
        {
            //On vérifie les paramètres de projet 
            $this->project_ref=$this->getUser()->getAttribute("current_project_ref");
            $this->project_id=$this->getUser()->getAttribute("current_project_id");   
        }
    }
    
    
    /* Cette fonction permet de rechercher le projet (EiProjet) avec les paramètres renseignés.  */
    public function checkProject(sfWebRequest $request) { 
        $this->checkProjectParameters($request);
        if($this->project_id == null || $this->project_ref==null)
            $this->forward404 ('Project not found with theses parameters');
        
            $this->ei_project=Doctrine_Core::getTable('EiProjet')
                    ->findOneByProjectIdAndRefId($this->project_id,$this->project_ref);
            if ($this->ei_project == null)
                throw new Exception("Project not found with these parameters ..."); 
            $this->project_name=$this->ei_project->getName();
        //On défine les variables de sessions permettant de naviguer au sein d'un projet
            $this->getUser()->setAttribute("current_project_ref", $this->ei_project->getRefId());
            $this->getUser()->setAttribute("current_project_id", $this->ei_project->getProjectId()); 
        //On vérifie que l'utilisateur a les droits nécessaires pour l'utilisation du projet 
             if(Doctrine_Core::getTable('EiProjectUser')->getEiProjet(
                            $this->ei_project->getProjectId(), $this->ei_project->getRefId(), $this->getUser()->getGuardUser()->getEiUser())==null):
                 
                 //On nettoie les variables de session du projet pour qu'elle ne nuisent pas à une nouvelle tentative de connexion
                 $this->cleanSessionVar();
                 //Renvoie d'une exception
                 throw new Exception("Access denied for this project..."); 
             endif;
                     
      } 

    //Recherche des paramètres de profil (dans l'url et dans la session utilisateur
    public function checkProfileParameters(sfWebRequest $request){ 
        $this->profile_name = $request->getParameter('profile_name');
        $this->profile_ref = $request->getParameter('profile_ref');
        $this->profile_id = $request->getParameter('profile_id');
        if($this->profile_name==null || $this->profile_ref==null || $this->profile_id==null){
            //On vérifie les paramètres de profil
            $this->profile_name=$this->getUser()->getAttribute("current_profile_name");
            $this->profile_ref=$this->getUser()->getAttribute("current_profile_ref");
            $this->profile_id=$this->getUser()->getAttribute("current_profile_id"); 
        }
    }
    /* Récupération du package par défaut d'un utilisateur */
    public function getDefaultPackage(EiUser $ei_user , EiProjet $ei_project){
        return Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByUserRefAndUserIdAndProjectRefAndProjectId(
                    $ei_user->getRefId(), $ei_user->getUserId(), $ei_project->getRefId(), $ei_project->getProjectId()); 
    }
    
    //Recherche d'un profil avec les paramètres de requête
    public function checkProfile(sfWebRequest $request, EiProjet $ei_project) {
        $this->checkProfileParameters($request); 
        if ($this->profile_ref  != null && $this->profile_id  != null) {
            //Recherche du projet en base
            $this->ei_profile = Doctrine_Core::getTable('EiProfil')
                    ->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef(
                            $this->profile_id, $this->profile_ref,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si le profil n'existe pas , alors on retourne un erreur 404
            if ($this->ei_profile == null):
                //la session est expirée ou le profil n'existe simplement pas 
                //on néttoie les données de session avant d'envoyer le méssage d'erreur
                //$this->getUser()->getAttributeHolder()->clear();
                //On déconnecte l'utilisateur 
                //$this->getUser()->signOut();
                /* Définition d'un flash pour signifier à l'utilisateur qu'il doit redéfinir son profil de navigation */
                $this->getUser()->setFlash('alert_home_page_error',
                    array('title' => 'Warning' ,
                        'class' => 'alert-warning' ,
                        'text' => 'Profile not found with these parameters.Select project again to initialize it ...'));
                $this->getContext()->getActionStack()->getLastEntry()->getActionInstance()->redirect('@homepage') ;
                //throw new Exception("Profile not found with these parameters ...");
                
            endif;
            $this->profile_name=$this->ei_profile->getName(); 
            //Définition du tableau de paramètres basique pour les différents objets
                $this->urlParameters = array(
                    'project_id' => $ei_project->getProjectId(),
                    'project_ref' => $ei_project->getRefId(),
                    'profile_id' => $this->profile_id,
                    'profile_ref' => $this->profile_ref,
                    'profile_name' => $this->profile_name);
            //Sauvegarde du profil en session utilisateur  
            $this->getUser()->setAttribute("current_profile_name", $this->profile_name);
            $this->getUser()->setAttribute("current_profile_id", $this->profile_id); 
            $this->getUser()->setAttribute("current_profile_ref", $this->profile_ref); 
        }

        else {
            $this->getUser()->setFlash('alert_home_page_error',
                    array('title' => 'Warning' ,
                        'class' => 'alert-warning' ,
                        'text' => 'Profile not found with these parameters.Select project again to initialize it ...'));
            $this->getContext()->getActionStack()->getLastEntry()->getActionInstance()->redirect('@homepage') ;
            //throw new Exception("Missing profile parameters  ...");
        }
    }

    //Recherche d'une livraison
    public function checkDelivery(sfWebRequest $request, EiProjet $ei_project) {
        $this->delivery_id = $request->getParameter('delivery_id');
        if ($this->delivery_id == null) :
            $this->ei_delivery=null;
            else:
            //Recherche de la livraison tout en s'assurant qu'elle corresponde au projet courant 
            $this->ei_delivery = Doctrine_Core::getTable('EiDelivery')->findOneByIdAndProjectIdAndProjectRef(
                    $this->delivery_id, $ei_project->getProjectId(), $ei_project->getRefId()); 
        endif;
        
    }
    //Récupération des livraisons ouvertes
    public function checkOpenDeliveries(EiProjet $ei_project){
        return Doctrine_Core::getTable('EiDelivery')->getOpenDeliveries($ei_project,10);
    }
    
    public function setBreadcrumbTabItem($logo,$completeTitle,$uri=null,$active=null,$is_last_bread=null,$id=null,$class=null){
        return array("logo" => ei_icon($logo),  
                    "title" => MyFunction::troncatedText($completeTitle, 20),
                    "completeTitle" => $completeTitle,
                    "uri" => ($uri!=null?$uri:"#"),
                    "active" => ($active!=null?$active:false) ,
                    "is_last_bread" => ($is_last_bread!=null?$is_last_bread:false),
                    "id" => ($id!=null?$id:""),
                    "class" =>($class!=null?$class:"") );
    }
    public function executeProjectUsers(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet 

        if ($this->ei_project->getProjectId() != null && $this->ei_project->getRefId() != null)
            $this->users = doctrine_core::getTable('EiProjectUser')
                    ->findByProjectIdAndProjectRef($this->ei_project->getProjectId(), $this->ei_project->getRefId());
        else
            $this->users = Doctrine_Core::getTable('EiProjet')
                    ->findOneByProjectIdAndRefId($this->ei_project->getProjectId(), $this->ei_project->getRefId())
                    ->getEiUsers();
    } 

    /**
     * Permet de récupérer la liste des profils pour un projet.
     * @param sfWebRequest $request 
     */
    public function executeGetProfils(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet 
        $this->checkProfile($request,$this->ei_project); //Recherche du profil 

        $this->profils = $this->ei_project->getProfils();
        $ei_scenario_id = $request->getParameter('ei_scenario_id');
        if ($ei_scenario_id)
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($ei_scenario_id);
        //On regarde si l'on navigue dans un scénario
    }

    /**
     * Méthode permettant de vérifier & recherche une exécution d'une campagne.
     *
     * @param sfWebRequest $request
     * @param EiProjet $ei_project
     * @param EiCampaign $ei_campaign
     */
    public function checkCampaignExecution(sfWebRequest $request, EiProjet $ei_project, EiCampaign $ei_campaign)
    {
        $this->campaign_execution_id = $request->getParameter('campaign_execution_id');

        if ($this->campaign_execution_id != null):
            $this->campaign_execution = Doctrine_Core::getTable('EiCampaignExecution')->findExecution($this->campaign_execution_id);
        endif;
    }
    /* Récupération  simple d'une execution de campagne */
    public function getExecutionDetails($execution_id){
        $res=Doctrine_core::getTable("EiCampaignExecution")->getExecutionDetails($execution_id);
        if(count($res)>0):
            return $this->campaign_execution=$res[0]; 
        endif; 
        return array();
    }
 

}

?>
