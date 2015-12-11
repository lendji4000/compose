<?php

/**
 * eiversion actions.
 *
 * @package    kalifast
 * @subpackage eiversion
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiversionActions extends sfActionsKalifast {

    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }

    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request, EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                    ->findOneByIdAndProjectIdAndProjectRef(
                    $this->ei_scenario_id, $ei_project->getProjectId(), $ei_project->getRefId());
            //Si le scénario n'existe pas , alors on retourne un erreur 404
            if ($this->ei_scenario == null) {
                $message = 'Missing scenario ...';
                $request->setParameter('msg', $message);
                $request->setParameter('back_link', $request->getReferer());
                $this->forward('erreur', 'error404');
            }
        } else {
            $this->forward404('Missing scenario parameters  ...');
        }
    }

    

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        if ($this->ei_scenario != null):
            //Récupération des versions du scénario ( en prenant pour chaque version le package associé si existant
            $this->ei_versions = Doctrine_Core::getTable('EiVersion')->getScenarioVersionsWithPackage($this->ei_scenario);
        endif;
    }

    public function executeSearchVersion(sfWebRequest $request) {
        // recuperation des paramètres
        $tab = Array();
        if ($request->getParameter('ei_version_id') != null) {
            $tab['ei_version_id'] = $request->getParameter('ei_version_id');
        }
        if ($request->getParameter('ei_scenario_id') != null) {
            $tab['ei_scenario_id'] = $request->getParameter('ei_scenario_id');
        }
        if ($request->getParameter('libelle') != null) {
            $tab['libelle'] = $request->getParameter('libelle');
        }
        if ($request->getParameter('created_at') != null) {
            $tab['created_at'] = $request->getParameter('created_at');
        }
        if ($request->getParameter('updated_at') != null) {
            $tab['updated_at'] = $request->getParameter('updated_at');
        }

        $this->versions = Doctrine_Core::getTable("EiVersion")->searchVersion($tab)->execute();
    }

    public function executeRename(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->ei_version_id = $request->getParameter('ei_version_id');
        $this->new_name = $request->getParameter('new_name');
        if (!$this->ei_version_id || !$this->new_name)
            $this->renderText('Missing parameters ! try again');
        else {
            $ei_version = Doctrine_Core::getTable('EiVersion')->findOneById($this->ei_version_id);
            $this->forward404Unless(($ei_version->getEiScenario()->getProjectId() == $this->project_id) && ($ei_version->getEiScenario()->getProjectRef() == $this->project_ref));
            if ($ei_version != null && $ei_project != null) {
                $ei_version->setLibelle($this->new_name);
                $ei_version->save();
                $this->renderText($ei_version->getLibelle());
            } else {
                $this->forward404('Unreachable project or test suite version ! try again');
            }
        }
        return sfView::NONE;
    }

    /* Recherche du package par défaut avec redirection vers la liste des versions si ce dernier n'est pas retrouvé */

    public function searchDefPackAndRedirect() {
        if ($this->ei_user != null && $this->ei_project != null):
            $this->defPack = $this->getDefaultPackage($this->ei_user, $this->ei_project);
            if ($this->defPack == null):
                $this->getUser()->setFlash('alert_version_form', array('title' => 'Warning! ',
                    'class' => 'alert-warning',
                    'text' => "Default package is not set. Set it and try again ..."));
                //Le package par défaut n'est pas définit et l'utilisateur tente de reproduire l'url d'accès sur son navigateur: on le redirige vers le listing des version
                $projet_list_eiversion = $this->urlParameters;
                $projet_list_eiversion['ei_scenario_id'] = $this->ei_scenario_id;
                $projet_list_eiversion['action'] = 'index';
                $this->redirect($this->generateUrl('projet_new_eiversion', $projet_list_eiversion));

            endif;
        endif;
    }

    public function executeNew(sfWebRequest $request) {

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        /* On recherche le package par défaut. S'il n'est pas définit , on le spécifie à l'utilisateur au lieu  de lui retourner le formulaire */
        $this->searchDefPackAndRedirect();
        $this->ei_package = Doctrine_core::getTable('EiTicket')->findOneByTicketIdAndTicketRef($this->defPack->getTicketId(), $this->defPack->getTicketRef());
        /* On recherche si pour le package par défaut et le scénario , il existe une  version */
        $this->ei_scenario_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
                $this->ei_scenario_id, $this->defPack->getTicketId(), $this->defPack->getTicketRef());
        if ($this->ei_scenario_package != null):
            $this->new_ei_scenario_package = $this->ei_scenario_package;
        //throw new Exception('Version is already set for this package !');  
        else:
            $this->new_ei_scenario_package = new EiScenarioPackage();
            $this->new_ei_scenario_package->setPackageId($this->defPack->getTicketId());
            $this->new_ei_scenario_package->setPackageRef($this->defPack->getTicketRef());
            $this->new_ei_scenario_package->setEiScenarioId($this->ei_scenario_id);
        endif;

        $ei_version = new EiVersion();
        $ei_version->setEiScenarioId($this->ei_scenario_id);
        $ei_version->ei_scenario_id = $request->getParameter('ei_scenario_id');
        $this->form = new EiVersionForm($ei_version, array('ei_scenario_package' => $this->new_ei_scenario_package));
    }

    public function executeCreate(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        /* On recherche le package par défaut. S'il n'est pas définit , on le spécifie à l'utilisateur au lieu  de lui retourner le formulaire */
        $this->searchDefPackAndRedirect();

        if (!($request->isMethod(sfRequest::POST) || $request->isXmlHttpRequest()))
            $this->forward404("Request error ...");
        //vérification des paramètres.
        $this->getUrlParameters($request);


        $this->ei_block_root = Doctrine_Core::getTable('EiBlock')->getEiBlockRoot($this->ei_scenario_id);

        if (!$this->ei_block_root) {
            return $this->createJSONErrorResponse("The version could not be created. Scenario's root block not found.");
        }

        $ei_blocks = Doctrine_Core::getTable('EiVersionStructure')->getEiBlocksTree($this->ei_block_root->getId(), EiVersionStructure::getBlockTypes());

        //création de la version
        $ei_version = new EiVersion();
        $ei_version->setEiScenarioId($this->ei_scenario_id);
        $ei_version->setEiScenarioBlocks($ei_blocks);
        $ei_version->setOldVersionId($this->ei_block_root->getEiVersionId());
        $this->form = new EiVersionForm($ei_version, array('ei_scenario_package' => new EiScenarioPackage()));

        $ei_scenario_package_form = $request->getParameter($this->form->getName());
        $ei_scenario_package_datas = $ei_scenario_package_form['ei_scenario_package'];
        if ($ei_scenario_package_datas['package_id'] != null && $ei_scenario_package_datas['package_ref'] != null):
            $this->ei_package = Doctrine_core::getTable('EiTicket')->findOneByTicketIdAndTicketRef($ei_scenario_package_datas['package_id'], $ei_scenario_package_datas['package_ref']);
            /* On recherche si pour le package par défaut et le scénario , il existe une  version */
            $this->ei_scenario_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
                    $this->ei_scenario_id, $ei_scenario_package_datas['package_id'], $ei_scenario_package_datas['package_ref']);
        else: //Erreur dû au fait qu'on ne permet pas la création d'une version sans spécification de package
            throw new Exception('No package set for version !');
        endif;

        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    /**
     * Format les réponses JSON de succès.
     * 
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($action, $status, $content = "") {

        $JSONResponse['status'] = $status;
        $JSONResponse['message'] = "Version " . $this->ei_block->getName() . " has been $action successfully.";
        $JSONResponse['content'] = $content;

        return $JSONResponse;
    }

    /**
     * Format les réponses JSON de succès.
     * 
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONErrorResponse($message) {
        $this->getResponse()->setContentType('application/json');

        $JSONResponse['status'] = "error";
        $JSONResponse['message'] = $message;

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Effectue la vérification et l'assignation des paramètres de l'url.
     * @param sfWebRequest $request
     */
    protected function getUrlParameters(sfWebRequest $request) {
        $this->root_id = $request->getParameter('root_id');
    }

    /*
     * Cette action permet de rediriger vers la version du scénario correspondant au profil courant
     */

    public function executeEditVersionWithoutId(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        //On recherche une association version-profil pour le scénario 
        $this->profil_scenario = Doctrine_Core::getTable("EiProfilScenario")->findOneByEiScenarioIdAndProfileIdAndProfileRef(
                $this->ei_scenario->getId(), $this->ei_profile->getProfileId(), $this->ei_profile->getProfileRef());
        /* Si le profil a été récemment ajouté sur script, alors il n'est affecté à aucune version ,
         *  du coup on affecte le profil à la première version rencontrée
         */
        if ($this->profil_scenario == null) {
            $pf = Doctrine_Core::getTable("EiProfilScenario")
                    ->findByEiScenarioId($this->ei_scenario->getId())
                    ->getFirst();
            $new_profil_scenario = new EiProfilScenario();
            $new_profil_scenario->setEiScenarioId($this->ei_scenario->getId());
            $new_profil_scenario->setProfileId($this->ei_profile->getProfileId());
            $new_profil_scenario->setProfileRef($this->ei_profile->getProfileRef());
            $new_profil_scenario->setEiVersionId($pf->getEiVersionId());
            $new_profil_scenario->save();
            $this->profil_scenario = $new_profil_scenario;
        }
        $this->forward404If(!$this->profil_scenario, 'System error : Environment must be associated to at least one version
                of test suit');
        //On récupère la version 
        $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($this->profil_scenario->getEiVersionId());
        // On redirige ensuite l'utilisateur vers la version trouvée
        $projet_edit_eiversion = $this->urlParameters;
        $projet_edit_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
        $projet_edit_eiversion['action'] = 'edit';
        $projet_edit_eiversion['ei_version_id'] = $this->ei_version->getId();
        $this->redirect($this->generateUrl('projet_edit_eiversion', $projet_edit_eiversion));

        return sfView::NONE;
    }

    /* Lier une version de scénario à un package */

    public function executeLinkVersionToDefaultPackage(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        $this->searchDefPackAndRedirect();
        
        /* On récupère les profils de la version du package par défaut et on les associe à la nouvelle version */
        $versionOfDefaultPack=Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
                $this->ei_scenario->getId(),$this->defPack->getTicketId(),$this->defPack->getTicketRef());
        $conn = Doctrine_Manager::connection();
        $conn->beginTransaction();
        try {
        if($versionOfDefaultPack!=null):   
            $conn->execute("update ei_profil_scenario set ei_version_id=".$this->ei_version->getId()." where ei_scenario_id=".$this->ei_scenario->getId()." and ei_version_id=".$versionOfDefaultPack->getEiVersionId());
         
        endif;
        /* On cree l'objet EiScenarioPackage en le rattachant à a version */
        $ei_scenario_package = new EiScenarioPackage();
        $ei_scenario_package->setEiScenarioId($this->ei_scenario->getId());
        $ei_scenario_package->setEiVersionId($this->ei_version->getId());
        $ei_scenario_package->setPackageId($this->defPack->getTicketId());
        $ei_scenario_package->setPackageRef($this->defPack->getTicketRef());
        $ei_scenario_package->save($conn);
        $conn->commit();
        }
        catch (Exception $e) {
                $conn->rollback();
                $this->getUser()->setFlash('alert_version_form', array('title' => 'Error! ',
                    'class' => 'alert-error',
                    'text' => "Error occur when trying to link version and default package ..."));
            }
        /* Redirection */
        $projet_edit_eiversion = $this->urlParameters;
        $projet_edit_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
        $projet_edit_eiversion['action'] = 'edit';
        $projet_edit_eiversion['ei_version_id'] = $this->ei_version->getId();
        $this->redirect($this->generateUrl('projet_edit_eiversion', $projet_edit_eiversion));
    }

    /* Edition d'une version de scénario à partir du package associé */
    public function executeEditVersionWithPackage(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        //On recherche la version correspondante au package renseigné puis on redirige vers l'action d'edition classque
        if (($package_id = $request->getParameter('package_id')) != null && ($package_ref = $request->getParameter('package_ref')) != null):
            if (($ei_scenario_id = $request->getParameter('ei_scenario_id')) != null):
                $ei_scenario_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
                        $ei_scenario_id, $package_id, $package_ref);
                if ($ei_scenario_package != null):
                    $request->setParameter('ei_version_id', $ei_scenario_package->getEiVersionId());
                    $projet_edit_eiversion = $this->urlParameters;
                    $projet_edit_eiversion['ei_scenario_id'] = $ei_scenario_id;
                    $projet_edit_eiversion['ei_version_id'] = $ei_scenario_package->getEiVersionId();
                    $projet_edit_eiversion['action'] = "edit";
                    $this->redirect($this->generateUrl("projet_edit_eiversion", $projet_edit_eiversion));
                //$this->executeEdit($request);
                else:
                    throw new Exception('Version not found ...');
                endif;
            else:
                throw new Exception('Scenario not found ...');
            endif;
        else:
            throw new Exception('Package not found ...');
        endif;
        $this->setTemplate('edit');
    }

    /**
     * Renvoi la page d'édition d'une version.
     * @param sfWebRequest $request
     */
    public function executeEdit(sfWebRequest $request) {
        $this->getUrlParameters($request);
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils
        /* Récupération des profils actifs sur une version de scénario */
        $this->actifs_version_profiles = Doctrine_Core::getTable('EiVersion')->getProfils($this->ei_version->getId(), $this->ei_scenario->getId());

        /* Récupération d'une éventuelle relation scenario-package-version */
//        $this->ei_scenario_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndEiVersionId($this->ei_scenario_id, $this->ei_version_id);
//        if ($this->ei_scenario_package):
//            $this->ei_package = Doctrine_core::getTable('EiTicket')->findOneByTicketIdAndTicketRef($this->ei_scenario_package->getPackageId(), $this->ei_scenario_package->getPackageRef());
//            //On récupère le bug lié au package si ce dernier n'est pas null
//            if ($this->ei_package != null):
//                /* On recherche tous les bugs ayant le package , afin de verifier une incohérence potentielle du système */
//                $this->defaultPackageSubjects = Doctrine_Core::getTable('EiSubject')->findByPackageIdAndPackageRefAndProjectIdAndProjectRef(
//                        $this->ei_package->getTicketId(), $this->ei_package->getTicketRef(), $this->ei_project->getProjectId(), $this->ei_project->getRefId());
//                if (count($this->defaultPackageSubjects) > 1): //Plusieurs sujets avec la même intervention.On déclenche une alerte système.
//                    throw new Exception('Please contact administrator.There many intervention with the same package ...');
//                elseif (count($this->defaultPackageSubjects) == 1):
//                    $this->ei_subject = $this->defaultPackageSubjects->getFirst(); 
//                endif;
//            endif;
//        endif;
        $this->ei_scenario_package=Doctrine_Core::getTable('EiScenarioPackage')->getDefaultInterventionWithScenarioVersion($this->ei_project ,$this->ei_version);
        
        
        /* Récupération de l'intervention par défaut avec la récupération de l'objet EiScenarioPackage marquant la relation entre le scenario et la version */
        //$this->defaultPackage = $this->getDefaultIntervention($this->ei_user, $this->ei_project);
        $this->defaultPackage = $this->getDefaultInterventionWithScenarioVersion($this->ei_user, $this->ei_project,$this->ei_version); 
//        if ($this->defaultPackage != null): 
//            //Récupération de la liaison éventuelle entre le package par défaut et le scénario
//            $this->ei_scenario_default_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
//                    $this->ei_scenario_id, $this->defaultPackage['package_id'], $this->defaultPackage['package_ref']);
//        endif;
//       
        //Récupération des versions du scénario
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                ->findByEiScenarioId($this->ei_scenario->getId());

        //si requete AJAX renvoi sous format JSON.
        if ($block_root_id = $request->getParameter('ei_block_root_id')) {
            $this->ei_version_structure = Doctrine_Core::getTable('EiVersionStructure')
                    ->findOneByEiVersionIdAndId($this->ei_version_id, $block_root_id);
        } else {
            $this->ei_version_structure = Doctrine_Core::getTable('EiVersionStructure')
                    ->getEiVersionStructureRoot($this->ei_version_id);
        }

        $this->forward404Unless($this->ei_version_structure);

        $this->form = new EiVersionForm($this->ei_version, array('ei_version_id' => $this->ei_version_id));
        $this->children = Doctrine_Core::getTable('EiVersionStructure')->getEiVersionStructureChildren($this->ei_version_structure->getId());
        $this->fonctionsForms = array();
        $this->fonctions = Doctrine_Core::getTable('EiFonction')->getFullFonctions($this->ei_version_structure, $this->defaultPackage);

        $params = Doctrine_Core::getTable('EiBlockParam')->createQuery('p')
                ->where('p.root_id = ? ', $this->ei_version_structure->getRootId())
                ->andWhere('p.level <= ?', $this->ei_version_structure->getLevel() + 1)
                ->orderBy('p.lft')
                ->execute();

        $this->ei_block_parameters = $this->ei_version_structure->getJSONParameters($params);

        $blockParams = $this->ei_version_structure->getParams();

        if ($this->ei_version_structure instanceof EiBlockForeach) {
            $this->formEditBlockParams = new EiBlockForeachForm($this->ei_version_structure, array(
                "size" => count($blockParams),
                "elements" => $blockParams,
                "mapping" => $this->ei_version_structure->getIteratorMapping()
            ));
        } else {
            $this->formEditBlockParams = new EiBlockForm($this->ei_version_structure, array(
                "size" => count($blockParams),
                "elements" => $blockParams
            ));
        }

        $j = 0;
        foreach ($this->children as $i => $child) {
            /** @var EiVersionStructure $child */
            if ($child->isEiFonction()) {
                /** @var EiFonction $fct */
                $fct = $this->fonctions[$j++];

                $this->fonctionsForms[$i] = new EiFonctionForm($fct, array(
                    'params' => $fct->getEiParams(),
                    'mappings' => $fct->getEiFunctionMapping()
                ));
            }
        }

        $this->ei_version_structure_id = $this->ei_version_structure->getId();

        $this->paramsForUrl = $this->urlParameters;
        $this->paramsForUrl['default_notice_lang'] = $this->ei_project->getDefaultNoticeLang();


        Doctrine_Core::getTable('EiScenarioOpenedBy')
                ->setOpenedBy($this->ei_scenario->getId(), $this->getUser()->getGuardUser()->getEiUser());


        //Récupération du chemin firefox dans les settings utilisateur
        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
                ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefoxPath = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        // Récupération du nom du projet.
        /** @var EiProjet $projet */
        $projet = Doctrine_Core::getTable("EiProjectUser")->getEiProjet($this->project_id, $this->project_ref, $this->getUser()->getGuardUser()->getEiUser());
        //=> 404 si le projet n'est pas retrouvé.
        $this->forward404Unless($projet);
        //=> Affectation du nom.
        $this->project_name = $projet->getName();

        // Récupération du jeu de données pré-sélectionné pour le "play".
        $cookieJddsScenarios = $request->getCookie(sfConfig::get("app_nomcookiejddscenariosplay"));
        $jddScenarioToPlay = json_decode($cookieJddsScenarios, true);
        // On ajoute le sous-tableau relatif au scénario contenant l'id et le nom du jeu de données sélectionné précédemment.
        $this->jddScenarioToPlay = isset($jddScenarioToPlay[$this->ei_scenario->getId()]) ? $jddScenarioToPlay[$this->ei_scenario->getId()] : array();

        //cas d'un changement de block
        if ($request->isXmlHttpRequest()) {
            $this->paramsForUrl['action'] = 'update';
            $this->paramsForUrl['ei_scenario_id'] = $this->ei_scenario->getId();
            $this->paramsForUrl['ei_version_id'] = $this->ei_version_id;
            $this->is_editable=true;
            $this->getResponse()->setContentType('application/json');
            $this->renderPartial('formContent');
            $JSONResponse['propertiesAndParams'] = $this->getComponent("block", "showParams", array(
                "form" => $this->formEditBlockParams,
                'project_id' => $this->project_id,
                'project_ref' => $this->project_ref,
                'profile_name' => $this->profile_name,
                'profile_id' => $this->profile_id,
                'profile_ref' => $this->profile_ref,
                'ei_version_structure_id' => $this->ei_version_structure->getId(),
                'ei_version_id' => $this->ei_version->getId(),
                'treeDisplay' => $this->treeDisplay,
                'ei_scenario_id' => $this->ei_version_structure->getEiVersion()->getEiScenarioId(),
            ));
            $JSONResponse['content'] = $this->getResponse()->getContent();
            $JSONResponse['status'] = "ok";
            $JSONResponse['path'] = "<li>" . $this->ei_version->getLibelle() . '<span class="divider">/</span> </li>';
            $JSONResponse['path'] .= $this->ei_version_structure->getPathTo();

            $this->getResponse()->setContent('');

            return $this->renderText(json_encode($JSONResponse));
        }
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        $this->getUrlParameters($request);
        $this->form = new EiVersionForm($this->ei_version);
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')->findByEiScenario($this->ei_scenario->id);

        $this->getResponse()->setContentType('application/json');

        return $this->processForm($request, $this->form, false);
    }

    public function executeDelete(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        //if(!$request->isXmlHttpRequest()) $request->checkCSRFProtection(); 

        if ($this->ei_version->delete() == -1)
            $this->forward404('Remove all environments affected on version before delete');

        $this->getUrlParameters($request);

        $this->getUser()->setFlash('msg_success', "Version deleted successfully.");

        $projet_new_eiversion = $this->urlParameters;
        $projet_new_eiversion['action'] = 'editVersionWithoutId';
        $projet_new_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
        return $this->redirect("projet_new_eiversion", $projet_new_eiversion);
    }

    public function executeCreateClone(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        $this->getUrlParameters($request);
        $this->defaultPackage = $this->getDefaultPackage($this->ei_user, $this->ei_project);
        if ($this->defaultPackage != null):
            //on vérifie que les identiefiants du package envoyé correspondent à ceux du package par défaut 
            if ($this->defaultPackage->getTicketId() != intval($request->getParameter('package_id')) || $this->defaultPackage->getTicketRef() != intval($request->getParameter('package_ref'))):
                $this->forward404("Default package and receive package aren\'t the same...");
            endif;
        else: //On s'assure que le package par défaut n'ait pas été detruit avant l'envoi de la requête
            $this->forward404("Default package has been set to null before processing...");
        endif;
        /* A ce niveau , tout s'est bien passé : on crée la copie et on la lie au package par défaut */
        //$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT) || $request->isXmlHttpRequest());
        $this->forward404Unless($request->getParameter('new_name'), "Missing version's name");

        $conn = Doctrine_Manager::connection();
        try {
            $conn->beginTransaction();
            $result = $this->ei_version->createCopie($request->getParameter('new_name'));
            if ($result != null):
                /* Création des relations profil-scenario pour la nouvelle version */
                //On recherche une éventuelle version associée au package par défaut
                $ei_scenario_default_package = Doctrine_Core::getTable('EiScenarioPackage')->findOneByEiScenarioIdAndPackageIdAndPackageRef(
                        $this->ei_scenario->getId(), $this->defaultPackage->getTicketId(), $this->defaultPackage->getTicketRef());
                if ($ei_scenario_default_package != null):
                    //On met à jour tous les éléments de la table EiProfilScenario (en changeant le champs ei_version_id 
                    Doctrine_Query::create()
                            ->update('EiProfilScenario ps')
                            ->set('ps.ei_version_id', '?', $result->getId())
                            ->where('ps.ei_scenario_id=' . $this->ei_scenario->getId() . ' And ps.ei_version_id=' . $ei_scenario_default_package->getEiVersionId())
                            ->execute();
                endif;
                //On associe le profil courant à la version clonée
                Doctrine_Query::create()
                        ->update('EiProfilScenario ps')
                        ->set('ps.ei_version_id', '?', $result->getId())
                        ->where('ps.ei_scenario_id=' . $this->ei_scenario->getId() . ' And ps.profile_id=' . $this->ei_profile->getProfileId() . ' And ps.profile_ref=' . $this->ei_profile->getProfileRef())
                        ->execute();

                //            On lie la copie au package par défaut
                $ei_scenario_package = new EiScenarioPackage();
                $ei_scenario_package->setEiScenarioId($this->ei_scenario->getId());
                $ei_scenario_package->setEiVersionId($result->getId());
                $ei_scenario_package->setPackageId($this->defaultPackage->getTicketId());
                $ei_scenario_package->setPackageRef($this->defaultPackage->getTicketRef());
                $ei_scenario_package->save($conn);


            endif;
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }


        $this->getUser()->setFlash("version_success", "Version has been cloned.");
        $paramsForRedirection = $this->urlParameters;
        $paramsForRedirection['ei_scenario_id'] = $request->getParameter("ei_scenario_id");
        $paramsForRedirection['ei_version_id'] = $result->getId();
        $paramsForRedirection['action'] = 'edit';

        return $this->redirect($this->generateUrl('projet_edit_eiversion', $paramsForRedirection));
    }

    //Récupération des notices de fonction liées à une version du scénario

    public function executeShowNotice(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
        //Récupération des paramètres de requete 
        $this->getRequestParams($request);


        if ($this->ei_version == null) {//Si la version spécifiée n'est pas retrouvée alors on la recherche par rapport au profil renseigné
            $this->profil_scenario = Doctrine_Core::getTable("EiProfilScenario")->findOneByEiScenarioIdAndIdProfil($this->ei_scenario_id, $this->profile_id);
            $this->forward404If(!$this->profil_scenario, 'Version Introuvable');
            $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($this->profil_scenario->getEiVersionId());
        }
        //Rechargement du projet si nécessaire
        if ($this->ei_project->needsReload())
            $this->ei_project->transactionToLoadObjectsOfProject(
                    $this->ei_project->downloadKalFonctions($request));

        //Vérification de l'existence des objets 
        //Récupération des langues du projet
        $this->projectLanguages = $this->ei_project->getProjectLangs();

        $this->list_notices = Doctrine_Core::getTable('EiNotice')
                ->getNoticePage($this->ei_version, $this->ei_project, $this->ei_profile, $this->lang);


        $this->ei_versions = Doctrine_Core::getTable('EiVersion')->findByEiScenarioId($this->ei_scenario->id);
        $this->setLayout('layout_notice'); //Second décorateur pour les fichiers de simple présentation
    }

    protected function processForm(sfWebRequest $request, sfForm $form, $redirect = true) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) :
            $setDefaultProfiles=false;
            if ($form->getObject()->isNew()):
                $setDefaultProfiles=true;
            endif;
            $ei_version = $form->save();
            /* Si la version est nouvellement créée , on créee les associations entre la nouvelle version et les profils */
            if ($setDefaultProfiles):     
                    //On met à jour tous les éléments de la table EiProfilScenario (en changeant le champs ei_version_id 
                    Doctrine_Query::create()
                            ->update('EiProfilScenario ps')
                            ->set('ps.ei_version_id', '?', $ei_version->getId())
                            ->where('ps.ei_scenario_id=' . $this->ei_scenario->getId() . ' And (ps.ei_version_id=' . ($this->ei_scenario_package?$this->ei_scenario_package->getEiVersionId():0).
                                    ' Or ps.profile_id=' . $this->ei_profile->getProfileId() . ' And ps.profile_ref=' . $this->ei_profile->getProfileRef().')')
                            ->execute(); 
                   
            endif;
                
            if ($redirect) :
                if ($form->getObject()->isNew()):

                    $message = "Version has been created successfully.";
                else :
                    $message = "Version has been saved successfully.";
                endif;
                $this->getUser()->setFlash('alert_version_form', array('title' => 'Success! ',
                    'class' => 'alert-success',
                    'text' => $message));

                $this->getUser()->setFlash('version_success', $message);
                $projet_edit_eiversion = $this->urlParameters;
                $projet_edit_eiversion['ei_scenario_id'] = $this->ei_scenario->getId();
                $projet_edit_eiversion['ei_version_id'] = $ei_version->id;
                $projet_edit_eiversion['action'] = 'edit';
                return $this->redirect('projet_edit_eiversion', $projet_edit_eiversion);
            else :

                $JSONResponse['status'] = "ok";
                $JSONResponse['message'] = "The version has been saved successfully.";

                return $this->renderText(json_encode($JSONResponse));
            endif;
        else :
            $this->getUser()->setFlash('alert_version_form', array('title' => 'Error !',
                'class' => 'alert-danger',
                'text' => 'Error occur when saving version ...'));
            $JSONResponse['status'] = "error";

            $JSONResponse['message'] = $form->renderGlobalErrors();

            return $this->renderText(json_encode($JSONResponse));
        endif;
    }

    //Récupération des paramètres de requete
    public function getRequestParams(sfWebRequest $request) {

        $this->ei_scenario_id = $request->getParameter('ei_scenario_id');
        $this->ei_version_id = $request->getParameter('ei_version_id');
        $this->lang = $request->getParameter('lang');

        $this->forward404If(!$this->ei_scenario_id, 'Test suit not found');
        $this->forward404If(!$this->ei_version_id, 'Version not found');
        $this->forward404If(!$this->lang, 'Language was not specified');


        //Recherche des objets concernés
        $this->ei_scenario = Doctrine_Core::getTable("EiScenario")->findOneById($this->ei_scenario_id);
        $this->ei_version = Doctrine_Core::getTable("EiVersion")->findOneById($this->ei_version_id);
        $this->user = $this->getUser();

        $this->forward404If(!$this->ei_scenario, 'Test Suit not found');
        $this->forward404If(!$this->ei_version, 'Version not found');
        $this->forward404If(!$this->user, 'You are not connected was not specified');
    }

}
