<?php

/**
 * Class eiscenarioComponents
 */
class eiscenarioComponents extends sfComponentsKalifast {

    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request, EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id, $ei_project->getProjectId(), $ei_project->getRefId());
        } else
            $this->ei_scenario = null;
    }

    /* Recherche d'une version courante de scénario */

    public function checkEiVersion($ei_version_id) {
        if ($ei_version_id == null) :
            $this->ei_version_id = null;
            $this->ei_version = null;

        else:
            $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($ei_version_id);
        endif;
    }

    /**
     * @param sfWebRequest $request
     */
    public function executeNavMenu(sfWebRequest $request) {
        $scenario_id = $request->getParameter('ei_scenario_id');
        $version_id = $request->getParameter("ei_version_id");
        
        if( !(isset($this->ei_current_block) && $this->ei_current_block instanceof EiVersionStructure && $this->ei_current_block->isEiBlock()) ){
            $this->ei_current_block = null;
        }

        if(!isset($this->ei_block_root) && !isset($version_id)){
            $this->ei_block_root = Doctrine_Core::getTable('EiBlock')
                ->getEiBlockRoot($this->ei_scenario->getId());
        }
        elseif( !isset($this->ei_block_root) && isset($version_id) ){
            $this->ei_block_root = Doctrine_Core::getTable('EiVersionStructure')
                ->getEiVersionStructureRoot($version_id);

            $this->ei_version_id = $request->getParameter('ei_version_id');
        }
        elseif( isset($version_id) ){
            $this->ei_version_id = $request->getParameter('ei_version_id');
            //
        }

        if ($request->getParameter('module') == 'eiversion') {
            $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                ->findByEiScenarioId($scenario_id);
        }

        $this->ei_blocks = Doctrine_Core::getTable('EiBlock')
                ->getEiBlocksWithParams($this->ei_block_root->getId());

        $this->block_redirect_class = $request->getParameter('module');
    }

    /**
     * @param sfWebRequest $request
     */
    public function executeSideBarScenario(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        if ($this->ei_scenario != null):
            //Récupération des versions du scénario
            $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                    ->findByEiScenarioId($this->ei_scenario->getId());
            $this->defaultVersion = $this->ei_scenario->getVersionForProfil($this->ei_profile);
        endif;
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }

    /* Composant permettant de retourner le chemin vers l'objet */

    public function executeBreadcrumb(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->breadcrumb = array(); //Tableau destiné à contenir le breadcrumb
        $mod = $request->getParameter('module');
        $act = $request->getParameter('action');

        $projet_eiscenario = $this->urlParameters;
        $projet_eiscenario['action'] = 'index';
        $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_scenario', 'Scenarios',
                $this->generateUrl('projet_eiscenario', $projet_eiscenario) ,null,null,'AccessProjectScenariosOnBreadCrumb');  
            
        if ($this->ei_scenario != null):
            //On n'oubli pas de renvoyer le chemin vers le scénario
            $this->breadcrumb = $this->evaluatePathToScenario($this->ei_scenario, $this->breadcrumb, $this->ei_project, $this->ei_profile);
        endif;
        switch ($mod):
            case "eiscenario":
                $this->evaluateSubBread($act);
                break;
            case "eiversion":
                $this->checkEiVersion($request->getParameter('ei_version_id'));
                $this->evaluateSubBreadVersion($act);
                break;
            case "eidataset":
                $this->evaluateSubBreadDataSet($act);
                break;
            case "eitestset":
                $this->evaluateSubBreadTestSet($act);
                break;
            default:
                break;
        endswitch;
    }

    public function executeSideBarHeaderObject(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);

        /** @var EiUser $user */
        $this->ei_user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
                ->findOneByUserRefAndUserId($this->ei_user->getRefId(), $this->ei_user->getUserId());

        $this->firefox_path = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project); 
            
        if ($this->ei_scenario != null):
            //Récupération des versions du scénario
            $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                    ->findByEiScenarioId($this->ei_scenario->getId());
            $this->defaultVersion = $this->ei_scenario->getVersionForProfil($this->ei_profile);
        endif;
        /* On récupère éventuellement le package par défaut de l'utilisateur courant */
        if($this->ei_user!=null && $this->ei_project!=null):
            $this->defPack=$this->getDefaultPackage($this->ei_user, $this->ei_project); 
        endif;
    }

    //Détermination du chemin jusqu'à un scénario
    public function evaluatePathToScenario(EiScenario $ei_scenario, $breadcrumb, EiProjet $ei_project, EiProfil $ei_profile) {
        return $ei_scenario->getPathTo($breadcrumb, $ei_project, $ei_profile);
    }

    //Détermination du prefixe de breadcrumb dans le cas d'un scenario
    public function evaluateSubBread($act) {
        switch ($act):
            case "index":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_scenario', 'Tree', null ,true,true);
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null ,true,true); 
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_edit', 'Edit', null ,true,true); 
            
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show', 'Show', null ,true,true); 
            
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'une version de scénario
    public function evaluateSubBreadVersion($act) {
        //Url vers le listing des versions d'un scénario
        $versionsUri = $this->urlParameters;
        $versionsUri['ei_scenario_id'] = $this->ei_scenario->getId();
        $versionsUri['action'] = 'index';
        $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_version', 'Versions', $this->generateUrl('projet_new_eiversion', $versionsUri) ,
                null,null,'AccessScenarioVersionsOnBreadCrumb'); 
            
        if ($this->ei_version != null):
            $projet_edit_eiversion = $this->urlParameters;
            $projet_edit_eiversion['ei_version_id'] = $this->ei_version->getId();
            $projet_edit_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
            $projet_edit_eiversion['action'] = 'edit';

            $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_version', $this->ei_version->getLibelle(), $this->generateUrl('projet_edit_eiversion', $projet_edit_eiversion) . '#'); 
            
        endif;

        switch ($act):
            case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_version', 'List', null,true,true);
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null,true,true); 
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_edit', 'Edit', null,true,true);
            
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show', 'Show', null,true,true);
            
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'un data set  de scénario
    public function evaluateSubBreadDataSet($act) {
        switch ($act):
            case "index":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_version', 'List', null,true,true); 
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_add', 'New', null,true,true); 
            
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_edit', 'Edit', null,true,true); 
            
                break;
                break;
            case "show":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_show', 'Show', null,true,true); 
            
                break;
            default:
                break;
        endswitch;
    }

    //Détermination du prefixe de breadcrumb dans le cas d'un test set  de scénario
    public function evaluateSubBreadTestSet($act) {
        switch ($act):
            case "index":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_testset', 'Reports', null,true,true); 
            
                break;
            case "new":
            case "create":
                $this->breadcrumb[] = $this->setBreadcrumbTabItem('ei_add', 'New', null,true,true); 
            
                break;
                break;
            case "edit":
            case "update":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_edit', 'Edit', null,true,true); 
            
                break;
                break;
            case "show":
                $this->breadcrumb[] =$this->setBreadcrumbTabItem('ei_show', 'Show', null,true,true); 
            
                break;
            default:
                break;
        endswitch;
    }

    /**
     * Composant permettant de récupérer le launcher.
     *
     * @param sfWebRequest $request
     */
    public function executePlayButton(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);

        if ($this->ei_scenario != null) {
            // Récupération du jeu de données pré-sélectionné pour le "play".
            $cookieJddsScenarios = $request->getCookie(sfConfig::get("app_nomcookiejddscenariosplay"));
            $jddScenarioToPlay = json_decode($cookieJddsScenarios, true);
            // On ajoute le sous-tableau relatif au scénario contenant l'id et le nom du jeu de données sélectionné précédemment.
            $this->jddScenarioToPlay = isset($jddScenarioToPlay[$this->ei_scenario->getId()]) ? $jddScenarioToPlay[$this->ei_scenario->getId()] : array();
        }
    }

}

?>
