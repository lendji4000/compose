<?php

/**
 * eidataset actions.
 *
 * @package    kalifastRobot
 * @subpackage eidataset
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eidatasetActions extends sfActionsKalifast
{
    /** @var sfLogger */
    private $logger;

    /** @var EiUser */
    private $user;

    /** @var string  */
//    private $login = "";

    /**
     * Méthode permettant de vérifier l'utilisateur rensigné par le Web service par le biais de tokens.
     */
    private function checkUserWebService()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );

        // On récupère le nom d'utilisateur ainsi que le mot de passe pour les intéractions avec script.
        $this->login = $this->user->getGuardUser()->getUsername();

        // On authentifie l'utilisateur s'il ne l'est pas.
        if( MyFunction::getGuard() == null )
            $this->getUser()->signIn($this->user->getGuardUser(), true);
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

    public function executeDownload(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);

        /** @var EiDataSet $ei_data_set */
        $this->forward404Unless($ei_data_set = Doctrine_Core::getTable('EiDataSet')
                ->find($request->getParameter('ei_data_set_id')));

        $this->forward404Unless($ei_data_set->getEiNode()->getProjectRef() == $this->project_ref && $ei_data_set->getEiNode()->getProjectId() == $this->project_id);

        $xml = $ei_data_set->generateXML();

        $this->getResponse()->setContentType('text/xml');

        $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="' . $ei_data_set->getName() . '.xml');

        return $this->renderText($xml);
    }

    public function executeIndex(sfWebRequest $request) {


        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);

        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $node = $this->ei_scenario->getEiNode();

        $this->ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                ->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');

        $this->forward404Unless($this->ei_data_set_root_folder);

        $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                ->findByRootId($this->ei_data_set_root_folder->getId());
    }

    private function checkParametersAsJSON(sfWebRequest $request, $form = null) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);

        $this->parent_id = $request->getParameter('parent_id');

        // TODO: Adaptation DataSetTemplate.
        $folder = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($this->parent_id, EiNode::$TYPE_DATASET_FOLDER);

        $JSONResponse['status'] = "error";

        //le dossier parent existe
        if ($folder) {
            $this->checkEiScenario($request,$this->ei_project);
            //le scenario existe

            $this->urlParameters['parent_id'] = $folder->getId();
            $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();

            // TODO: Modification du formulaire afin d'adapter aux templates.
            //le projet existe pour l'utilisateur
            $this->form = $form == null ? new EiDataSetTemplateForm(null, array('ei_node_parent' => $folder)):$form;

            $JSONResponse['status'] = "ok";

            $this->renderPartial("form");

            $JSONResponse['content'] = $this->getResponse()->getContent();

            $this->getResponse()->setContent("");
        }
        else {
            $JSONResponse['message'] = "Parent node " . $this->parent_id . " not found.";
        }

        return $JSONResponse;
    }

    public function executeNew(sfWebRequest $request) {

        $this->getResponse()->setContentType('application/json');

        return $this->renderText(json_encode($this->checkParametersAsJSON($request)));
    }

    public function executeCreate(sfWebRequest $request)
    {
        // Indique si la requête est AJAX ou non.
        $requeteAjax = $request->isXmlHttpRequest();

        // S'il s'agit d'une requête AJAX, on modifie le type de réponse.
        if( $requeteAjax ) $this->getResponse()->setContentType('application/json');

        // Vérifications de la requête.
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);
        $this->parent_id = $request->getParameter('parent_id');
        $this->forward404Unless($folder = Doctrine_Core::getTable('EiNode')->findOneByIdAndType($this->parent_id, 'EiDataSetFolder'));

        // Paramètres liés à l'utilisateur.
        if ($this->getUser()->getGuardUser()->getEiUser() !== null) {
            $ref_user = $this->getUser()->getGuardUser()->getEiUser()->getRefId();
            $id_user = $this->getUser()->getGuardUser()->getEiUser()->getUserId();
        } else {
            $ref_user = null;
            $id_user = null;
        }

        $this->form = new EiDataSetTemplateForm(null, array('ei_node_parent' => $folder));

        $this->ei_root_structure = Doctrine_Core::getTable('EiDataSetStructure')->getRoot($this->ei_scenario->getId());

        $this->forward404Unless($this->ei_root_structure);

        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $this->urlParameters['parent_id'] = $this->parent_id;

        // REUSSITE
        if ($this->processForm($request, $this->form))
        {
            $messageSuccess = 'Data set save successfully ...';
            $titreSuccess = 'Success !';

            $data_set = $this->form->getObject()->getEiDataSet();
            $data_set->setUserRef($ref_user);
            $data_set->setUserId($id_user);

            $data_set->save();

            $this->getUser()->setFlash('alert_dataSet_form', array('title' => $titreSuccess,
                    'class' => 'alert-success',
                    'text' => $messageSuccess));

            if( $requeteAjax ) {

                $JSONResponse = array(
                    "success" => true,
                    "title" => $titreSuccess,
                    "message" => $messageSuccess,
                    "content" => ""
                );
                $JSONResponse = $this->getUpdatedSidebar($JSONResponse);

                return $this->renderText(json_encode($JSONResponse));
            }
            else return $this->redirect($this->generateUrl('eidataset_index', $this->urlParameters));
        }
        // ECHEC
        else
        {
            $this->getUser()->setFlash('alert_dataSet_form', array('title' => 'Error ',
                    'class' => 'alert-danger',
                    'text' => 'Error occur when saving data set ...'));
            $this->urlParameters['parent_id'] = $folder->getId();


            $node = $this->ei_scenario->getEiNode();

            $this->ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                    ->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');

            $this->forward404Unless($this->ei_data_set_root_folder);

            $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                    ->findByRootId($this->ei_data_set_root_folder->getId());
            $this->setTemplate('new');

            if( $requeteAjax ) return $this->renderText(json_encode($this->checkParametersAsJSON($request, $this->form)));
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);

        // Récupération du paramètre IS_SELECT_DATA_SET
        $isSelectDataSet = $request->getParameter("is_select_data_set");
        // TODO: Modification liée aux templates.
        $ei_data_set = Doctrine_Core::getTable('EiDataSetTemplate')->find(array($request->getParameter('ei_data_set_id')));

        $this->forward404Unless($ei_data_set, sprintf('Object ei_data_set does not exist (%s).', $request->getParameter('id')));
        // TODO: Modification liée aux templates.
        $this->form = new EiDataSetTemplateForm($ei_data_set);

        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $this->urlParameters['ei_data_set_id'] = $ei_data_set->getId();

        if ($request->isXmlHttpRequest()):
            $this->getResponse()->setContentType('application/json');

            // CAS EDITION VIA SELECTION DATA SET DANS SCENARIO.
            if( $isSelectDataSet != null && $isSelectDataSet == 1 ){
                $this->renderComponent("eidataset", "manager", array(
                    "ei_data_set" => $ei_data_set,
                    "form" => $this->form,
                    "urlParameters" => $this->urlParameters,
                    "ei_scenario" => $this->ei_scenario,
                    "is_select_data_set" => $isSelectDataSet,
                    "project_id" => $this->project_id,
                    "project_ref" => $this->project_ref,
                    "profile_id" => $this->profile_id,
                    "profile_ref" => $this->profile_ref,
                    "profile_name" => $this->profile_name
                ));
            }
            else{
                // CAS COMMUN : formulaire d'édition.
                $this->renderPartial('form');
            }

            $JSONResponse['status'] = "ok";
            $JSONResponse['content'] = $this->getResponse()->getContent();
            $this->getResponse()->setContent("");
        endif;

        if ($request->isXmlHttpRequest()):
            return $this->renderText(json_encode($JSONResponse));
        else:
            $node = $this->ei_scenario->getEiNode();
            $this->ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                    ->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');

            $this->forward404Unless($this->ei_data_set_root_folder);

            $this->ei_data_set_children = Doctrine_Core::getTable('EiNode')
                    ->findByRootId($this->ei_data_set_root_folder->getId());
        endif;
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project);

        // Titre et message retourné si succès.
        $messageSuccess = 'Data set has been successfully updated...';
        $titreSuccess = 'Success !';

        // Récupération du paramètre IS_SELECT_DATA_SET
        $isSelectDataSet = $request->getParameter("is_select_data_set");
        // TODO: Modification liée aux templates.
        $ei_data_set = Doctrine_Core::getTable('EiDataSetTemplate')->find(array($request->getParameter('ei_data_set_id')));

        $this->forward404Unless($ei_data_set, sprintf('Object ei_data_set does not exist (%s).', $request->getParameter('id')));
        // TODO: Modification liée aux templates.
        $this->form = new EiDataSetTemplateForm($ei_data_set);

        $this->getResponse()->setContentType('application/json');
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $this->urlParameters['ei_data_set_id'] = $ei_data_set->getId();

        if ( ($formOK = $this->processForm($request, $this->form)) ) {
            $JSONResponse['status'] = "ok";
            $JSONResponse['message'] = "Data set has been updated successfully.";
        } else {
            $JSONResponse['status'] = "error";
            $JSONResponse['message'] = "Data set could not be saved.";
        }

        //Retour de la réponse
        // CAS EDITION VIA SELECTION DATA SET DANS SCENARIO.
        if( $isSelectDataSet != null && $isSelectDataSet == 1 ){
            $this->renderComponent("eidataset", "manager", array(
                "ei_data_set" => $ei_data_set,
                "form" => $this->form,
                "urlParameters" => $this->urlParameters,
                "ei_scenario" => $this->ei_scenario,
                "is_select_data_set" => $isSelectDataSet,
                "project_id" => $this->project_id,
                "project_ref" => $this->project_ref,
                "profile_id" => $this->profile_id,
                "profile_ref" => $this->profile_ref,
                "profile_name" => $this->profile_name
            ));

            if( $formOK ){
                $JSONResponse["success"] = true;
                $JSONResponse["title"] = $titreSuccess;
                $JSONResponse["message"] = $messageSuccess;

                $JSONResponse = $this->getUpdatedSidebar($JSONResponse);
            }
            else{
                $JSONResponse['success'] = false;
                $JSONResponse["title"] = 'Bad !';
                $JSONResponse['message'] = "Data set could not be saved.";
            }
        }
        else{
            // CAS COMMUN : formulaire d'édition.
            $this->renderPartial('form');
        }

        $JSONResponse['content'] = $this->getResponse()->getContent();
        $this->getResponse()->setContent("");

        return $this->renderText(json_encode($JSONResponse));
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->checkProject($request);
        $this->checkProfile($request,$this->ei_project);
        $this->forward404Unless($ei_data_set = Doctrine_Core::getTable('EiDataSet')->find(array($request->getParameter('id'))), sprintf('Object ei_data_set does not exist (%s).', $request->getParameter('id')));
        $ei_data_set->delete();

        $this->redirect('eidataset/index');
    }

    protected function getServiceParameters(sfWebRequest $request) {

        // On vérifie les droits de l'utilisateur.
        $this->checkUserWebService();
//        $ei_user = Doctrine_Core::getTable('sfGuardUser')
//                ->findOneByUsername($request->getParameter('ei_username'));

        //vérification du user
//        if ($ei_user != null && MyFunction::getResultConnection($ei_user->getUsername(), $request->getParameter('ei_password'))) {
//            $this->ei_username = $request->getParameter('ei_username');
//            $this->ei_password = $request->getParameter('ei_password');
            //véririf du profil
            $this->profile_id = $request->getParameter('profile_id');
            $this->profile_ref = $request->getParameter('profile_ref');

            $this->ei_profile = Doctrine_Core::getTable('EiProfil')->
                    findOneByProfileIdAndProfileRef($this->profile_id, $this->profile_ref);

            if ($this->ei_profile == null) {
                throw new Exception("Environment not found.");
            }

            $this->getServiceLightParameters($request, $this->user);

            //vérifi de la version
            $this->ei_version = Doctrine_Core::getTable("EiProfilScenario")->createQuery('prof')
                    ->leftJoin('prof.EiVersion vers')
                    ->where("prof.profile_id = ?", $this->profile_id)
                    ->andWhere("prof.profile_ref = ?", $this->profile_ref)
                    ->andWhere("prof.ei_scenario_id = ? ", $this->ei_scenario->getId())
                    ->fetchOne();

            if ($this->ei_version == null) {
                throw new Exception("Version corresponding to Environment and scenario not found.");
            } else {
                $this->ei_version = $this->ei_version->getEiVersion();
            }

            //vérification du jeu de donnée. Il est possible que l'on soit en train de tester un test set.
            //si c'est le cas alors le test sur le data set ne se fait pas mais celui sur le test set sera
            //traité.

            $template_id = $request->getParameter('ei_data_set_id');
            $data_set_id = $request->getParameter('ei_data_set_id');
            $this->ei_data_set = null;

            if (isset($data_set_id) && $data_set_id > 0) {

                /** @var EiDataSetTemplate $template */
                $template = Doctrine_Core::getTable("EiDataSetTemplate")->find($template_id);
                /** @var EiDataSet $data_set */
                $data_set = null;

                if( $template != null && $template->getId() != "" && $template->getEiDataSetRefId() != ""){

                    // Si le scénario du template est le même que le scénario de l'URL alors il s'agit du template
                    if( $template->getEiNode() != null && $template->getEiNode()->getEiScenarioNode()->getObjId() == $this->ei_scenario->getId() ){
                        $this->ei_data_set = $template->getEiDataSet();
                        $data_set_id = $template->getEiDataSetRefId();
                    }
                    else{
                        $this->ei_data_set = null;
                    }
                }
                else{
                    $this->ei_data_set = null;
                }

                // Sinon, si le scénario du jeu de données est le même que celui chargé, c'est bon.
//                if( $this->ei_data_set == null ){
                    $data_set = Doctrine_Core::getTable("EiDataSet")->find($data_set_id);

                    if( $data_set != null && $data_set->getId() != "" && $data_set->getEiNode()->getEiScenarioNode()->getObjId() == $this->ei_scenario->getId() ){
                        $this->ei_data_set = $data_set;
                    }
//                }

                if ( $this->ei_data_set == null ){//&& $dataSetScenario != null && $dataSetScenario->getId() == $this->ei_scenario->getId()) {
                    throw new Exception("Data set not found.");
                }
            }

            //vérification du test set
            $test_set = $request->getParameter('ei_test_set_id');
            if (isset($test_set)) {
                $this->ei_test_set = Doctrine_Core::getTable('EiTestSet')
                        ->find($request->getParameter('ei_test_set_id'));

                if ($this->ei_test_set == null) {
                    throw new Exception("Data set not found.");
                }
            }
//        } else {
//            throw new Exception("Authentification error. Bad credentials.");
//        }
    }

    /**
     * @param sfWebRequest $request
     * @param SfGuardUser $ei_user
     * @throws Exception
     */
    protected function getServiceLightParameters(sfWebRequest $request, $ei_user) {

        //vérification du scénario
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($request->getParameter('ei_scenario_id'));

        if ($this->ei_scenario == null) {
            throw new Exception("Scenario not found.");
        }

        //vérif du projet
        $this->ei_project = Doctrine_Core::getTable('EiProjectUser')
                ->getEiProjet($this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $ei_user);

        if ($this->ei_project == null) {
            throw new Exception("Project not found. You are not allowed to access to this project, or it may have been deleted.");
        }
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     *
     * @updated 21/10/2014
     */
    public function executeGetAvailableBlocks(sfWebRequest $request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try
        {
            $this->getServiceParameters($request);

            /** @var EiDataLine $ei_data_line */
            $ei_data_line = Doctrine_Core::getTable('EiDataLine')->find($request->getParameter('ei_data_line_id'));

            /** @var EiNodeDataSet[] $nodes */
            $nodes = Doctrine_Core::getTable('EiDataSetStructure')->getChildrenAccordingToParent($ei_data_line->getEiDataSetStructure());

            if ($nodes && $nodes->count() > 0)
                foreach ($nodes as $i => $node) {
                    $JSONResponse[$i]['name'] = $node->getName();
                    $JSONResponse[$i]['id'] = $node->getId();
                }
            else
                throw new Exception('Impossible to create a data line inheriting from this one. Make sur there is at least one block defined as child in scenarios\' structure.');
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeCreateEmpty(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try {
            $this->getServiceParameters($request);

            $ei_data_set = $this->processCreateEmpty($request);

            $JSONResponse['ei_data_set_id'] = $ei_data_set->getId();
            $JSONResponse['ei_data_set_name'] = $ei_data_set->getName();
            $JSONResponse['ei_data_line_root_id'] = $ei_data_set->getEiDataLine()->get(0)->getId();
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeLightCreateFromXml(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try {
            $this->getServiceLightParameters($request, $request->getParameter("ei_user"));

            $ei_data_set = $this->processCreateEmpty($request);
            $root_data_line = $ei_data_set->getEiDataLine()->get(0);

            if ($request->getParameter("file") !== null) {
                $ei_data_set->createDataLines($request->getParameter("file"), $root_data_line);
            }

            $JSONResponse['ei_data_set_id'] = $ei_data_set->getId();
            $JSONResponse['ei_data_set_name'] = $ei_data_set->getName();
            $JSONResponse['ei_data_set_desc'] = $ei_data_set->getDescription();
            $JSONResponse['ei_data_line_root_id'] = $root_data_line->getId();
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return EiDataSet|null
     */
    private function processCreateEmpty(sfWebRequest $request)
    {
        // Déclaration de la connexion SQL.
        $connection = Doctrine_Manager::connection();
        // Instanciation du logger.
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT SAUVEGARDE DATA SET (CREATE EMPTY)");

        try {
            // On débute la transaction.
            $connection->beginTransaction();

            $ei_user = $this->getUser()->getGuardUser()->getEiUser();
            $nom = $request->getParameter("name");
            $desc = $request->getParameter("description");
            // Déclaration du template.
            $ei_data_set_template = null;
            $ei_node_template = null;
            // Récupération du jeu de données de base si enregistrement.
            $dataSetSourceId = $request->getParameter("dataSetSource");
            // On récupère le data set template source (si enregistrement).
            $dataSetTemplateSourceId = $request->getParameter("dataSetTemplateSource");
            // On récupère le dossier où enregistrer le JDD.
            $dataSetDirId = $request->getPostParameter("dataSetDir");

            // LOGS
            $this->logger->info("-- JDD SOURCE ID : " . $dataSetSourceId);
            $this->logger->info("-- JDD TEMPLATE SOURCE ID : " . $dataSetTemplateSourceId);
            $this->logger->info("-- JDD DIR ID : " . $dataSetDirId);

            if( $dataSetSourceId != null )
            {
                // LOGS
                $this->logger->info("-- DATA SET SOURCE ID NOT NULL ");

                /** @var EiDataSet $dataSetSource Récupération du jeu de données source. */
                $dataSetSource = Doctrine_Core::getTable("EiDataSet")->find($dataSetSourceId);

                if( $dataSetSource != null && $dataSetSource->getEiDataSetTemplate() != null )
                {
                    // LOGS
                    $this->logger->info("-- DATA SET SOURCE (".$dataSetSource->getId().") ET TEMPLATE (".$dataSetSource->getEiDataSetTemplate()->getId().") NOT NULL ");

                    $ei_data_set_template = $dataSetSource->getEiDataSetTemplate();
                    $ei_node_template = $ei_data_set_template->getEiNode();

                    // On modifie le nom et la description.
                    $nom = $ei_data_set_template->getName();
                    $desc = $ei_data_set_template->getDescription() . " V" . ($ei_data_set_template->getEiDataSets()->count()+1);
                }
                else{
                    $dataSetSource = Doctrine_Core::getTable("EiDataSetTemplate")->find($dataSetSourceId);

                    if( $dataSetSource != null ){
                        // LOGS
                        $this->logger->info("-- DATA SET SOURCE (".$dataSetSource->getId().") ET TEMPLATE (".$dataSetSource->getEiDataSetTemplate()->getId().") NOT NULL ");

                        $ei_data_set_template = $dataSetSource;
                        $ei_node_template = $ei_data_set_template->getEiNode();

                        // On modifie le nom et la description.
                        $nom = $ei_data_set_template->getName();
                        $desc = $ei_data_set_template->getDescription() . " V" . ($ei_data_set_template->getEiDataSets()->count()+1);
                    }
                }
            }
            elseif( $dataSetTemplateSourceId != null ){
                /** @var EiDataSetTemplate $dataSetSource */
                $dataSetSource = Doctrine_Core::getTable("EiDataSetTemplate")->find($dataSetTemplateSourceId);

                if( $dataSetSource != null ){
                    // LOGS
                    $this->logger->info("-- DATA SET SOURCE (".$dataSetSource->getEiDataSetRefId().") ET TEMPLATE (".$dataSetSource->getId().") NOT NULL ");

                    $ei_data_set_template = $dataSetSource;
                    $ei_node_template = $ei_data_set_template->getEiNode();

                    // On modifie le nom et la description.
                    $nom = $ei_data_set_template->getName();
                    $desc = $ei_data_set_template->getDescription() . " V" . ($ei_data_set_template->getEiDataSets()->count()+1);
                }
            }

            if( $ei_data_set_template == null )
            {
                // LOGS
                $this->logger->info("-- CREATION D'UN NOUVEAU TEMPLATE");

                // Création du template.
                //=> NODE
                $ei_node_template = new EiNode();
                $ei_node_template->createEmpty($request->getParameter("ei_node_parent_id"), $this->ei_project, $request->getParameter("name"));
                //=> TEMPLATE.
                $ei_data_set_template = new EiDataSetTemplate();
                $ei_data_set_template->createEmpty($nom, $desc, $ei_node_template, $ei_user);

                // Sauvegarde du template.
                $ei_data_set_template->save($connection);
            }

            $this->logger->info("----------------------------------------------------------");
            $this->logger->info("---   FIN SAUVEGARDE DATA SET (CREATE EMPTY)");
            $this->logger->info("----------------------------------------------------------");

            // Création du jeu de données.
            //=> NODE
            $ei_node = new EiNode();
            $ei_node->createEmpty($ei_node_template->getId(), $this->ei_project, $request->getParameter("name"));
            //=> JEU DE DONNEES
            $ei_data_set = new EiDataSet();

            if ($this->getUser() !== null) {
                $ei_data_set->user_id = $ei_user->getUserId();
                $ei_data_set->user_ref = $ei_user->getRefId();
            }

            //=> Création à vide.
            $ei_data_set->createEmpty($nom, $desc, $ei_node, $this->ei_scenario);
            //=> Affectation du template.
            $ei_data_set->setEiDataSetTemplate($ei_data_set_template);
            //=> SAUVEGARDE
            $ei_data_set->save($connection);

            // Affectation du jeu de données au template.
            $ei_data_set_template->setEiDataSet($ei_data_set);
            $ei_data_set_template->save($connection);

            // Création des lignes du jeu de données.
            $ei_data_set->createRootDataLine();

            $connection->commit();
        }
        catch (Exception $e)
        {
            $connection->rollback();

            return null;
        }

        return $ei_data_set;
    }

    /**
     * @param sfWebRequest $request
     * @return string
     *
     * @updated 21/10/2014
     */
    public function executeCreateEiDataLine(sfWebRequest $request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try {
            $this->getServiceParameters($request);
            $JSONStr = $request->getParameter('eidataline');

            // Déclaration de la table EiDataLine.
            /** @var EiDataLineTable $tableDataLine */
            $tableDataLine = Doctrine_Core::getTable("EiDataLine");

            // Déclaration de la table EiDataSetStructure.
            /** @var EiDataLineTable $tableDataLine */
            $tableDataSetStr = Doctrine_Core::getTable("EiDataSetStructure");

            if (!is_null($JSONStr))
            {
                $JSONArray = json_decode($JSONStr);
                $parent = $tableDataLine->find($JSONArray[0]->{'ei_data_line_parent_id'});

                /** @var EiDataSet $data_set */
                $data_set = $parent->getEiDataSet();
                /** @var EiDataSetTemplate $data_set_template */
                $data_set_template = $data_set->getEiDataSetTemplate();

                if ($parent == null)
                    throw new Exception('The parent data line does not exist.');
                elseif ($data_set_template->getId() != $JSONArray[0]->{'ei_data_set_id'})
                    throw new Exception('You tried to add a data line into a data line not included into the data set.');

                /** @var EiDataSetStructure $str */
                $str = $tableDataSetStr->findOneByIdAndEiScenarioId($JSONArray[0]->{'ei_scenario_structure_id'}, $this->ei_scenario->getId());

                if ($str == null)
                    throw new Exception('The parent scenario structure does not exist.');

                if ($str->getType() == EiDataSetStructure::$TYPE_LEAF)
                    throw new Exception("You can not create a data line for parameters.");

                /** @var EiDataLine $ei_data_line */
                $ei_data_line = $str->createEiDataLine($data_set->getId(), $parent->getRootId() == "" ? $parent->getId():$parent->getRootId() );
                $ei_data_line->setEiDataLineParentId($parent->getId());

                if ($ei_data_line->getEiDataLineParent()->getEiDataSetStructureId() != $str->getEiDatasetStructureParentId())
                    throw new Exception("A mismatch has been detected beetween the scenario's structure and your data set.");

                $ei_data_line->save();

                $JSONResponse['id'] = $ei_data_line->getId();
                $JSONResponse['name'] = $ei_data_line->getEiDataSetStructure()->getName();
                $params = array();

                /** @var EiDataLine $line */
                foreach ($ei_data_line->getEiDataLines() as $l => $line)
                {
                    if ($line->getEiDataSetStructure()->getType() == EiDataSetStructure::$TYPE_LEAF) {
                        $params[$l]['name'] = $line->getEiDataSetStructure()->getName();
                        $params[$l]['id'] = $line->getId();
                        $params[$l]['valeur'] = $line->getValeur();
                    }
                }

                $JSONResponse['parameters'] = $params;
            } else {
                throw new Exception('Error when trying to create a new data line : information are missing.');
            }
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     *
     * @updated 21/10/2014
     */
    public function executeEditEiDataLine(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try {
            $this->getServiceParameters($request);
            $JSONStr = $request->getParameter('parameters');

            // Déclaration de la table EiDataLine.
            /** @var EiDataLineTable $tableDataLine */
            $tableDataLine = Doctrine_Core::getTable("EiDataLine");

            if (!is_null($JSONStr)) {
                $JSONArray = json_decode($JSONStr, true);
                foreach ($JSONArray as $j => $vI) {
                    foreach ($vI as $i => $v) {
                        /** @var EiDataLine $param */
                        $param = $tableDataLine->findOneByIdAndEiDataSetId($i, $this->ei_data_set->getId());

                        if (!$param) {
                            throw new Exception("Parameter $i not found.");
                        } elseif ($param->getEiDataSetStructure()->getType() == EiDataSetStructure::$TYPE_NODE) {
                            throw new Exception('A block can not be considered as a parameter. Do not try to set any value to it.');
                        } else {
                            $param->setValeur($v);
                            $param->save();
                            $JSONResponse[$i] = $v;
                        }
                    }
                }
            } else {
                throw new Exception('No parameters given.');
            }
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return string
     *
     * @updated 21/10/2014
     */
    public function executeDeleteEiDataLine(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $JSONResponse = array();

        try
        {
            $this->getServiceParameters($request);

            // Déclaration de la table EiDataLine.
            /** @var EiDataLineTable $tableDataLine */
            $tableDataLine = Doctrine_Core::getTable("EiDataLine");

            /** @var EiDataLine $ei_data_line */
            $ei_data_line = $tableDataLine->findOneByIdAndEiDataSetId($request->getParameter('ei_data_line_id'), $this->ei_data_set->getId());

            if ($ei_data_line->getEiDataSetStructure()->getNode()->isRoot())
                $ei_data_line->getEiDataSet()->delete();
            else
                $ei_data_line->delete();

            $JSONResponse['ok'] = "Data line deleted successfully.";
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     *
     * @updated 21/10/2014
     */
    public function executeSendEiDataLine(sfWebRequest $request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $JSONResponse = array();

        try {
            $this->getServiceParameters($request);

            $data_set_id = $this->ei_data_set->getId();

            // Déclaration de la table EiDataLine.
            /** @var EiDataLineTable $tableDataLine */
            $tableDataLine = Doctrine_Core::getTable("EiDataLine");

            if ($request->getParameter('ei_data_line_id') == 0)
            {
                $ei_data_line = $tableDataLine->getEiDataLineRoot($data_set_id);
                $root_id = $ei_data_line->getId();
            }
            else
            {
                $ei_data_line = $tableDataLine->find($request->getParameter('ei_data_line_id'));
                $root_id = $ei_data_line->getRootId();
            }

            if (is_null($ei_data_line))
                throw new Exception('Data line not found.');

            $this->pager = new sfDoctrinePager('EiDataLine', 25);
            $this->pager->setQuery($tableDataLine->getEiDataLineBlocks($ei_data_line->getId()));
            $this->pager->setPage($request->getParameter('page', 1));
            $this->pager->init();

            $paramsPrev = $paramsNext = $params = array(
                'token' => $request->getParameter('token'),
                'profile_id' => $request->getParameter('profile_id'),
                'profile_ref' => $request->getParameter('profile_ref'),
                'project_id' => $request->getParameter('project_id'),
                'project_ref' => $request->getParameter('project_ref'),
                'ei_scenario_id' => $request->getParameter('ei_scenario_id'),
                'ei_data_set_id' => $data_set_id,
                'ei_data_line_id' => $request->getParameter('ei_data_line_id'),
            );

            $i = 0;

            $JSONResponse[$i]['name'] = "Root";
            $JSONResponse[$i]['id'] = $root_id;

            /** @var EiDataLine[] $params */
            $params = $tableDataLine->getEiDataLineParameters($root_id);

            foreach ($params as $j => $p) {
                $JSONResponse[$i]['parameters'][$j]['name'] = $p->getEiDataSetStructure()->getName();
                $JSONResponse[$i]['parameters'][$j]['id'] = $p->getId();
                $JSONResponse[$i]['parameters'][$j]['valeur'] = $p->getValeur();
            }

            $paramsPrev['page'] = $this->pager->getPreviousPage();
            $paramsNext['page'] = $this->pager->getNextPage();
            //ajouter le parent_id
            $JSONResponse['root_id'] = $root_id;
            $JSONResponse['nbpages'] = $this->pager->getLastPage();
            $JSONResponse['nextPage'] = $this->generateUrl("get_ei_data_line", $paramsNext);
            $JSONResponse['previousPage'] = $this->generateUrl("get_ei_data_line", $paramsPrev);

            $ei_data_line_children = $this->pager->getResults();

            foreach ($ei_data_line_children as $l) {

                ++$i;

                $JSONResponse[$i]['name'] = $l->getEiDataSetStructure()->getName();
                $JSONResponse[$i]['id'] = $l->getId();
                $JSONResponse[$i]['parameters'] = array();

                $params = $tableDataLine->getEiDataLineParameters($l->getId());

                foreach ($params as $j => $p) {
                    $JSONResponse[$i]['parameters'][$j]['name'] = $p->getEiDataSetStructure()->getName();
                    $JSONResponse[$i]['parameters'][$j]['id'] = $p->getId();
                    $JSONResponse[$i]['parameters'][$j]['valeur'] = $p->getValeur();
                }
            }
        } catch (Exception $e) {
            $JSONResponse = array();
            $JSONResponse['error'] = $e->getMessage();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @param sfForm $form
     * @return bool
     */
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        /** @var EiDataSetTemplate $object */
        $object = $form->getObject();

        if ($form->isValid()) {
            if ($form->isNew()){
                // Paramètres liés à l'utilisateur.
                if ($this->getUser()->getGuardUser()->getEiUser() !== null) {
                    $ref_user = $this->getUser()->getGuardUser()->getEiUser()->getRefId();
                    $id_user = $this->getUser()->getGuardUser()->getEiUser()->getUserId();
                } else {
                    $ref_user = null;
                    $id_user = null;
                }

                $object->setRootStr($this->ei_root_structure);
                $object->setUserRef($ref_user);
                $object->setUserId($id_user);
            }

            $this->getUser()->setFlash('xml_success', 'Update Success ...');

            try {
                $form->save();
            }
            catch (Exception $e) {
                $this->getUser()->setFlash('xml_error', $e->getMessage());
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Méthode permettant de retourner la nouvelle sidebar.
     */
    private function getUpdatedSidebar($JSONResponse)
    {
        $this->urlParameters['ei_scenario_id'] = $this->ei_scenario->getId();
        $node = $this->ei_scenario->getEiNode();
        $ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');
        $ei_data_set_children = Doctrine_Core::getTable('EiNode')->findByRootId($ei_data_set_root_folder->getId());

        $JSONResponse["sidebar"] = $this->getPartial("root", array(
            'urlParameters' => $this->urlParameters,
            'ei_scenario' => $this->ei_scenario,
            'ei_data_set_root_folder'=> $ei_data_set_root_folder,
            'ei_data_set_children' => $ei_data_set_children,
            'is_edit_step_case' => false,
            'is_select_data_set' => 1,
            'fullDisplay' => false
        ));

        return $JSONResponse;
    }

    /**
     * Méthode permettant de modifier la version courante associée au template.
     *
     * @param sfWebRequest $request
     */
    public function executeChangeCurrentVersion(sfWebRequest $request)
    {
        // Instanciation du logger.
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT MODIFICATION VERSION JDD TEMPLATE");
        $this->logger->info("----------------------------------------------------------");

        // On effectue les vérifications de base.
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        // On indique qu'aucun layout n'est utilisé.
        $this->setLayout(false);
        // Et le type de la réponse: Json.
        $this->getResponse()->setContentType('application/json');
        // Puis on déclare le tableau contenant la réponse.
        $JSONResponse = array();
        $valide = true;

        // Récupération des paramètres POST.
        $old_ei_data_set = $request->getPostParameter('oldVersionId');
        $new_ei_data_set = $request->getPostParameter('newVersionId');

        // Récupération du template id.
        $template_id = $request->getParameter('ei_data_set_template_id');

        $this->logger->info("---   PARAMETRES POST");
        $this->logger->info("OLD : " . $old_ei_data_set);
        $this->logger->info("NEW : " . $new_ei_data_set);
        $this->logger->info("---   TEMPLATE");
        $this->logger->info("ID : " . $template_id);

        // On récupère le template.
        $ei_data_set_template = Doctrine_Core::getTable('EiDataSetTemplate')->find($template_id);

        // On récupère l'ancienne et la nouvelle version du jeu de données.
        /** @var EiDataSet $old_ei_data_set */
        $old_ei_data_set = Doctrine_Core::getTable('EiDataSet')->find($old_ei_data_set);
        /** @var EiDataSet $new_ei_data_set */
        $new_ei_data_set = Doctrine_Core::getTable('EiDataSet')->find($new_ei_data_set);

        // On vérifie que les objets existent bien.
        if( $ei_data_set_template != null && $old_ei_data_set != null && $old_ei_data_set->getId() != "" && $new_ei_data_set != null && $new_ei_data_set->getId() != "" ){
            $this->logger->info("---   COMPARAISON TEMPLATE ASSOCIE A OLD & NEW");
            $this->logger->info("OLD : " . $old_ei_data_set->getEiDataSetTemplateId());
            $this->logger->info("NEW : " . $new_ei_data_set->getEiDataSetTemplateId());

            if( $ei_data_set_template->getId() != $old_ei_data_set->getEiDataSetTemplateId() ){
                $valide = false;
            }
        }
        else{
            $this->logger->info("---   MAUVAISE RECUPERATION");
            $valide = false;
        }

        // Puis, on vérifie qu'ils dépendent tous deux du même template.
        if( $valide && $old_ei_data_set->getEiDataSetTemplate()->getId() == $new_ei_data_set->getEiDataSetTemplate()->getId() ){
            // Si c'est le cas, on modifie la version courante.
            /** @var EiDataSetTemplate $template */
            $template = $new_ei_data_set->getEiDataSetTemplate();

            $template->setEiDataSetRefId($new_ei_data_set->getId());
            $template->save();

            $JSONResponse["success"] = true;
            $JSONResponse["title"] = "Success !";
            $JSONResponse["message"] = "Data set master version has been changed successfully.";
        }
        else{
            $JSONResponse["success"] = false;
            $JSONResponse["title"] = "Error !";
            $JSONResponse["message"] = "An error occured when we try to change Data set master version.";
        }

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   FIN MODIFICATION VERSION JDD TEMPLATE");
        $this->logger->info("----------------------------------------------------------");

        return $this->renderText(json_encode($JSONResponse));
    }

    /**************************************************************************/
    /**********          GESTION JEU DE DONNEES VIA JSTREE          ***********/
    /**************************************************************************/

    /**
     * Méthode permettant de renommer la valeur d'une ligne d'un jeu de données.
     *
     * @param sfWebRequest $request
     */
    public function executeRedefineValue(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS PROJET/PROFIL.

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS DATA-SET/DATA-LINE.

        $this->checkEiScenario($request, $this->ei_project);
        $this->checkEiDataSet($request, $this->ei_scenario);
        $this->checkEiDataLine($request, $this->ei_data_set);

        $JSONResponse = array();

        try{
            // Récupération du paramètre
            $nouvelleValeur = $request->getPostParameter("ei_leaf_data_set[name]");

            if( $nouvelleValeur != null ){
                $this->ei_data_line->setValeur($nouvelleValeur);
                $this->ei_data_line->save();

                $JSONResponse = false;
            }
            else{
                throw new Exception("Impossible de récupérer la nouvelle valeur de la ligne.");
            }
        }
        catch(Exception $exc){
            $JSONResponse = $this->createJSONResponse("error", "Impossible de redéfinir la valeur.", "value");
        }

        if ( !$JSONResponse ){
            $JSONResponse = $this->createJSONResponse('saved', 'ok', "value");
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Format les réponses JSON de succès.
     *
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($action, $status, $type = "Node", $message = "") {
        /** @var EiDataSetStructure $objet */
        $objet = $this->ei_data_line->getEiDataSetStructure();

        $JSONResponse['status'] = $status;
        $JSONResponse['success'] = $status == "error" ? false:true;
        $JSONResponse['title'] = $status == "error" ? "Failed":"Success";
        $JSONResponse['message'] = $message == "" ? $type . " " . $objet->getName() . " has been $action successfully.":$message;

        return $JSONResponse;
    }

}

