<?php

/**
 * eiscenario actions.
 *
 * @package    kalifast
 * @subpackage eiscenario
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiscenarioActions extends sfActionsKalifast {
 
    public function preExecute() {
        parent::preExecute();

        // Récupération de l'utilisateur.
        $this->guard_user = $this->getUser()->getGuardUser();
        $this->ei_user = $this->guard_user->getEiUser();
    }
    //Recherche d'un scénario avec les paramètres de requête
    public function checkEiScenario(sfWebRequest $request,EiProjet $ei_project) {
        if (($this->ei_scenario_id = $request->getParameter('ei_scenario_id')) != null ) {
            //Recherche du scénario en base
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                    ->findOneByIdAndProjectIdAndProjectRef(
                            $this->ei_scenario_id,$ei_project->getProjectId(),$ei_project->getRefId());
            //Si le scénario n'existe pas , alors on retourne un erreur 404
            if ($this->ei_scenario == null){ 
                $message = 'Scénario  introuvable!! l identificateur n\'est pas spécifié';
                $request->setParameter('msg', $message);
                $request->setParameter('back_link', $request->getReferer());
                $this->forward('erreur', 'error404');
            }
        }

        else {
            $this->forward404('Missing scenario parameters  ...');
        }
    } 
    
    protected function initReloadFunctionVariable(sfWebRequest $request) {

        // Initialisation des variables de rechargement de la fonction

        if ($request->getParameter('login') != null)
            $this->login = $request->getParameter('login');
        else
            $this->login = null;

        if ($request->getParameter('pwd') != null)
            $this->pwd = $request->getParameter('pwd');
        else
            $this->pwd = null;

        if ($this->login == null || $this->pwd == null)
            $this->forward404('Login ou mot de masse manquant.');
    }
 
    public function executeDownload(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        //Suppression des fichiers du repertoire web/download
        $download_dir = sfContext::getInstance()->getConfiguration()->getRootDir() . '/web/download/';
        MyFunction::deleteDownloadFiles($download_dir);
        //paramètres de creation du scénario
        $ei_scenario_id = $request->getParameter('ei_scenario_id');

        $profile_id = $request->getParameter('profile_id');
        $profil_scenario = Doctrine_Core::getTable('EiProfilScenario')->findOneByEiScenarioIdAndProfileId($ei_scenario_id, $profile_id);
        if ($profil_scenario == null) {
            $this->error1 = 'error1';
        } else {
            $id_version = $profil_scenario->id_version;
            //récupération de la version
            $version = Doctrine_Core::getTable('EiVersion')->findOneBy('id', $id_version);
            if ($version == null) {
                $this->error2 = 'error2';
            } else {
                //fichier du scénario
                $this->ei_scenario_id = $ei_scenario_id;
                $this->id_version = $id_version;
                $this->profile_id = $profile_id;
                if ($ei_scenario_id == null || $id_version == null || $profile_id == null) {
                    $this->error3 = 'error3';
                } else {
                    $scenario_file = Doctrine_Core::getTable('EiScenario')->generateXML($ei_scenario_id, $id_version, $profile_id);
                    if ($scenario_file == null) {
                        $this->error4 = 'error4'; //Aucune fonction pour la version et le scénario spécifiés
                    } else {
                        //récupération des fonctions
                        $q = Doctrine_Core::getTable('EiFonction')->getFonctionsByCriteria($id_version, $ei_scenario_id, null, null, null);
                        $ei_fonctions = $q->execute();

                        //$fonction_file=Doctrine_Core::getTable('EiFonction')->generateXMLForPHP($id_fonction);
                        // creation du dossier tmp à retourner à l'utilisateur
                        //mkdir("./download/tmp", 0777);
                        //creation du scenario

                        $zip = new ZipArchive(); //on crée une nouvelle instance zip
                        $generik_name = $profil_scenario->getEiProfil()->profile_name . '_' . $profil_scenario->getEiScenario()->
                                nom_scenario;
                        if ($profile_id != null) {
                            $this->nom_zip = $ei_scenario_id . '_' . $profile_id . '_' . $generik_name . '_' . date('H:i:s');
                        }
                        if ($zip->open('./download/' . $this->nom_zip . '.zip', ZipArchive::CREATE) === true) {
                            //création de l archive
                            // Ajout direct.
                            $zip->addEmptyDir($generik_name);
                            $zip->addFromString($generik_name . '/scenario.xml', $scenario_file);
                            //ajout des fonctions
                            foreach ($ei_fonctions as $ei_fonction) {
                                $fonction_file = Doctrine_Core::getTable('EiFonction')->generateXMLForPHP($ei_fonction->id, $profile_id, $request);
                                $zip->addFromString($generik_name . '/' . $ei_fonction->getKalFonction()->nom_fonction . $ei_fonction->id, $fonction_file);
                            }
                            $zip->close();
                            $this->redirect('http://' . $request->getHost() . '/download/' . $this->nom_zip . '.zip');
                        } else {
                            echo 'Impossible d\'ouvrir <br/>';
                            // Traitement des erreurs avec un switch(), par exemple.
                        }
                    }
                }
            }
        }
    }

    //Campagnes d'un scénario 
    public function executeGetScenarioCampaigns(sfWebRequest $request){
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $this->scenarioCampaigns=$this->ei_scenario->getCampaigns();
    }
    //Récupération de l'oracle le plus récent d'un scénario
    public function executeGetLastOracle(sfWebRequest $request) { 
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $this->EiScenarioTestsSet= Doctrine_Query::create()->from('EiTestSet')
                ->where('ei_scenario_id='.$this->ei_scenario->getId())
                ->orderBy('created_at desc')
                ->execute();

        if(count($this->EiScenarioTestsSet)>0):
            $this->redirect($this->generateUrl('eitestset_oracle', array(
                'project_id' => $this->project_id,
                'project_ref' => $this->project_ref,
                'ei_test_set_id'=> $this->EiScenarioTestsSet->getFirst()->getId(),
                'ei_scenario_id'=>$this->ei_scenario_id,
                'profile_id' => $this->profile_id,
                'profile_ref' => $this->profile_ref,
                'profile_name' => $this->profile_name
            )));
        else: 
            $this->getUser()->setFlash('no_oracle', 'No oracle because scenario hasn\'t be played ...' );
            $this->redirect($request->getReferer());
        endif; 
        return sfView::NONE;
    }
    
    
    public function executeDownloadJDT(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $id_version = $request->getParameter('id_version'); 
        if (isset($id_version)) { 
            $this->getResponse()->setContentType('text/xml');

            $this->getResponse()->setHttpHeader('Content-disposition', 'attachment; filename.xml'); 
            $this->eiVersion = $this->ei_scenario->getRootVersion($id_version);

            $this->forward404Unless($this->eiVersion, "Aucune version assignée.");
        }
    }

    public function executeProfilOfScenario(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $this->checkEiVersion($request, $this->ei_scenario);
        $this->ei_profiles = $this->ei_project->getProfils(); //Récupération des profils
        /* Récupération des profils actifs sur une version de scénario*/
        $this->actifs_version_profiles=Doctrine_Core::getTable('EiVersion')->getProfils($this->ei_version->getId(), $this->ei_scenario->getId());
         
        return $this->renderPartial('profilOfScenario',array(
            'ei_scenario' => $this->ei_scenario,
            'ei_version' => $this->ei_version,
            'ei_profiles' => $this->ei_profiles,
            'actifs_version_profiles' => $this->actifs_version_profiles
        ));
    }

    public function executeIndex(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        $this->recent_scenarios = Doctrine_Core::getTable('EiScenarioOpenedBy')->getLastOpenedForCurrentProject(
                $this->getUser()->getGuardUser()->getEiUser(),$this->project_id,$this->project_ref);
        $this->root_folder=$this->ei_project->getRootFolder();
        $this->opened_ei_nodes = Doctrine_Core::getTable('EiTreeOpenedBy')
                ->getOpenedNodes($this->getUser()->getGuardUser()->getEiUser(),
                        $this->project_ref,
                        $this->project_id);
    }

    //Récupération de l'arbre des jeux de données d'un scénario
    public function executeGetScenarioDataSets(sfWebRequest $request){
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        $this->checkEiScenario($request,$this->ei_project);
        if(!is_array($this->urlParameters)) throw new Exception('Missing Uri parameters'); 
        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $this->urlParameters['step_id'] = $request->getParameter('step_id');
        $this->urlParameters['choose_dataset'] = $request->getParameter('choose_dataset');

        $this->is_edit_step_case= $this->urlParameters['step_id'] !== null && $this->urlParameters['step_id'] != "undefined";
        $this->is_select_data_set = $this->urlParameters['choose_dataset'] !== null;

        $node = $this->ei_scenario->getEiNode();

        $this->ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                ->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');

        $this->forward404Unless($this->ei_data_set_root_folder);

        $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                ->findByRootId($this->ei_data_set_root_folder->getId());
        
        return $this->renderPartial('eidataset/root');
    }
    
    public function executePlayOnRobot(sfWebRequest $request) {
        
    }

    public function executeGenerateXML(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $ei_scenario_id = $request->getParameter('ei_scenario_id');
        $id_version = $request->getParameter('id_version');

        if (($ei_scenario_id == null) || ($id_version == null)) { // si le scenario ou la version n'est pas récupérée
            $this->xmlfile = null; // on renvoie un fichier xml vide
        } else { //sinon on construit le fichier xml
            $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($ei_scenario_id);
            $this->forward404Unless(Doctrine_Core::getTable("EiProjectUser")->getEiProjet($this->ei_scenario->getProjectId(), $this->getUser()->getGuardUser()->id));

            $this->xmlfile = Doctrine_Core::getTable('EiScenario')->generateXML($ei_scenario_id, $id_version);
            $this->ei_scenario_id = $ei_scenario_id;
            $this->id_version = $id_version;
        }
    }

    /**
     * Génère et retourne le XSD du scénario.
     * @param sfWebRequest $request
     */
    public function executeGenerateXSD(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $ei_scenario_id = $request->getParameter('ei_scenario_id');

        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($ei_scenario_id);

        $this->ei_scenario->generateXSD();

        $this->forward404Unless($this->ei_scenario);

        $this->forward404Unless(Doctrine_Core::getTable("EiProjectUser")
                        ->getEiProjet($this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $this->getUser()->getGuardUser()->getEiUser()));

        $xsd = $this->ei_scenario->generateXSD();

        $response = $this->getResponse();

        $response->setContentType('text/xml');
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="' . $this->ei_scenario->getNomScenario() . '-XSD.xsd');
        $response->setContent($xsd);

        return sfView::NONE;
    }

    public function executeShow(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request, $this->ei_project); 
 
    }

    public function executeNew(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project); 
        $this->root_id = $request->getParameter('root_id'); 
        $ei_scenario = new EiScenario(null,false,$this->ei_project); 
        /* On recherche le package par défaut. S'il n'est pas définit , on le spécifie à l'utilisateur au lieu  de lui retourner le formulaire */
        $this->defPack=Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
               $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$this->ei_user->getUserId(),$this->ei_user->getRefId() );
        if($this->defPack==null): // Alert à lever si le package par défaut n'est pas définit
            $this->getUser()->setFlash('alert_scenario_form', array('title' => 'Warning  ',
                    'class' => 'alert-warning',
                    'text' => 'You have to select package before create à test suite ...'));
        endif;
        if ($this->root_id)
            $this->form = new EiScenarioForm($ei_scenario, array('root_id' => $this->root_id));
        //Récupération du noeud parent et du chemein jusqu'à de dernier
        $node_parent = Doctrine_Core::getTable('EiNode')->find($this->root_id);
        $this->forward404Unless($node_parent, "Node " . $this->root_id . "Node parent not found.");

        $this->chemin = $node_parent->getPathTo();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isXmlHttpRequest());
        $this->redirect_after_save= $request->getParameter('redirect_after_save');
        $this->ei_scenario = new EiScenario();
        $this->ei_scenario->setProject($this->ei_project); 

        $this->form = new EiScenarioForm($this->ei_scenario, array('root_id' => $this->root_id));
        //Récupération du noeud parent et du chemein jusqu'à de dernier
        $node_parent = Doctrine_Core::getTable('EiNode')->find($this->root_id);
        $this->forward404Unless($node_parent, "Node " . $this->root_id . "Node parent not found.");

        $this->chemin = $node_parent->getPathTo();
        
        $this->processForm($request, $this->form);
        if($request->isXmlHttpRequest()):
        if($this->result): //Tout s'est bien passé mais l'utilisateur ne souhaite pas etre rediriger
            if(!$this->redirect_after_save):
            $nodeLine=$this->urlParameters ;
                      $nodeLine['ei_node']=$this->ei_scenario->getNode(); 
                      $nodeLine['is_step_context']=false ;  
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('einode/nodeLine', $nodeLine),
                        'flash_box' => $this->getPartial('global/alertBox',array(  'flash_string' => 'alert_scenario_form' )),
                        'success' => true)));
            else:  
                return $this->renderText(json_encode(array(
                        'html' => $this->redirectUri,
                        'redirect_after_save' => true,
                        'success' => true)));
            endif;
        else:
            $url_form=$this->urlParameters;
                    $url_form['ei_scenario']=$this->ei_scenario;
                    $url_form['root_id']=$this->root_id;
                    $url_form['form']=$this->form; 
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('form', $url_form),
                        'success' => false)));
        endif;
            else:  
            return $this->setTemplate('new');
        endif;
         return sfView::NONE;
    }

    //Creation du xsl de la notice d'un scénario
    public function executeGenerateXslNotice(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        //Récupération des paramètres nécessaires pour la génération du xsl
        $this->root_id = $request->getParameter('root_id'); 
        $this->checkEiScenario($request, $this->ei_project);
        //Recherche des objets en base 
           $this->profil_scenario = Doctrine_Core::getTable('EiProfilScenario')->findOneByProfileIdAndProfileRefAndEiScenarioId(
                $this->ei_profile->getProfileId(), $this->ei_profile->getProfileRef(), $this->ei_scenario->getId());
        $this->ei_version = Doctrine_Core::getTable('EiVersion')->findOneById($this->profil_scenario->getEiVersionId());

        $this->xslresult = Doctrine_Core::getTable('EiScenario')->generateXslJeuTestForNotice(
                $this->ei_version, $this->ei_profile, $this->ei_scenario, $this->ei_project);
    }
 
    protected function getScenarioExecution(EiProjet $ei_project,EiScenario $ei_scenario){ 
        $q=Doctrine_Core::getTable('EiLogFunction')->getLastScenarioExecution($ei_project,$ei_scenario)->execute();
        if(count($q) > 0) return $q->getFirst();
        return null;
    }
    /* Récupération des statistiques sur un scénario */
    public function executeStatistics(sfWebRequest $request){
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project); 
        $this->checkEiScenario($request, $this->ei_project);
        $this->scenarioStats=$this->ei_scenario->getScenarioStats();
        /* Récupération de la dernière execution de scénario */
       $this->eiLogFunction= $this->getScenarioExecution($this->ei_project,$this->ei_scenario);
       if($this->eiLogFunction!=null):
        $this->lastEiLogFunctionLines = Doctrine_Query::create()->from('EiLogFunction elf ')
                ->where(" elf.ei_log_id=? And   elf.ei_test_set_id=? And elf.ei_scenario_id= ?", array(
                    $this->eiLogFunction->getEiLogId(), $this->eiLogFunction->getEiTestSetId(), $this->ei_scenario->getId()
                ))
                ->orderBy('elf.position ASC ')
                ->execute();
        //On construit le tableau à exploiter au niveau des graphes
        $this->lastExTab = Array();
        if(count($this->lastEiLogFunctionLines)):
            foreach ($this->lastEiLogFunctionLines as $elfl):
                $this->lastExTab[] = array($elfl->getPosition(), $elfl->getDuree());
            endforeach;
        endif;
        endif; 
    }
    
    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        $this->checkEiScenario($request, $this->ei_project);

        $this->forward404Unless($this->ei_scenario);

        //On n'oubli pas de renvoyer le chemin vers le scénario
        //$this->evaluatePathToScenario($this->ei_scenario);
        //Récupération des versions du scénario
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                ->findByEiScenarioId($this->ei_scenario->getId());
         
        $this->form = new EiScenarioForm($this->ei_scenario);

        //Récupération du chemin firefox dans les settings utilisateur
        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
            ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefoxPath = $this->user_settings == null ? : $this->user_settings->getFirefoxPath(); 
 
    }
    
    public function executeEditStructure(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        $this->checkEiScenario($request, $this->ei_project);  
        //On n'oubli pas de renvoyer le chemin vers le scénario
        //$this->evaluatePathToScenario($this->ei_scenario);
        //Récupération des versions du scénario
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')
                ->findByEiScenarioId($this->ei_scenario->getId());
        
        $this->ei_block_root = Doctrine_Core::getTable('EiBlock')
                ->getEiBlockRoot($this->ei_scenario->getId());

        $this->forward404Unless($this->ei_block_root && $this->ei_block_root->getNode()->isRoot());

        $this->ei_block_parameters = Doctrine_Core::getTable('EiBlockParam')
                ->findByLevelAndEiVersionStructureParentId(1, $this->ei_block_root->getId());

        if ($this->ei_block_parameters) {
            $aux = array();
            foreach ($this->ei_block_parameters as $ei_block_param) {
                $aux[] = new EiBlockParamForm($ei_block_param);
            }
            $this->ei_block_parameters = $aux;
        }

        $this->path = array($this->ei_block_root->getName());

        $this->ei_block_children = Doctrine_Core::getTable('EiBlock')->
                getEiBlockChildren($this->ei_block_root);

        $this->ei_block_root_form = new EiBlockForm($this->ei_block_root);

        $this->form = new EiScenarioForm($this->ei_scenario);

        Doctrine_Core::getTable('EiScenarioOpenedBy')
                ->setOpenedBy($this->ei_scenario->getId(), $this->getUser()->getGuardUser()->getEiUser());
    }
     
    public function executeRename(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request, $this->ei_project); 
        $this->new_name = $request->getParameter('new_name');
        if (!$this->new_name)
            $this->renderText('Missing parameters ! try again');
        else { 
            if ($this->ei_scenario != null && $this->ei_project != null) {
                $this->ei_scenario->setNomScenario($this->new_name);
                $this->ei_scenario->save();

                $this->renderText($this->ei_scenario->getNomScenario());
            } else {
                $this->renderText('Unreachable project or test suite ! try again');
            }
        }
        return sfView::NONE;
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT) || $request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 
        
        //récupération du scenario
        
        $this->form = new EiScenarioForm($this->ei_scenario);

        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
         $redirect = $this->urlParameters;
         $redirect['action']='index'; 
 
        /* On vérifie que le scénario n'est rattaché à aucune campagne */
        $this->linkCampaigns=Doctrine_Core::getTable('EiCampaignGraph')->findByScenarioId($this->ei_scenario->getId());
        if(count($this->linkCampaigns)>0):
            $this->getUser()->setFlash('version_echec', 'Scenario is use in some campaigns.Delete link before ...');
            $this->redirect($request->getReferer());
            else:
            $this->ei_scenario->getNode()->deleteNodeDiagram();
            $this->getUser()->setFlash('msg_success', 'The scenario has been deleted successfully.');
            return $this->redirect($this->generateUrl('projet_eiscenario', $redirect));
        endif;
        
    }

    /**
     * Action executée à la sélection d'un scenarios depuis la liste déroulante du menu gauche
     * @param sfWebRequest $request
     * @return Redirection
     */
    public function executeForwardToEdit(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $ei_scenario_id = $request->getGetParameter('liste_scenario_choice');
        $ei_scenario_id_fromURl = $request->getParameter('ei_scenario_id');
        if (isset($ei_scenario_id) || isset($ei_scenario_id_fromURl)) {

            if (!isset($ei_scenario_id))
                $ei_scenario_id = $ei_scenario_id_fromURl;

            $this->root_id = $request->getParameter('root_id'); 
            //modification du paramète 'ei_scenario_id' afin que l'ei_scenario_id fourni par 'liste_scenario_choice' 
            //soit propagé aux actions à suivre
            $request->setParameter('ei_scenario_id', $ei_scenario_id);
            //initialisation du tableau de paramètres pour la redirection
            // par défaut, vers l'édition.
            $paramsForRedirection = $this->urlParameters;
            $paramsForRedirection['ei_scenario_id']=$ei_scenario_id; 
            $paramsForRedirection ['action']= 'edit';
            //Si un profil a été sélectionné, l'utilisateur est redirigé vers la version du scénario
            //associée au profil.
            if ($this->profile_id != 0 && $this->profile_ref != 0) { 
                $version = $this->ei_profile->getEiVersionId($ei_scenario_id);
                if ($version) {
                    $paramsForRedirection['id_version'] = $version;
                    return $this->redirect('projet_eiscenario_action', $paramsForRedirection);
                }
                else
                    throw new Exception('Erreur critique : aucun profil associé au profil sélectionné. Contactez un administrateur.');
            }
            return $this->redirect(
                            $this->generateUrl('projet_eiscenario_action', $paramsForRedirection));
        }
        else
            $this->forward404('Scénario non trouvé.');
    } 

    public function executeCreateClone(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->root_id = $request->getParameter('root_id'); 

        if ($request->getParameter('new_name') == null || $request->getParameter('ei_scenario_id') == null)
            $this->forward404('Missing parameters : new name = ' . $request->getParameter('new_name') . ', id = ' . $request->getParameter('ei_scenario_id'));

        $scenario = Doctrine_Core::getTable('EiScenario')->findOneById($request->getParameter('ei_scenario_id'));

        if ($scenario == null)
            $this->forward404('Scenario can t found ... ' );

        $ei_scenario = $scenario->createCopie($request->getParameter('new_name'));

        $this->forward404Unless($ei_scenario != null, 'Copy failed.');
        $URIparams = $this->urlParameters;
        $URIparams['ei_scenario_id']=$ei_scenario->getId(); 
        $URIparams['action']='editVersionWithoutId';  

        $this->getUser()->setFlash("msg_success", "Scenario copied as " . $ei_scenario->getNomScenario());

        return $this->redirect($this->generateUrl("projet_new_eiversion", $URIparams));
    }
    
    //Récupération des profils actifs d'un scénario pour un package donné
    public function executeGetScenarioProfilesForPackage(sfWebRequest $request){
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request);
        $this->checkPackage($request, $this->ei_project);
        $this->checkEiScenario($request, $this->ei_project);
        // Récupération de l'utilisateur. (Pour la sauvegarde de la résolution  du conflit)
        $this->guard_user = $this->getUser()->getGuardUser();
        //Récupération de la livraison dans laquelle le conflit  a été découvert
        $this->checkDelivery($request, $this->ei_project);
        /* Résolution du conflit découvert sur la fonction */
        Doctrine_Core::getTable('EiPackageScenarioConflict')->createItem($this->ei_scenario,$this->ei_delivery,$this->ei_package,$this->guard_user);
        
        $this->ei_profiles=$this->ei_project->getProfils(); //Récupération des profils
        //Récupération des relations script-profil impliquant le ticket en question
      $this->TabScenarioProfiles=$this->ei_package->getAssociatedProfilesForScenario($this->ei_scenario);
      $this->versionsProfiles=array();
      if(!empty($this->TabScenarioProfiles)){
          foreach($this->TabScenarioProfiles as $versionProfile):
              $tab[$versionProfile['ei_scenario_id'].'_'.$versionProfile['profile_id'].'_'.$versionProfile['profile_ref']]=$versionProfile['ei_version_id'];
          endforeach; 
          $this->versionsProfiles=$tab;
      }
       
            return $this->renderText(json_encode(array(
                        'html' => $this->getPartial('eiprofilscenario/profilesForScenarioVersion',array(
                            'package_id' => $this->package_id,
                            'package_ref' => $this->package_ref,
                            'project_id' => $this->project_id,
                            'project_ref' => $this->project_ref,
                            'ei_scenario_id' => $this->ei_scenario_id ,
                            'ei_profiles' => $this->ei_profiles,
                            'versionsProfiles' => $this->versionsProfiles
                        )) ,
                        'success' => true))); 
        return sfView::NONE;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $is_update_case=false;
            if(!$form->getObject()->isNew()): $is_update_case=true; endif;
            $this->ei_scenario = $form->save();
            $URIparams = $this->urlParameters;
            $URIparams['ei_scenario_id']=$this->ei_scenario->getId(); 
            $URIparams['action']='edit';  
            //Construction du méssage flash 
                $this->getUser()->setFlash('alert_scenario_form', array('title' => 'Success ',
                    'class' => 'alert-success',
                    'text' => 'Well done ...'));

            /*Tout s'est bien passé , on vérifie qu'on est dans le cadre 
             d'une sauvegarde ajax et que l'utilisateur souhaite rester dans le contexte de création
            */
                $this->result=true;
                $this->redirectUri=$this->generateUrl('projet_eiscenario_action', $URIparams);  
                if($is_update_case) : $this->redirect($this->redirectUri); endif; 
        } else {  
            $this->result=false; 
            $this->getUser()->setFlash('alert_scenario_form', array('title' => 'Error ',
                    'class' => 'alert-danger',
                    'text' => 'Error occur when saving scenario ...'));
        }
    }

}
