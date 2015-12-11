<?php

/**
 * Composants permettant de récupérer la plus part des entités rattachées au projet
 * afin d'en effectuer l'affichage à la visite d'un projet, au niveau du menu latéral gauche.
 *
 * @author Lenine DJOUATSA
 */
class eiprojetComponents extends sfComponentsKalifast {
 
    /* Composant permettant de retourner le chemin vers l'objet */
    public function executeBreadcrumb(sfWebRequest $request){
        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_list', 'Projects', null ,true,true);  
        if($request->getParameter('project_id') && $request->getParameter('project_ref') && $request->getParameter('profile_id') && $request->getParameter('profile_ref')):
           $this->checkProject($request); //Récupération du projet
            $this->checkProfile($request, $this->ei_project);
            
            $mod = $request->getParameter('module');
            $act = $request->getParameter('action');
            $this->evaluateProjectBread($act); 
        endif;
         
    }
    public function executeSideBarHeaderObject(sfWebRequest $request){
        
    }  
    public function executeSideBarProject(sfWebRequest $request){
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
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
     * Présente le projet de manière sommaire pour le menu de gauche
     * @param sfWebRequest $request 
     */
    public function executeShowDetails(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet  
        $this->nbScenarios = $this->ei_project->getEiScenarios()->count();
        $this->nbProfils = $this->ei_project->getProfils()->count();
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
     * Récupère la liste des projets de l'utilisateur et génère un widget permettant la navigation depuis
     * la liste déroulante.
     * @param sfWebRequest $request 
     */
    public function executeListProjets(sfWebRequest $request) {
        $project_id = $request->getParameter('project_id');
        $project_ref = $request->getParameter('project_ref');
        $user = $this->getUser()->getGuardUser();
        if ($project_ref && $project_id) {
            $this->ei_projets = $user->getProjetsQuery()->execute(); //Tous les projets
            foreach ($this->ei_projets as $p => $projet) {
                if ($projet->getProjectId() == $project_id && $projet->getRefId() == $project_ref) {
                    $this->ei_project = $projet;
                }
            }
        }
    }

    /**
     * Récupère la liste des scénarios du projet en cours de visionnage.
     * @param sfWebRequest $request 
     */
    public function executeListScenarios(sfWebRequest $request) {
        $this->checkProject($request); //Recherche du projet 
        //si le projet est renseigné

        $this->form = new sfForm();
        //mise en place du widget.
        $doctrineChoice = new sfWidgetFormDoctrineChoice(array(
            'model' => 'EiScenario',
            'add_empty' => '',
            'query' => $this->ei_project->getScenariosQuery()
        ));

        //si l'id scénario est renseigné, alors on sélectionne le
        //scénario en cours de visionnage.
        $ei_scenario_id = $request->getParameter('id');
        if (isset($ei_scenario_id))
            $doctrineChoice->setDefault($ei_scenario_id);

        $this->form->setWidget('liste_scenario_choice', $doctrineChoice);
    }

    /**
     * Récupère l'ensemble des scénarios d'un projet
     * @param sfWebRequest $request 
     */
    public function executeGetScenarios(sfWebRequest $request) {
        $this->ei_project = $this->checkProject($request); //Recherche du projet
        $this->scenarios = $this->ei_project->getScenarios()->getData();
    }

    /**
     * Renvoie un reloader pour le contenu 
     * @param sfWebRequest $request 
     */
    public function executeReloader(sfWebRequest $request) {

        $this->setReloadProjet($request);
    }

    /**
     * Renvoie un reloader pour l'arbre projet
     * @param sfWebRequest $request 
     */
    public function executeArbreProjet(sfWebRequest $request) {

        $this->setReloadProjet($request);
        $this->opened_ei_nodes = Doctrine_Core::getTable('EiTreeOpenedBy')
                ->getOpenedNodes($this->getUser()->getGuardUser()->getEiUser(),
                        $this->ei_project->getProjectId(),
                        $this->ei_project->getRefId());
        //Noeud racine de l'arbre
        $this->root_tree = Doctrine_Core::getTable('EiView')->getRootView($this->ei_project->getRefId(), $this->ei_project->getProjectId());
        //Noeuds fils du noeud racine 
        if($this->root_tree!=null):
        $this->tree_childs=$this->root_tree->getNodesWithChildsInf();
        endif;
        $this->class_action = "statistics";
        if($this->ei_version !== null)  $this->class_action = "get_path_function"; 
        if($this->showFunctionContent)  $this->class_action = "showFunctionContent";
        if($this->is_function_context)  $this->class_action = "addFunctionToSubject";
        if($this->is_step_context)      $this->class_action = "showFunctionSubjects";
    }

    /**
     * Set la variable reloadProjet et ei_project
     * @param sfWebRequest $request 
     */
    public function setReloadProjet(sfWebRequest $request) { 
        try {
            $this->checkProject($request); //Recherche du projet
            $this->checkProfile($request, $this->ei_project);
            try {
                if ($this->ei_project->getObsolete() == false) {
                    $this->reloadProjet = $this->ei_project->needsReload();
                } else {
                    $this->reloadProjet = false;
                    $this->getUser()->setFlash('reload_error', 'Votre projet est obsolet.', false);
                }
            } catch (Exception $e) {

                $this->getUser()->setFlash('reload_error', $e->getMessage(), false);
                $this->reloadProjet = false;
            }
        } catch (Exception $e) {
            $this->reloadProjet = false;
        }
    }

    public function executeNavBar(sfWebRequest $request) {
        /* Eviter de rechercher le projet et le profil dans le cas ou ce n'est pas nécessaire:
         * Par exemple pour l'index du projet
         */
        $this->chemin = "";
        $this->menu = true;
        if ($request->getParameter('module') == 'eiprojet' && $request->getParameter('action') == 'index') {
            $this->menu = false;
        } else {
            $this->ei_version = $request->getParameter('ei_version_id');
            $this->ei_scenario = $request->getParameter('ei_scenario_id');
            
            if($this->ei_scenario){
                $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->ei_scenario);
                $this->chemin = $this->ei_scenario->getPathTo();
            }
            
        }
    }
    //Détermination du prefixe de breadcrumb dans le cas d'un projet
    public function evaluateProjectBread($act) {
        switch ($act): 
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_dashboard', 'Dashboard', null ,true,true);  
                break;
            default:
                break;
        endswitch;
    }

}

?>
