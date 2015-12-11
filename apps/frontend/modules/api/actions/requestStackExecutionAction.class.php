<?php

/**
 * Class requestScenarioExecutionAction
 */
class requestStackExecutionAction extends sfActions{
    /**
     * Action permettant d'indiquer le statut d'un robot et de retourner le cas échéant quelque chose à faire.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        try {
            $this->setLayout(false);
            $this->getResponse()->setContentType('application/json');

            $response = array();

            $statut = $request->getParameter("status");
            $device_identitifier = $request->getParameter("mac");

            // Récupération de la table gérant les exécutions.
            /** @var EiExecutionStackTable $tableExecutionStack */
            $tableExecutionStack = Doctrine_Core::getTable("EiExecutionStack");

            // Récupération de la connexion.
            $conn = Doctrine_Manager::connection();
            // Récupération du gestionnaire de vérouillage.
            $lockingManager = new Doctrine_Locking_Manager_Pessimistic($conn);

            if( $device_identitifier != null && $statut != null && $statut == 1 ){
                $device = Doctrine_Core::getTable("EiDevice")->findOneBy('device_identifier', $device_identitifier);
                $device_id = intval($device->getId());

                /* On arrête les exécutions précédentes */
                $tableExecutionStack->abortPreviousProcessingExecutions($device_id);
                
                /* On recherche la première tâche de la liste assignée à notre device */
                $firstElement = $tableExecutionStack->getFirstElementToExecuteForDevice($device_id);
                if ($firstElement == null) {
                    $devicedrivers = $device->getEiDeviceDriver();
                    
                    /* S'il n'en existe pas, on va rechercher une tâche parmi celles non assignées à un device et sur un couple driver / browser que le device possède  */
                    foreach ($devicedrivers as $devicedriver)
                    {
                        $driverBrowsers = $devicedriver->getEiDriverBrowser();
                        foreach ($driverBrowsers as $driverBrowser)
                        {
                            $firstElement = $tableExecutionStack->getFirstElementToExecuteForDriversAndBrowsers($devicedriver['driver_type_id'], $driverBrowser['browser_type_id'], $device_id );
                            if($firstElement != null)
                            {
                                /* On arrête dès que l'on trouve la première tâche remplissant les conditions décrites précédemment */
                                /* On met ici à jour le device_id de l'exécution */
                                $tableExecutionStack->updateDeviceId($firstElement->getId(), $device_id);
                                break;
                            }
                        }
                    }
                }

                if( $firstElement != null && !is_bool($firstElement) ){
                    $lockingManager->releaseAgedLocks(30);
                    $gotLock = $lockingManager->getLock($firstElement, "robot");

                    if ($gotLock){

                        /** @var EiProfil $profile */
                        $profile = Doctrine_Core::getTable("EiProfil")->findOneByProjectRefAndProjectIdAndProfileRefAndProfileId(
                            $firstElement->getProjectRef(),
                            $firstElement->getProjectId(),
                            $firstElement->getProfileRef(),
                            $firstElement->getProfileId()
                        );

                        /** @var EiProjet $project */
                        $project = Doctrine_Core::getTable("EiProjet")->findOneByRefIdAndProjectId(
                            $firstElement->getProjectRef(),
                            $firstElement->getProjectId()
                        );

                        // Récupération de la première demande non-traitée.
                        $firstElement->setStatus(StatusConst::STATUS_PROCESSING_DB);
                        $firstElement->setUpdatedAt(date("Y-m-d H:i:s"));

                        $response = array(
                            "profile_ref" => intval($firstElement->getProfileRef()),
                            "profile_id" => intval($firstElement->getProfileId()),
                            "profile_name" => $profile->getName(),
                            "project_ref" => intval($firstElement->getProjectRef()),
                            "project_id" => intval($firstElement->getProjectId()),
                            "project_name" => $project->getName(),
                            "token" => $firstElement->getSfGuardUser()->getEiUser()->getTokenApi()
                        );

                        if( $firstElement->isCampaign() ){
                            $response["type"] = "campaign";

                            //***********************************************//
                            //**********     GENERER EXECUTION     **********//
                            //***********************************************//

                            $response["campaign_id"] = intval($firstElement->getEiCampaignId());
                            $response["start_pos"] = intval($firstElement->getStartPos());
                            $response["end_pos"] = intval($firstElement->getEndPos());

                            // Si des positions de début/fin ont été fixées, ont les précisent sinon on prend celles par défaut.
                            $request->setParameter("projet", $project->getName());
                            $request->setParameter("profile_name", $profile->getName());
                            $request->setParameter("profil", $profile->getName());
                            $request->setParameter("campagne", $firstElement->getEiCampaignId());
                            $request->setParameter("position_debut", $firstElement->getStartPos());
                            $request->setParameter("position_fin", $firstElement->getEndPos());
                            $request->setParameter("onError", $firstElement->getEiCampaign()->getOnError());
                            $request->setParameter("internal", true);

                            // Authentification de l'utilisateur.
                            $this->getUser()->signIn($firstElement->getSfGuardUser(), true);

                            $this->getController()->getPresentationFor("eicampaignexecution", "create");

                            $firstElement->setEiCampaignExecutionId($this->getUser()->getAttribute("executionIdForRobot"));

                            //************************************************//
                            //**********     AJOUT EXECUTION ID     **********//
                            //************************************************//

                            $response["execution_id"] = intval($firstElement->getEiCampaignExecutionId());
                        }
                        else{
                            $response["type"] = "scenario";
                            $response["scenario_name"] = $firstElement->getEiScenario()->getNomScenario();
                            $response["scenario_id"] = intval($firstElement->getEiScenarioId());
                            $response["jdd_id"] = $firstElement->getEiDataSetId() == "" ? 0:intval($firstElement->getEiDataSetId());
                        }
                        $driver = Doctrine_Core::getTable("EiDriverType")->findOneBy("id", $firstElement->getDriverId());
                        $browser = Doctrine_Core::getTable("EiBrowserType")->findOneBy("id", $firstElement->getBrowserId());
                        $response["driver_type"] = $driver['hidden_name'];
                        $response["browser_type"] = $browser['hidden_name'];
                        $firstElement->save($conn);
                    }
                    else {
                        $response["error"] = "An error occurred during processing.";
                    }
                }
                else{
                    $response["error"] = "Nothing to execute.";
                }
            }
            else{
                $response["error"] = "Invalid parameters or robot busy.";
            }
        } catch (Exception $ex) {
            $response["error"] = "An error occurred during processing: " . $ex->getMessage();
            $response["success"] = false;
        }
            
        return $this->renderText(json_encode($response));
    }
} 