<?php

/**
 *
 * @author Lenine DJOUATSA
 */
class eiversionComponents extends sfComponentsKalifast {
    /* Recherche d'une version courante de scénario */

    public function getEiVersion($ei_version_id) {
        if ($ei_version_id == null) :
            $this->ei_version_id = null;
            $this->ei_version = null;

        else:
            $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($ei_version_id);
        endif;
    }

    public function executeSideBarVersion(sfWebRequest $request) {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->ei_scenario_id = $request->getParameter('ei_scenario_id');

        if ($this->ei_scenario_id):
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find(array($request->getParameter('ei_scenario_id')));
            if ($this->ei_scenario != null):
                //Récupération des versions du scénario
                $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                        ->findByEiScenarioId($this->ei_scenario->getId());
                //Recherche de la version courante du scénario 
                $this->getEiVersion($request->getParameter('ei_version_id'));
                //Récupération de la version du scénario correspondant au profil courant
                $this->defaultVersion = $this->ei_scenario->getVersionForProfil($this->ei_profile);
            endif;
        endif;
        /* Liste des livraisons ouvertes dans la limite de 10 livraisons ordonnées pad date */
        $this->openDeliveries=$this->checkOpenDeliveries($this->ei_project);
    }

    /* Composant permettant de retourner la barre de menu objet d'une version */

    public function executeSideBarHeaderObject(sfWebRequest $request)
    {
        $this->checkProject($request); //Récupération du projet
        $this->checkProfile($request, $this->ei_project);
        $this->ei_scenario_id = $request->getParameter('ei_scenario_id');

        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
            ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefox_path = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        if ($this->ei_scenario_id):
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find(array($request->getParameter('ei_scenario_id')));
            if ($this->ei_scenario != null): 
                //Recherche de la version courante du scénario  
                $this->getEiVersion($request->getParameter('ei_version_id'));
                //Récupération de la version du scénario correspondant au profil courant
                $this->defaultVersion = $this->ei_scenario->getVersionForProfil($this->ei_profile);
            endif;
        endif;

        $this->mod = $request->getParameter('module');
        $this->act = $request->getParameter('action');
        $this->objMenu = array();

        switch ($this->mod):
            case 'eiversion':
                switch ($this->act):
                    case 'index':
                    case 'new':
                    case 'create':

                        $projet_new_eiversion = $this->urlParameters;
                        $projet_new_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
                        $projet_new_eiversion['action'] = 'index';
                        $this->objTitle = 'Versions';
                        $this->logoTitle =    ei_icon('ei_version') ;
                        $this->objMenu[] = array(
                            'logo' =>   ei_icon('ei_list') ,
                            'title' => 'List',
                            'uri' => $this->generateUrl('projet_new_eiversion', $projet_new_eiversion) . '#', //Url de listing des versions d'un scénario
                            'active' => ($this->mod == 'eiversion' && $this->act == 'index') ? true : false,
                            'class' => "",
                            'id' => "",
                            'tab' => '',
                            'titleAttr' => "Scenario versions");

                        $projet_new_eiversion['action'] = 'new';
                        $this->objMenu[] = array(
                            'logo' =>    ei_icon('ei_add'),
                            'title' => 'New',
                            'uri' => $this->generateUrl('projet_new_eiversion', $projet_new_eiversion) . '#', //Url de création d'une version de scénario
                            'active' => ($this->mod == 'eiversion' && ($this->act == 'new' || $this->act == 'create') ) ? true : false,
                            'class' => "",
                            'id' => "",
                            'tab' => '',
                            'titleAttr' => "New scenario version");
                        break;
                        break;
                        break;
                    case 'edit':
                    case 'update':
                    case 'show':
                    case 'editVersionWithPackage':    
                        $this->objTitle = $this->ei_version->getLibelle();
                        $this->logoTitle =  ei_icon('ei_version') ;
                        $this->objMenu[] = array(
                            'logo' => '<i class="fa fa-wrench"></i>',
                            'title' => 'Properties',
                            'uri' => '#informations',
                            'active' => false,
                            'class' => "",
                            'tab' => 'tab',
                            'id' => "versionProp",
                            'titleAttr' => "Version properties");
                        $this->objMenu[] = array(
                            'logo' =>     ei_icon('ei_version') ,
                            'title' => 'Structure',
                            'uri' => '#block',
                            'active' => ($this->mod == 'eiversion' && ($this->act == 'edit' || $this->act == 'update' || $this->act == 'show' )) ? true : false,
                            'class' => "",
                            'id' => "versionStructure",
                            'tab' => 'tab',
                            'titleAttr' => "Version structure"); 
                        break;
                        break;
                        break;
                        break;
                    default :

                        break;
                endswitch;

                break;
            default :

                break;
        endswitch;
    }
    
    /**
     * @param sfWebRequest $request
     */
    public function executeNavMenu(sfWebRequest $request) {
        $this->ei_scenario_id = $request->getParameter('ei_scenario_id');
        $this->ei_version_id = $request->getParameter('ei_version_id');

        if (!(isset($this->ei_current_block) && $this->ei_current_block instanceof EiVersionStructure && $this->ei_current_block->isEiBlock())) {
            $this->ei_current_block = null;
        }

        $this->ei_block_root = Doctrine_Core::getTable('EiVersionStructure')
                ->getEiVersionStructureRoot($this->ei_version_id); 
        
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                ->findByEiScenarioId($this->ei_scenario_id);

        $this->ei_blocks = Doctrine_Core::getTable('EiBlock')
                ->getEiBlocksWithParams($this->ei_block_root->getId());

        $this->block_redirect_class = $request->getParameter('module');
    }

}

?>
