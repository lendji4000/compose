<?php

/**
 * eitestset actions.
 *
 * @package    kalifastRobot
 * @subpackage eitestset
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eitestsetActions extends sfActionsKalifast
{
    /** @var EiUser */
    private $user;

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
        $this->getUser()->signIn($this->user->getGuardUser(), true);
    }

    //Recherche d'un jeu de test avec les paramètres de requête
    public function checkEiTestSet(sfWebRequest $request, $fullObj = false) {
        if (($this->ei_test_set_id = $request->getParameter('ei_test_set_id')) != null ) {
            //Recherche du jeu de test en base
            $this->ei_test_set = Doctrine_Core::getTable('EiTestSet')->findTestSet($this->ei_test_set_id, $fullObj);
            //Si le jeu de test n'existe pas , alors on retourne un erreur 404
            if ($this->ei_test_set == null)
                $this->forward404('Test Set not found ...'); 
        } 
        else {
            $this->forward404('Missing Test Set parameters  ...');
        }
    }
    
    /* Recherche de la liste des jeux de test d'un scnéario .
     *  NB:  le scénario doit etre définit dans l'action ( $this->ei_scenario!=null)
     */
    public function findEiScenarioTestsSet(EiScenario $ei_scenario)
    {
//        $this->EiScenarioTestsSet= Doctrine_Query::create()
//            ->from('EiTestSet')
//            ->where('ei_scenario_id='.$ei_scenario->getId())
//            ->orderBy('id DESC')
//            ->execute()
//        ;

        $this->EiScenarioTestsSet = Doctrine_Core::getTable("EiTestSet")->findAllTestSet($ei_scenario->getId());
    }
     
    
    /* Vérification de l'appartenance d'un scénario au projet durant un process 
     * NB:   le scénario et le projet sont déjà définis
     */
    public function checkLogicBetweenProjectAndScenario(EiProjet $ei_project ,  EiScenario $ei_scenario){
        //On vérifie si le scénario appartient bien au projet
        if($ei_project->getProjectId()!=$ei_scenario->getProjectId() || 
           $ei_project->getRefId()!=$ei_scenario->getProjectRef() ) $this->forward404 ('Scenario  don\'t belong to project' );
           
    }
    /* Vérification de l'appartenance d'un profil au projet durant un process 
     * NB:   le profil et le projet sont déjà définis
     */
    public function checkLogicBetweenProjectAndProfile(EiProjet $ei_project , EiProfil $ei_profile){
          
        //On vérifie si le profil appartient bien au projet
        if($ei_project->getProjectId()!=$ei_profile->getProjectId() || 
           $ei_project->getRefId()!=$ei_profile->getProjectRef() ) $this->forward404 ('Environment  don\'t belong to project' );
    }
    
    /* Liste des jeux de tests d'un scénario */
    public function executeIndex(sfWebRequest $request){
        
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkEiScenario($request,$this->ei_project); //Recherche du scénario concerné
        $this->checkProfile($request,$this->ei_project); //Recherche du profil 
        $this->findEiScenarioTestsSet($this->ei_scenario); //Recherche des jeux de test du scenario

        $this->ProjectProfilesArray = Doctrine_Core::getTable("EiProfil")->getProjectProfilesAsArray($this->ei_project);

        /* 
         * On vérifie que le scénario est bien un objet du projet .
         * Et que le profil est bien un profil du projet.
         */
        $this->checkLogicBetweenProjectAndScenario($this->ei_project,$this->ei_scenario); 
        $this->checkLogicBetweenProjectAndProfile($this->ei_project,$this->ei_profile);


        //Récupération du chemin firefox dans les settings utilisateur
        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();

        $this->user_settings = Doctrine_Core::getTable('EiUserSettings')
            ->findOneByUserRefAndUserId($user->getRefId(), $user->getUserId());

        $this->firefox_path = $this->user_settings == null ? : $this->user_settings->getFirefoxPath();

        /** @var EiTestSetStateTable $tableStates */
        $tableStates = Doctrine_Core::getTable("EiTestSetState");
        // Récupération des statuts.
        $this->states = $tableStates->findByProjectIdAndProjectRef($this->project_id, $this->project_ref);
    }

    /**
     * Action permettant de télécharger au format XML (par exemple) les paramètres de
     * sortie d'un jeu de test.
     *
     * @param sfWebRequest $request
     */
    public function executeDownloadOracle(sfWebRequest $request)
    {
        // Variable contenant le résultat.
        $xmlResult = "";

        // Nous vérifions certaines informations relatives au projet/scénario.
        $this->checkProject($request); //Recherche du projet concerné

        $this->checkProfile($request, $this->ei_project);
        
        $this->checkEiScenario($request,$this->ei_project); //Recherche du scénario concerné

        /*
         * On vérifie que le scénario est bien un objet du projet .
         */
        $this->checkLogicBetweenProjectAndScenario($this->ei_project,$this->ei_scenario);
        //Recherche du jeu de test
        $this->checkEiTestSet($request, true);

        // On supprime le layout.
        $this->setLayout(false);
        // On détermine le type de contenu retourné.
        $this->getResponse()->setContentType('application/xml');

        try{
            $xmlResult = $this->renderText($this->ei_test_set->generateTestSetDataSetXML());
        }
        catch (Exception $e) {
            $xmlResult = $this->renderText('<error>' . $e->getMessage() . '</error>');
        }

        return $xmlResult;
    }
    
    public function executeFunctionOracle(sfWebRequest $request){
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project); //Recherche du scénario concerné
        $this->checkFunction($request, $this->ei_project);
        $this->lang=$request->getParameter('lang');
        $this->ei_user=$this->getUser()->getEiUser(); 
        if($this->lang==null) $this->lang=$this->ei_project->getDefaultNoticeLang();
        //Recherche du jeu de test
        $this->checkEiTestSet($request);
        //On recherche le profil utilisé pour le jeu de test 
        $this->ei_profile=$this->ei_test_set->getProfile();
        //SI le profil n'existe plus , on renvoi une exception 
        if( $this->ei_profile==null) $this->forward404 ('Environment doesn\'t exist anymore' );
        //Recherche des paramètres de profil à utiliser pour interpreter des éventuels paramètres variables
        $this->profileParams=$this->ei_profile->getParamsWithName($this->ei_user);
        //Récupération de l'oracle du jeu de test ( notice  du jeu de test) 
        $this->oracle=$this->ei_test_set->getTestSetOracle($this->ei_project, $this->lang,$this->kal_function->getFunctionId(),$this->kal_function->getFunctionRef());
        //Récupération des paramètres du jeu de test pour l'interprétation des paramètres variables
        $this->vbParams=$this->ei_test_set->getTestSetParam();

        $this->setLayout('layout_notice');
        
    }
    
    /* Construction de l'oracle d'un jeu de test */
    
    public function executeOracle(sfWebRequest $request){
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project);
        $this->checkEiScenario($request,$this->ei_project); //Recherche du scénario concerné
        $this->lang=$request->getParameter('lang');
        $this->ei_user=$this->getUser()->getEiUser();
        if($this->lang==null) $this->lang=$this->ei_project->getDefaultNoticeLang();

        /* 
         * On vérifie que le scénario est bien un objet du projet . 
         */
        $this->checkLogicBetweenProjectAndScenario($this->ei_project,$this->ei_scenario); 
        //Recherche du jeu de test
        $this->checkEiTestSet($request);
        //On recherche le profil utilisé pour le jeu de test 
        $this->ei_profile=$this->ei_test_set->getProfile();
        //SI le profil n'existe plus , on renvoi une exception 
        if( $this->ei_profile==null) $this->forward404 ('Environment doesn\'t exist anymore' );
        //Recherche des paramètres de profil à utiliser pour interpreter des éventuels paramètres variables
        $this->profileParams=$this->ei_profile->getParamsWithName($this->ei_user);
        //Récupération de l'oracle du jeu de test ( notice  du jeu de test) 
        $this->oracle=$this->ei_test_set->getTestSetOracle($this->ei_project, $this->lang);
        //Récupération des paramètres du jeu de test pour l'interprétation des paramètres variables
        $this->vbParams=$this->ei_test_set->getTestSetParam();

        $this->setLayout('layout_notice');
        
    }

    /**
     * @param sfWebRequest $request
     * @throws Exception
     */
    protected function getUrlParameters(sfWebRequest $request)
    {
        $this->checkUserWebService();

        //véririf du profil
        $this->profile_id = $request->getParameter('profile_id');
        $this->profile_ref = $request->getParameter('profile_ref');

        $this->ei_profile = Doctrine_Core::getTable('EiProfil')->
                findOneByProfileIdAndProfileRef($this->profile_id, $this->profile_ref);

        if ($this->ei_profile == null) {
            throw new Exception("Environment not found.");
        }

        //vérifi du scénario
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                ->find($request->getParameter('ei_scenario_id'));

        if ($this->ei_scenario == null) {
            throw new Exception("Scenario not found.");
        }

        //vérifie du projet
        $this->ei_project = Doctrine_Core::getTable('EiProjectUser')
                ->getEiProjet($this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $this->user);

        if ($this->ei_project == null) {
            throw new Exception("Project not found. You are not allowed to access to this project, or it may have been deleted.");
        }

        //vérifie de la version
        $this->ei_version = Doctrine_Core::getTable("EiProfilScenario")->createQuery('prof')
                ->leftJoin('prof.EiVersion vers')
                ->where("prof.profile_id = ?", $this->profile_id)
                ->andWhere("prof.profile_ref = ?", $this->profile_ref)
                ->andWhere("prof.ei_scenario_id = ? ", $this->ei_scenario->getId())
                ->fetchOne();

        if ($this->ei_version == null) {
            throw new Exception("Version corresponding to environment and scenario not found.");
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
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeGetPreviousBlock(sfWebRequest $request){
        $this->getResponse()->setContentType('application/xml');
        $this->setLayout(false);
         
        try{
            $this->getUrlParameters($request);
            $ei_user=$this->getUser()->getGuardUser()->getEiUser();
            //Récupération du package par défaut
            $this->defPack=Doctrine_Core::getTable('EiUserDefaultPackage')->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                        $this->ei_project->getProjectId(),$this->ei_project->getRefId(),$ei_user->getUserId(),$ei_user->getRefId() );
            $position = $request->getParameter("position");
            
            $this->functions = Doctrine_Core::getTable('EiTestSetFunction')
                    ->getPreviousBlock($this->ei_test_set->getId(), $position, $request->getParameter("profile_id"), $request->getParameter("profile_ref"));
            
            $this->setTemplate("getFunctionsFromPosition");
            
        }catch(Exception $e){
            return $this->renderText('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * @param EiTestSet $testSet
     * @param int $position
     *
     * @updated 18/08/2015
     */
    private function generateTestSetFunctionFromPosition(EiTestSet $testSet, $position = 1, EiIteration $iteration = null)
    {
        // Si la fonction n'a pas encore été chargée dans le jeu de test, on le fait.
        if( $testSet->getFunctionAt($position) === null )
        {
            // Récupération de la version de la structure.
            /** @var EiProfilScenario $profilScenario */
            $profilScenario = Doctrine_Core::getTable("EiProfilScenario")->findOneByEiScenarioIdAndProfileIdAndProfileRef(
                $testSet->getEiScenarioId(),
                $testSet->getProfileId(),
                $testSet->getProfileRef()
            );

            /** @var EiVersion $version */
            $version = $profilScenario->getEiVersion();

            /** @var EiVersionStructure $rootStructure */
            $rootStructure = Doctrine_Core::getTable("EiVersionStructure")->getEiVersionStructureRootId($version->getId());

            $chrono = new Chronometre();
            $globalCh = $chrono->lancerChrono("Génération de la fonction à la position " . $position, true);

            if( $rootStructure !== null )
            {
                $conn = Doctrine_Manager::connection();

                //************************************************************//
                //*****     CREATION DES PARAMETRES DU BLOCK SUIVANT     *****//
                //************************************************************//

                $start = $chrono->lancerChrono("RECHERCHE PROCHAIN BLOCK", true);

                // Récupération de l'instance de la table EiTestSetBlockParam
                /** @var EiTestSetBlockParamTable $tableJDTBP */
                $tableJDTBP = Doctrine_Core::getTable("EiTestSetBlockParam");
                // Récupération de la table de gestion de la pile.
                /** @var EiTestSetBlockStackTable $tableJDTS */
                $tableJDTS = Doctrine_Core::getTable("EiTestSetBlockStack");

                try{
                    $conn->beginTransaction();
                    // Récupération de la dernière fonction exécutée.
                    /** @var EiTestSetFunction $lastFunc */
                    $lastFunc = Doctrine_Core::getTable("EiTestSetFunction")->findOneByPositionAndEiTestSetId($position - 1, $testSet->getId());

                    // Récupération du block dans la stack.
                    $path = $lastFunc == null ? EiTestSetFunction::getDefaultPath():$lastFunc->getPath();
                    $blockStack = $tableJDTS->getLastElementFromPath($testSet->getId(), $path);

                    do{
//                        var_dump("-------------     BEGIN     -------------");
//                        if( $blockStack != null )
//                            var_dump($blockStack->toArray(false));

                        // Récupération du block suivant à la position -1.
                        $nextBlock = $testSet->getNextBlock(is_bool($lastFunc) ? null:$lastFunc, is_bool($blockStack) ? null:$blockStack, $conn);

//                        if ($nextBlock != null){
//                            var_dump($nextBlock->toArray(false));
//                            var_dump("First fragment is a function ? " . ($nextBlock->firstFragmentElementIsFunction() ? 1:0));
//                        }

                        if( $nextBlock != null ){
                            $start2 = $chrono->lancerChrono("GENERATION DES PARAMETRES DE BLOCKS", true);
                            // Génération de ses paramètres dans le JDT.
                            /** @var EiTestSetBlockParam $blockParam */
                            $tableJDTBP->generate($testSet, $nextBlock, $conn);
                            $chrono->arreterEtAfficherChrono("GENERATION DES PARAMETRES DE BLOCKS",$start2);
                        }

//                        var_dump("-------------      END      -------------");
                    }
                    // Si nous avons à faire à un block qui n'a pas de fonction, on traite le suivant.
                    /** @var EiTestSetBlockStack $nextBlock */
                    while( $nextBlock != null && !$nextBlock->firstFragmentElementIsFunction() && ($blockStack = $nextBlock) );

//                    var_dump($nextBlock->toArray(false));
//                    var_dump($nextBlock->getEiTestSetBlockStackParent()->toArray(false));
//                    exit;
                    $chrono->arreterEtAfficherChrono("RECHERCHE PROCHAIN BLOCK",$start);

                    //*****************************************************//
                    //*****     GENERATION DES FICHIERS XML & XSL     *****//
                    //*****************************************************//

                    $start = $chrono->lancerChrono("Génération XML Mapping Data Set METHODE 1", true);

                    // On génére les paramètres de blocks à partir de la branche du block.
                    if( $nextBlock != null ){
                        $xml = $nextBlock->generateBlockParametersXML();
                    }
                    else{
                        $xml = $testSet->generateBlockParametersXML();
                    }

                    $chrono->arreterEtAfficherChrono("Génération XML Mapping Data Set METHODE 1",$start);

//                    echo $xml;

                    $start = $chrono->lancerChrono("Génération XSL Test Set", true);

                    // Récupération de la liste des fonctions du fragment.
                    $functionsList = $nextBlock != null ? $nextBlock->getFragmentsFunctions():array();

//                    var_dump($functionsList);

                    // Si block
                    if( $nextBlock != null && $nextBlock->getPartIndex() != $nextBlock->getPartsCount() ){
                        $xsl = $nextBlock->generateXSLForTestSet($functionsList);
                    }
                    elseif( $nextBlock != null ){
                        $xsl = $nextBlock->generateXSLForTestSet($functionsList);
                    }
                    else{
                        $xsl = $testSet->getEiVersion()->generateXSLForTestSet();
                    }

                    $chrono->arreterEtAfficherChrono("Génération XSL Test Set",$start);

//                    echo $xsl;
//exit;
                    $start = $chrono->lancerChrono("Génération XML Test Set", true);

                    if( $nextBlock != null )
                        $testSet->generateFromXML($xsl, $xml, $position, $iteration);

                    $chrono->arreterEtAfficherChrono("Génération XML Test Set",$start);

                    $testSet->save($conn);

                    // Validation des éléments.
                    $tableJDTS->validateStackElements($testSet->getId(), $conn);

                    $conn->commit();
                }
                catch(Exception $exc){
                    // TODO : à supprimer
                    var_dump($exc->getMessage(), $exc->getTraceAsString());
                    $conn->rollback();
                }
            }

            $chrono->arreterEtAfficherChrono("Génération de la fonction à la position " . $position, $globalCh);
        }
    }

    /**
     * Méthode permettant de générer le jeu de test.
     *
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeGenerateTestSet(sfWebRequest $request)
    {
        $this->getResponse()->setContentType('application/json');
        $this->setLayout(false);

        $chronometre1 = new Chronometre();
        $chronometre2 = new Chronometre();
        $chronometre3 = new Chronometre();
        $chronometre4 = new Chronometre();
        $chronometre5 = new Chronometre();
        $keyword = "PERFORMANCE JDT - ";

        // Début mesure.
        $chronometre1->lancerChrono($keyword."LANCEMENT GENERATION DU JEU DE TEST");

        /** @var EiCampaignExecutionGraphTable $tableExecutionGraph */
        $tableExecutionGraph = Doctrine_Core::getTable("EiCampaignExecutionGraph");
        /** @var EiTestSetTable $tableTestSet */
        $tableTestSet = Doctrine_Core::getTable("EiTestSet");
        // Récupération des éléments relatifs à la campagne (si existe).
        $executionId = $request->getParameter("execution_id");
        $stepId = $request->getParameter("graph_id");

        try {
            $this->getUrlParameters($request);

            // Choix du navigateur.
            $deviceChoice = $request->getParameter("device");

            $testSet = new EiTestSet();
            $testSet->setEiScenarioId($this->ei_scenario->getId());
            $testSet->setEiVersionId($this->ei_version->getId());
            $testSet->setProfileId($this->profile_id);
            $testSet->setProfileRef($this->profile_ref);
            $testSet->setModeByIde($request->getParameter("exec_mode"));
            $testSet->setAuthorId($this->user->getGuardId());
            $testSet->setDevice($deviceChoice == null || !DevicesConst::isValid($deviceChoice) ? DevicesConst::SELENIUM_IDE:$deviceChoice);
            $testSet->setEiIteration($this->getRequestActiveIteration());

            if($this->ei_data_set instanceof EiDataSet){
                $testSet->setEiDataSetId($this->ei_data_set->getEiDataSetTemplate()->getEiDataSetRefId());

                // Début mesure.
                $chronometre2->lancerChrono($keyword."COMPLETION JDD");
                // Complétion du jeu de données.
                $testSet->getEiDataSet()->completeDataSet();
                // Fin mesure
                $chronometre2->arreterEtAfficherChrono();
            }
            $testSet->save();

            $tableTestSet->searchRelatedEiExecutionStackAndTaggedIt($testSet, $this->ei_project);

            $this->getLogger()->info("-----   Jeu de données à créer.");

            //*******************************************************************//
            //**********     AFFECTATION TEST SET ID A LA CAMPAGNE     **********//
            //*******************************************************************//

            if( $executionId != null && $stepId != null && $executionId != 0 && $stepId != 0 ){
                /** @var EiCampaignExecutionGraph $executionGraph */
                $executionGraph = $executionId != null ? $tableExecutionGraph->findOneByExecutionIdAndGraphId($executionId, $stepId):null;

                // On vérifie que l'étape de l'exécution a bien été trouvée et que l'id du scénario correspond à l'ID
                // du scénario du JDT.
                if( $executionGraph != null && $executionGraph->getEiScenario()->getId() == $testSet->getEiScenarioId() ){
                    $executionGraph->setEiTestSetId($testSet->getId());
                    $executionGraph->save();
                }
            }

            // Génération du jeu de données pour le jeu de test.
            // Début mesure.
            $chronometre3->lancerChrono($keyword."GENERATION JDD");
            $testSet->generateTestSetDataSet();
            // Fin mesure
            $chronometre3->arreterEtAfficherChrono();

            $this->getLogger()->info("-----   Jeu de données créé.");

            $this->getLogger()->info("-----   Paramètres de blocks à créer.");

            // Début mesure.
            $chronometre5->lancerChrono($keyword."GENERATION PARAMS BLOCK");

            // Fin mesure
            $chronometre5->arreterEtAfficherChrono();

            $this->getLogger()->info("-----   Paramètres de blocks créés.");

            // Fin mesure.
            $chronometre1->arreterEtAfficherChrono();

            $JSONResponse['ei_test_set_id'] = $testSet->getId();
            $JSONResponse['ei_data_set_id'] = $testSet->getEiDataSetId() != "" ? $testSet->getEiDataSetId():0;
        }
        catch (Exception $e) {
            $JSONResponse['error'] = $e->getMessage();
        }
        return $this->renderText(json_encode($JSONResponse));
    }
    
    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeGetFunctionsFromPosition(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/xml');

        $this->error = false;

        $chronometre1 = new Chronometre();
        $chronometre2 = new Chronometre();
        $chronometre3 = new Chronometre();
        $chronometre4 = new Chronometre();
        $this->chronometre5 = new Chronometre();
        $keyword = "PERFORMANCE NEXT BLOCK - ";
        $this->keyword = $keyword;

        // Début mesure.
        $chronometre1->lancerChrono($keyword."LANCEMENT GENERATION DU BLOCK SUIVANT");

        if( is_bool($this->error) ){
            try {
                $this->getUrlParameters($request);

                //***********************************************************//
                //*****          RECUPERATION ITERATION ACTIVE          *****//
                //***********************************************************//

                // Récupération de l'itération active.
                $iterationActive = $this->ei_test_set->getEiIteration();
                //Récupération du package par défaut
                $this->defPack = Doctrine_Core::getTable('EiUserDefaultPackage')
                    ->findOneByProjectIdAndProjectRefAndUserIdAndUserRef(
                        $this->ei_project->getProjectId(),
                        $this->ei_project->getRefId(),
                        $this->user->getUserId(),
                        $this->user->getRefId()
                );

                //******************************************************************//
                //*****          GENERATION FONCTIONS DEPUIS POSITION          *****//
                //******************************************************************//

                $position = $request->getParameter("position");

                $this->getLogger()->info("-----   Début génération de la fonction à partir de la position ".$position." pour le JDT N°".$this->ei_test_set->getId().".");

                // Début mesure.
                $chronometre2->lancerChrono($keyword."GENERATION FONCTION A PARTIR DE LA POSITION");
                $this->generateTestSetFunctionFromPosition($this->ei_test_set, $position, $iterationActive);
                // Fin mesure.
                $chronometre2->arreterEtAfficherChrono();

                //********************************************************************//
                //*****          TRAITEMENT DES FONCTIONS POUR TEMPLATE          *****//
                //********************************************************************//

                $this->functions = Doctrine_Core::getTable('EiTestSetFunction')
                    ->getFunctionsFromPosition($this->ei_test_set->getId(), $position, $this->ei_test_set->getProfileId(), $this->ei_test_set->getProfileRef() );

                $this->params = array();
                $treated = array();

                // Début mesure.
                $chronometre3->lancerChrono($keyword."FONCTIONS TO ARRAY");

                foreach( $this->functions as $function ){
                    $idFunc = $function["id"];

                    if( !in_array($idFunc, $treated) ){
                        $this->params[$idFunc] = Doctrine_Core::getTable("EiFunctionHasParam")
                            ->findByFunctionRefAndFunctionId($function["function_ref"], $function["function_id"])
                            ->toArray();
                    }
                }
                // Fin mesure.
                $chronometre3->arreterEtAfficherChrono();

                //*********************************************//
                //*****          SI FIN SCENARIO          *****//
                //*********************************************//

                // Début mesure.
                $chronometre4->lancerChrono($keyword."FIN DE SCENARIO");

                if( count($this->functions) == 0 ){
                    $this->getLogger()->info("-----   Fin de scénario.");
                    $this->ei_test_set->finish();
                }

                // Fin mesure.
                $chronometre4->arreterEtAfficherChrono();

                // Fin mesure.
                $chronometre1->arreterEtAfficherChrono();

            } catch (Exception $e) {
//                var_dump($e);
//                exit;
                return $this->renderText('<error>' . $e->getMessage(). '</error>');
            }
        }
        else{
            return $this->renderText('<error>' . $this->error . '</error>');
        }
    }

    public function executeGenerateEiFunctionXML(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/xml');

        try {
            $this->getUrlParameters($request);

            $ei_fonction_id = $request->getParameter("ei_fonction_id");
            $function = Doctrine_Core::getTable('EiTestSetFunction')
                    ->getEiFunctionAsArray($ei_fonction_id, $request->getParameter("position"), $this->ei_test_set->getId());

            if ($function == null)
                throw new Exception('Function not found.');

            $xml = Doctrine_Core::getTable('EiFonction')
                    ->generateXMLFromTestSet($function, $this->ei_profile);

            return $this->renderText($xml);
        } catch (Exception $e) {
            return $this->renderText('<error>' . $e->getMessage() . '</error>');
        }
    }
}