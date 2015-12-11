<?php

/**
 * eilog actions.
 *
 * @package    kalifastRobot
 * @subpackage eilog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eilogActions extends sfActionsKalifast {

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

    /**
     * @param sfWebRequest $request
     * @throws Exception
     */
    protected function getUrlParameters(sfWebRequest $request) {
        $this->checkUserWebService();

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        //vérifi du scénario
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')
                ->find($request->getParameter('ei_scenario_id'));

        if ($this->ei_scenario == null) {
            throw new Exception("Scenario not found.");
        }


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
        $this->ei_data_set = $request->getParameter('ei_data_set_id');
        if (isset($this->ei_data_set)) {

            if ($this->ei_data_set > 0) {
                $this->ei_data_set = Doctrine_Core::getTable('EiDataSet')
                        ->find($request->getParameter('ei_data_set_id'));

                if ($this->ei_data_set == null) {
                    throw new Exception("Data set not found.");
                }
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
     * Webservice créant une nouvelle log. 
     * @param sfWebRequest $request
     * @return type
     */
    public function executeCreateLog(sfWebRequest $request) {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        try {
            $this->getUrlParameters($request);

            $log = new EiLog();
            $log->setEiDataSetId($this->ei_test_set->getEiDataSetId());
            $log->setEiScenario($this->ei_scenario);
            $log->setEiTestSet($this->ei_test_set);
            $log->setEiIteration($this->ei_test_set->getEiIteration());
            $log->setEiVersion($this->ei_version);
            $log->setProfileId($this->profile_id);
            $log->setProfileRef($this->profile_ref);
            $log->setUserRef($this->user->getRefId());
            $log->setUserId($this->user->getUserId());
            $log->save();

            $JSONResponse = array("ei_log_id" => $log->getId());
        } catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * Ordonne au serveur de cloturer la log en lui associant la date de fin d'execution
     * @param sfWebRequest $request
     * @return type
     * @throws Exception
     */
    public function executeEndCurrentLog(sfWebRequest $request){
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        try {
            $this->getUrlParameters($request);

            $log = Doctrine_Core::getTable('EiLog')->findOneByIdAndUserIdAndUserRefAndEiTestSetId(
                        $request->getParameter('ei_log_id'),
                        $this->user->getUserId(),
                        $this->user->getRefId(),
                        $this->ei_test_set->getId()
                    );
            
            if($log){
                $log->closeExecution();
                $JSONResponse = array("ok" => "Log closed successfully.");
            }
            else{
                throw new Exception('Log not found.');
            }
            
        } catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));
    }
    
    /**
     * Webservice permettant la mise à jour des logs du jeu de test.
     *
     * @param sfWebRequest $request
     * @return type
     * @throws Exception
     */
    public function executeRetrieveLogs(sfWebRequest $request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $this->logger = sfContext::getInstance()->getLogger();

        $statutPlaying = StatusConst::STATUS_PROCESSING_DB;
        $statutOk = StatusConst::STATUS_OK_DB;
        $statutKo = StatusConst::STATUS_KO_DB;
        /** @var EiTestSetTable $tableJDT */
        $tableJDT = Doctrine_Core::getTable("EiTestSet");
        $synchronized = false;

        try
        {
            $this->getUrlParameters($request);
            $JSONStr = $request->getParameter('logs');
            
            $ei_log = Doctrine_Core::getTable('EiLog')->findOneByEiTestSetIdAndEiScenarioIdAndProfileIdAndProfileRefAndId(
                    $this->ei_test_set->getId(), $this->ei_scenario->getId(), $this->profile_id, $this->profile_ref, $request->getParameter('ei_log_id')
            );

            $this->logger->debug($JSONStr);

            $JSONArray = json_decode($JSONStr); 
            $ei_log_st = $statutPlaying;
            $i = 0;
            
            // Récupération de l'itération active selon le projet & profil.
            $iterationActive = $this->ei_test_set->getEiIteration();

            //***************************************************************************//
            //*****     PARCOURS DE LA LISTE DE FONCTIONS/LOGS EXECUTE(E)S JSON     *****//
            //***************************************************************************//

            foreach ($JSONArray as $i => $data)
            {
                /** @var EiTestSetFunction $test_set_function */
                $test_set_function = Doctrine_Core::getTable('EiTestSetFunction')
                        ->findOneByPositionAndEiTestSetId($data->{'position'}, $this->ei_test_set->getId());

                if (is_null($test_set_function)) {
                    throw new Exception($data->{'position'} . " not found.");
                }

                //si une fonction a le status KO, on met KO au log.
                if ($data->{'resultat'} == $statutKo)
                    $ei_log_st = $statutKo;
                elseif ($data->{'resultat'} == $statutOk)
                    $ei_log_st = $statutOk;

                //assignation des valeurs pour les logs de la dernière execution du jeu de test.
                $test_set_function->setStatus($data->{'resultat'});
                $test_set_function->setDateDebut($data->{'datedebut'});
                $test_set_function->setDateFin($data->{'datefin'});            
                $test_set_function->setDuree($data->{'duree'});

                //*************************************************//
                //*****     Création des logs d'execution     *****//
                //*************************************************//

                $log = new EiLogFunction();
                $log->setEiFonction($test_set_function->getEiFonction());
                $log->setFunctionRef($test_set_function->getFunctionRef());
                $log->setFunctionId($test_set_function->getFunctionId());
                $log->setEiIteration($iterationActive);
                $log->setEiLogId($ei_log->getId());
                $log->setEiTestSetFunctionId($test_set_function->getId());
                $log->setEiTestSetId($this->ei_test_set->getId());
                $log->setEiScenarioId($this->ei_scenario->getId());
                $log->setPosition($test_set_function->getPosition());
                $log->setDateDebut($data->{'datedebut'});
                $log->setDateFin($data->{'datefin'});
                $log->setDuree($data->{'duree'});
                $log->setStatus($ei_log_st);

                //*******************************************************************//
                //*****     Création des logs des paramètres de la fonction     *****//
                //*******************************************************************//

                $params = $test_set_function->getEiTestSetParams();
                $paramsColl = new Doctrine_Collection('EiLogParam');
                
                if($params)
                {
                    foreach($params as $p => $param){
                        $paramLog = new EiLogParam();
                        $paramLog->setEiLogId($ei_log->getId());
                        $paramLog->setEiIteration($iterationActive);
                        $paramLog->setFunctionId($test_set_function->getFunctionId());
                        $paramLog->setFunctionRef($test_set_function->getFunctionRef());
                        $paramLog->setEiTestSetId($this->ei_test_set->getId());
                        $paramLog->setParamId($param->getParamId());
                        $paramLog->setParamValeur($param->getValeur());
                        $paramLog->setParamName($param->getEiFunctionHasParam()->getName());
                        
                        $ei_param = Doctrine_Core::getTable('EiParam')
                                ->findOneByParamIdAndIdFonction($param->getParamId(), $test_set_function->getEiFonction()->getId());
                        
                        $paramLog->setEiParam($ei_param);
                        $paramLog->setEiLogFunction($log);
                        $paramsColl->add($paramLog);
                    }
                }

                //*****************************************************************//
                //*****     Traitement des paramètres retournés par l'IDE     *****//
                //*****************************************************************//

                foreach($data->{"parameters"} as $paramName => $paramValue)
                {
                    //****************************************************************************//
                    //*****     Recherche du paramètre de sortie dans la base de données     *****//
                    //****************************************************************************//

                    /** @var EiFunctionHasParam $paramBD */
                    $paramBD = Doctrine_Core::getTable('EiFunctionHasParam')
                            ->findOneByFunctionRefAndFunctionIdAndNameAndParamType(
                                    $test_set_function->getEiFonction()->getFunctionRef(),
                                    $test_set_function->getEiFonction()->getFunctionId(),
                                    $paramName,
                                    "OUT");

                    // On détermine le XPATH.
                    $xpathF = $test_set_function->getXpath();
                    $xpathF.= $xpathF == "/Root" ? "[1]":"";

                    $paramsToUpdate = array();

                    $position = $data->{'position'};
                    $request->setParameter("position", $position);

                    //si un paramètre de sorti a été trouvé, alors on renseigne sa log.
                    if($paramBD)
                    {
                        $this->logger->info("---   Synchronisation des paramètres de fonction avec les paramètres de block   ---");
                        $this->logger->info("-----------------------------------------------------------------------------------");
                        $this->logger->info("---   Paramètre de fonction trouvé : " . $paramBD->getName());
                        $this->logger->info("---   Nb de mapping du paramètre trouvé(s) : " . $paramBD->getEiFunctionParamMapping()->count());

                        // Création du log du paramètre.
                        $paramLog = new EiLogParam();
                        $paramLog->setEiLogId($ei_log->getId());
                        $paramLog->setEiIteration($iterationActive);
                        $paramLog->setFunctionId($test_set_function->getFunctionId());
                        $paramLog->setFunctionRef($test_set_function->getFunctionRef());
                        $paramLog->setEiTestSetId($this->ei_test_set->getId());
                        $paramLog->setParamId($paramBD->getParamId());
                        $paramLog->setParamValeur($paramValue);
                        $paramLog->setParamName($paramBD->getName());
                        $paramLog->setParamType($paramBD->getParamType());
                        $paramLog->setEiLogFunction($log);
                        $paramsColl->add($paramLog);
                        
                        $paramTestSet = Doctrine_Core::getTable('EiTestSetParam')
                                ->findOneByEiTestSetFunctionIdAndParamIdAndParamType($test_set_function->getId(), $paramBD->getParamId(), "OUT");
                        
                        if($paramTestSet){
                            $paramTestSet->setValeur($paramValue);
                            $paramTestSet->save();
                        }

                        //****************************************************************************************************//
                        //*****     SYNCHRONISATION DES PARAMETRES DE FONCTION DANS LES PARAMETRES DE BLOCK ASSOCIES     *****//
                        //****************************************************************************************************//

                        /** @var EiParamBlockFunctionMapping[] $mappings */
                        $mappings = $paramBD->getEiFunctionParamMapping();

                        foreach( $mappings as $mapping )
                        {
                            $this->logger->info("---   Mapping Fonction ID : " . $mapping->getEiFunctionId());
                            $this->logger->info("---   Log Fonction ID : " . $log->getEiFonctionId());
                            $this->logger->info("---   Mapping Param Block ID : " . $mapping->getEiParamBlockId());
                            $this->logger->info("---   Param Block PATH : " . $xpathF . "/" . $mapping->getEiBlockParamMapping()->getName());

                            if( $mapping->getEiFunctionId() == $log->getEiFonctionId() && $mapping->getEiParamBlockId() != "" )
                            {
                                // Récupération du paramètre du block correspondant du jeu de test.
                                /** @var EiTestSetBlockParam $blockParam */
                                $blockParam = EiTestSetBlockParamTable::getInstance()->findOneByPathAndEiVersionStructureIdAndEiTestSetId(
                                    $xpathF . "/" . $mapping->getEiBlockParamMapping()->getName(),
                                    $mapping->getEiParamBlockId(),
                                    $test_set_function->getEiTestSetId()
                                );

                                if( $blockParam != null ){
                                    $this->logger->info("---   Param to UPDATE : " . "'#{".$blockParam->getName()."}'" );
                                    $synchronized = true;
                                    $paramsToUpdate[] = "'#{".$blockParam->getName()."}'";
                                    $blockParam->setValue($paramValue);
                                    $blockParam->save();
                                }
                            }
                        }
                    }

                    //************************************************************************************************//
                    //*****     MISE A JOUR DES VALEURS DES PARAMETRES DES FONCTIONS SUIVANTES DEJA GENEREES     *****//
                    //************************************************************************************************//

                    $tableJDT->updateParamsFunctionToSynchronize($test_set_function->getEiTestSet(), $xpathF, $position, $paramsToUpdate);
                }
                
                $test_set_function->save();
                
                $log->setEiLogParams($paramsColl);
                $log->save();
                
                //*********************************//
                //*****     LOG DE SONDES     *****//
                //*********************************//
                
                $sensor = $data->{'sensors'};
                if(isset($sensor))
                {
                    $logSensor = new EiLogSensor();
                    $logSensor->setEiLogFunctionId($log->getId());
                    $logSensor->setAppMemoryMean($sensor->{'app_memory_mean'});
                    $logSensor->setAppMemoryMin($sensor->{'app_memory_min'});
                    $logSensor->setAppMemoryMax($sensor->{'app_memory_max'});
                    $logSensor->setAppMemoryStart($sensor->{'app_memory_start'});
                    $logSensor->setAppMemoryEnd($sensor->{'app_memory_end'});
                    $logSensor->setAppCpuMean($sensor->{'app_cpu_mean'});
                    $logSensor->setAppCpuMin($sensor->{'app_cpu_min'});
                    $logSensor->setAppCpuMax($sensor->{'app_cpu_max'});
                    $logSensor->setAppCpuStart($sensor->{'app_cpu_start'});
                    $logSensor->setAppCpuEnd($sensor->{'app_cpu_end'});
                    $logSensor->setDbMemoryMean($sensor->{'db_memory_mean'});
                    $logSensor->setDbMemoryMin($sensor->{'db_memory_min'});
                    $logSensor->setDbMemoryMax($sensor->{'db_memory_max'});
                    $logSensor->setDbMemoryStart($sensor->{'db_memory_start'});
                    $logSensor->setDbMemoryEnd($sensor->{'db_memory_end'});
                    $logSensor->setDbCpuMean($sensor->{'db_cpu_mean'});
                    $logSensor->setDbCpuMin($sensor->{'db_cpu_min'});
                    $logSensor->setDbCpuMax($sensor->{'db_cpu_max'});
                    $logSensor->setDbCpuStart($sensor->{'db_cpu_start'});
                    $logSensor->setDbCpuEnd($sensor->{'db_cpu_end'});
                    $logSensor->setClientMemoryMean($sensor->{'client_memory_mean'});
                    $logSensor->setClientMemoryMin($sensor->{'client_memory_min'});
                    $logSensor->setClientMemoryMax($sensor->{'client_memory_max'});
                    $logSensor->setClientMemoryStart($sensor->{'client_memory_start'});
                    $logSensor->setClientMemoryEnd($sensor->{'client_memory_end'});
                    $logSensor->setClientCpuMean($sensor->{'client_cpu_mean'});
                    $logSensor->setClientCpuMin($sensor->{'client_cpu_min'});
                    $logSensor->setClientCpuMax($sensor->{'client_cpu_max'});
                    $logSensor->setClientCpuStart($sensor->{'client_cpu_start'});
                    $logSensor->setClientCpuEnd($sensor->{'client_cpu_end'});
                    $logSensor->save();
                }
                
                //******************************************************************//
                //*****     RECENSEMENT DES LOGS SELENIUM POUR LA FONCTION     *****//
                //******************************************************************//

                // Récupération de la connection.
                $dbh = Doctrine_Manager::connection();
                $logS = new EiLogFunctionSelenium();

                // Création de la requête préparée pour l'insertion des logs.
                $query = "INSERT INTO ".$logS->getTable()->getTableName()." (ei_fonction_id, ei_log_id, ei_test_set_function_id, ei_test_set_id, ";
                $query.= "ei_scenario_id, ei_log_function_id, message, created_at, updated_at)";
                $query.= "VALUES (:fonction,:log,:tsfid,:tsid,:scid,:logfid,:message,NOW(),NOW())";

                /** @var Doctrine_Connection_Statement $stmt */
                $stmt = $dbh->prepare($query);

                $dbh->beginTransaction();

                foreach($data->{"logs"} as $logSelenium)
                {
                    $stmt->bindValue("fonction", $test_set_function->getEiFonctionId());
                    $stmt->bindValue("log", $ei_log->getId());
                    $stmt->bindValue("tsfid", $test_set_function->getId());
                    $stmt->bindValue("tsid", $this->ei_test_set->getId());
                    $stmt->bindValue("scid", $this->ei_scenario->getId());
                    $stmt->bindValue("logfid", $log->getId());
                    $stmt->bindValue("message", $logSelenium);

                    $stmt->execute(array());
                }

                $dbh->commit();


                //**************************************************************************************//
                //*****     VERIFICATION FIN DE BLOCK POUR SYNCHRONISATION AVEC JEU DE DONNEES     *****//
                //**************************************************************************************//

                // Récupération du xpath et traitement en cas d'exception /Root.
                $xpathB = $test_set_function->getXpath() == "/Root" ? "/Root[1]":$test_set_function->getXpath();

                $nbFonctionsExecuteesBlock = EiTestSetFunctionTable::getInstance()->findByEiTestSetIdAndXpath(
                    $test_set_function->getEiTestSetId(),
                    $test_set_function->getXpath() // Ici, on utilise le xpath de la fonction dont root = /Root
                )->count();

                $this->logger->info("---   Synchronisation des paramètres en fin de block   ---");
                $this->logger->info("----------------------------------------------------------");
                $this->logger->info("---   NB fonctions exécutées : " . $nbFonctionsExecuteesBlock);

                /** @var EiTestSetBlockParam $testSetBlockParam */
                // On récupère le paramètre de block à mettre à jour relativement au xpath.
                $testSetBlockParam = EiTestSetBlockParamTable::getInstance()->findOneByEiTestSetIdAndPath(
                    $test_set_function->getEiTestSetId(),
                    $xpathB // Ici, on utilise le xpath du block root dont root = /Root[1]
                );

                // On vérifie qu'il est bien non null et relatif à un élément de la structure de la version.
                if( $testSetBlockParam != null && $testSetBlockParam->getEiVersionStructure() != null ){
                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   Matched : " . $xpathB);
                    // On récupère le nombre de fonctions que comporte le block.
                    $nbFonctionsBlock = $testSetBlockParam->getEiVersionStructure()->getNbFonctions();
                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   NB FONCTIONS BLOCK " . $nbFonctionsBlock);

                    // SI même nombre, alors on synchronise.
                    if( $nbFonctionsBlock == $nbFonctionsExecuteesBlock ){
                        $this->logger->info("----------------------------------------------------------");
                        $this->logger->info("---   SYNCHRONISATION DE " . $testSetBlockParam->getName());
                        $testSetBlockParam->synchronizeWithDataSet();
                    }
                }

            }

            $ei_log->setStatus($ei_log_st);

            $ei_log->save();

            //*****************************************************//
            //*****      PARTIE GESTION STATUTS CAMPAGNES     *****//
            //*****************************************************//

            $this->logger->info("----------------------------------------------------------");
            $this->logger->info("---   MAJ CAMPAGNE");
            $this->logger->info("----------------------------------------------------------");

            // On regarde s'il s'agit du dernier block des logs.
            if( (count($JSONArray) - 1) == $i )
            {
                $this->logger->info("----------------------------------------------------------");
                $this->logger->info("---   Dernier block des logs à la position (".$data->{'position'}.")");
                $this->logger->info("----------------------------------------------------------");

                // Puis on récupère le block à la position suivante.
                $nextTestSetFunction = Doctrine_Core::getTable('EiTestSetFunction')
                    ->findOneByPositionAndEiTestSetId($data->{'position'} + 1, $this->ei_test_set->getId());

                // S'il n'y a pas de block suivant et que la position est 1, on met à jour directement car block unique.
                if( $nextTestSetFunction == null && $data->{'position'} == 1 ){

                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   Pas de fonction après & position = 1");
                    $this->logger->info("----------------------------------------------------------");

                    // Mise à jour directe du statut du scénario.
                    $this->searchAndUpdateCampaignGraphState($request->getParameter("campagne"), $request->getParameter("campPosition"), $statutPlaying, $request->getParameter("execution_id"), $ei_log);
                }
                // S'il n'y en a pas mais qu'il y a plusieurs blocks, on va vérifier si le scénario a comporté des erreurs.
                elseif( $nextTestSetFunction == null && $data->{'position'} > 1 ){

                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   Pas de fonction après & position > 1");
                    $this->logger->info("----------------------------------------------------------");

                    // Puis on récupère le block à la position suivante.
                    $nbBlockErrors = Doctrine_Core::getTable('EiTestSetFunction')->findByStatusAndEiTestSetId($statutKo, $this->ei_test_set->getId());

                    // Si nombre de blocks en erreur supérieur à 0, on indique le statut KO sinon OK.
                    $ei_log_st = ( $nbBlockErrors != null && $nbBlockErrors->count() > 0 ) ? $statutKo:$statutOk;

                    // Enfin, nous mettons à jour le statut du scénario.
                    $this->searchAndUpdateCampaignGraphState($request->getParameter("campagne"), $request->getParameter("campPosition"), $statutPlaying, $request->getParameter("execution_id"), $ei_log);
                }
                // S'il n'y en a pas mais qu'il y a plusieurs blocks, on va vérifier si le scénario a comporté des erreurs.
                elseif( $request->getParameter("positionOnError") == "Stop" && $ei_log_st == $statutKo ){

                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   Une fonction après & On Error Stop & statut KO");
                    $this->logger->info("----------------------------------------------------------");

                    $ei_log_st = $statutKo;

                    // Enfin, nous mettons à jour le statut du scénario.
                    $this->searchAndUpdateCampaignGraphState($request->getParameter("campagne"), $request->getParameter("campPosition"), $ei_log_st, $request->getParameter("execution_id"), $ei_log);
                }
            }


            if( $synchronized === true ){
                $JSONResponse = array(
                    "ok" => "Synchronized.",
                    "xml" => $this->getController()->getPresentationFor("eitestset", "getFunctionsFromPosition"),
//                    "synP" => $synP
                );
            }
            else{
                $JSONResponse = array("ok" => "Log saved.");
            }
        }
        catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }
        return $this->renderText(json_encode($JSONResponse));
    }


    /**
     * @param sfWebRequest $request
     */
    public function executeAbortTestSet(sfWebRequest $request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $this->logger = sfContext::getInstance()->getLogger();

        try{
            $test_set_id = $request->getParameter("ei_test_set_id");
            $position = $request->getParameter("position");
            /** @var EiTestSetTable $tableTestSet */
            $tableTestSet = Doctrine_Core::getTable("EiTestSet");
            /** @var EiTestSet $testSet */
            $testSet = $tableTestSet->find($test_set_id);

            $this->logger->info("----------------------------------------------------------");
            $this->logger->info("---   Abort test set with " . $test_set_id . ", " . $position);
            $this->logger->info("----------------------------------------------------------");

            // Si on a bien récupéré un jeu de test, on le clôs.
            if( $testSet != null && $testSet->getId() != "" ){
                $testSet->finish(true);
            }

            $JSONResponse = array("ok" => "Log saved.");
        }
        catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeStartCampaignStep(sfWebRequest $request){

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        try{
            $this->searchAndUpdateCampaignGraphState($request->getParameter("campagne"), $request->getParameter("position"), StatusConst::STATUS_PROCESSING_DB, $request->getParameter("execution_id"));

            $JSONResponse = array("ok" => "Log saved.");
        }
        catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));

    }


    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeFinishedCampaignStep(sfWebRequest $request){

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $this->logger = sfContext::getInstance()->getLogger();

        try{
            $campagne = $request->getParameter("campagne");
            $position = $request->getParameter("position");
            $execution_id = $request->getParameter("execution_id");

            $this->logger->info("----------------------------------------------------------");
            $this->logger->info("---   Finish with " . $campagne . ", " . $execution_id . ", " . $position);
            $this->logger->info("----------------------------------------------------------");

            if( $campagne != "" && $position != "" && $execution_id != "" ){
                // Mise à jour du statut de l'élément de la campagne.
                $this->searchAndUpdateCampaignGraphState($campagne, $position, null, $execution_id);
                // Fermeture de l'exécution.
//                Doctrine_Core::getTable("EiCampaignExecution")->closeExecution($execution_id);
            }

            $JSONResponse = array("ok" => "Log saved.");
        }
        catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));
    }


    /**
     * @param sfWebRequest $request
     * @return sfView
     */
    public function executeAbortedCampaignStep(sfWebRequest $request){

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');
        $this->logger = sfContext::getInstance()->getLogger();

        try{
            $campagne = $request->getParameter("campagne");
            $position = $request->getParameter("position");
            $execution_id = $request->getParameter("execution_id");

            $this->logger->info("----------------------------------------------------------");
            $this->logger->info("---   Aborted with " . $campagne . ", " . $execution_id . ", " . $position);
            $this->logger->info("----------------------------------------------------------");

            if( $campagne != "" && $position != "" && $execution_id != "" ){
                // Mise à jour du statut de l'élément de la campagne.
                $this->searchAndUpdateCampaignGraphState($campagne, $position, null, $execution_id);
                // Fermeture de l'exécution.
                Doctrine_Core::getTable("EiCampaignExecution")->closeExecution($execution_id);
            }

            $JSONResponse = array("ok" => "Log saved.");
        }
        catch (Exception $e) {
            $JSONResponse = array("error" => $e->getMessage());
        }

        return $this->renderText(json_encode($JSONResponse));

    }

    /**
     * @param $campagneId
     * @param $positionId
     * @param $ei_log_st
     * @param $execution_id
     * @param EiLog $ei_log
     * @return EiCampaignGraph|null
     */
    private function searchAndUpdateCampaignGraphState($campagneId, $positionId, $ei_log_st, $execution_id, $ei_log = null)
    {
        $campagneGraph = null;
        /** @var EiTestSetTable $tableEiTestSet */
        $tableEiTestSet = Doctrine_Core::getTable("EiTestSet");
        /** @var EiCampaignExecutionGraph $executionGraph */
        $executionGraph = $execution_id != null ? Doctrine_Core::getTable("EiCampaignExecutionGraph")->findOneByExecutionIdAndGraphId($execution_id, $positionId):null;

        // Si les deux ne sont pas nuls, on tente une mise à jour du statut.
        if( $campagneId !== null && $positionId !== null ){
            /** @var EiCampaignGraph $campagneGraph */
            $campagneGraph = Doctrine_Core::getTable("EiCampaignGraph")->getCampaignGraphStep($positionId);

            // Si une exécution est spécifiée, on met à jour l'élément de l'exécution.
            if( $executionGraph !== null || (is_bool($executionGraph) && $executionGraph == true) ){
                $graph = $executionGraph;
                $campagneIdG = $graph->getEiCampaignGraph()->getCampaignId();
            }
            // Sinon, les statuts de la campagne.
            else{
                $graph = $campagneGraph;
                $campagneIdG = $graph->getCampaignId();
            }

            if( $graph != null && $campagneIdG == intval($campagneId) ){

                // Mise à jour du statut en fonction des résultats des logs.
                if( $ei_log_st == null && $graph->getEiTestSetId() != "" ){

                    $this->logger->info("----------------------------------------------------------");
                    $this->logger->info("---   CASE ST NULL & TEST SET NOT NULL");

                    $ts = $tableEiTestSet->findTestSet($graph->getEiTestSetId());

                    $ei_log_st = $ts->getStatusName() == StatusConst::STATUS_TEST_OK_DB ? StatusConst::STATUS_OK_DB:
                        ($ts->getStatusName() == StatusConst::STATUS_TEST_KO_DB ? StatusConst::STATUS_KO_DB:StatusConst::STATUS_NA_DB);

                    $this->logger->info("---   EI LOG ST : " . $ei_log_st . " / " . $ts->getStatusName());
                    $this->logger->info("----------------------------------------------------------");
                }

                if( $ei_log_st != null && $ei_log_st == StatusConst::STATUS_OK_DB ){
                    $graph->setState(sfConfig::get("app_campaigngraphstateok"));
                }
                elseif( $ei_log_st != null && $ei_log_st == StatusConst::STATUS_KO_DB ){
                    $graph->setState(sfConfig::get("app_campaigngraphstateko"));
                }
                elseif( $ei_log_st != null && $ei_log_st == StatusConst::STATUS_PROCESSING_DB ){
                    $graph->setState(sfConfig::get("app_campaigngraphstateprocessing"));
                }
                elseif( $ei_log_st != null && $ei_log_st == StatusConst::STATUS_NA_DB ){
                    $graph->setState(sfConfig::get("app_campaigngraphstateaborted"));
                }
                else{
                    $graph->setState(sfConfig::get("app_campaigngraphstateblank"));
                }

                if( $ei_log != null ){
                    $graph->setEiTestSetId($ei_log->getEiTestSetId());

                    // Mise à jour du mode du JDT si <> Campagne
                    if( $ei_log->getEiTestSet()->getMode() != EiTestSetModeConst::MODE_CAMPAGNE ){
                        $ei_log->getEiTestSet()->setMode(EiTestSetModeConst::MODE_CAMPAGNE);
                        $ei_log->getEiTestSet()->save();
                    }
                }

                $graph->save();
            }
        }

        return $campagneGraph;
    }

}
