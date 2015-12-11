<?php

/**
 * eicampaignexecution actions.
 *
 * @package    kalifastRobot
 * @subpackage eicampaignexecution
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eicampaignexecutionActions extends sfActionsKalifast
{
    /**
     * Listing of executions
     *
     * @param sfWebRequest $request
     */
    public function executeCampaignReportsIndex(sfWebRequest $request)
    {
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project); //Recherche du profil
        $this->checkCampaign($request,$this->ei_project); // Recherche de la camapagne.

        /** @var EiCampaignExecutionTable $tableExecs Récupération de la table des exécutions. */
        $tableExecs = Doctrine_Core::getTable('EiCampaignExecution');
        /** @var EiProfilTable $tableProfils */
        $tableProfils = Doctrine_Core::getTable("EiProfil");
        /** @var EiTestSetStateTable $tableStates */
        $tableStates = Doctrine_Core::getTable("EiTestSetState");

        // Récupération des différentes exécutions.
        $this->campaignExecutions = $tableExecs->getAllCampaignExecutions($this->ei_campaign->getId());

        // Récupération des profils.
        $this->ProjectProfilesArray = $tableProfils->getProjectProfilesAsArray($this->ei_project);

        // Récupération des statuts.
        $this->states = $tableStates->findByProjectIdAndProjectRef($this->project_id, $this->project_ref);
    } 
    /* Statistiques d'une execution de campagne */
    
    public function executeStatistics(sfWebRequest $request){
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project); //Recherche du profil
        $this->checkCampaign($request,$this->ei_project); // Recherche de la camapagne. 
        $this->campaign_execution_id = $request->getParameter('campaign_execution_id');
        $res=Doctrine_core::getTable("EiCampaignExecution")->getExecutionDetails($this->campaign_execution_id);
        if(count($res)>0): 
            $this->campaign_execution=$res[0];            
            $this->execFunctions=Doctrine_core::getTable("EiCampaignExecution")->getExecutionFunctions($this->campaign_execution['ce_id']);
        endif; 
    }
    /* Récupération des fonctions non exécutées pour une exécution de campagne */
    public function executeGetUnexecFunctions(sfWebRequest $request){
        $this->forward404unless($request->isXmlHttpRequest());
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project); //Recherche du profil
        $this->checkCampaign($request,$this->ei_project); // Recherche de la camapagne.
        $this->checkCampaignExecution($request,$this->ei_project, $this->ei_campaign); // Recherche de l'exécution de camapagne. 
        $this->unexecFunctions=Doctrine_core::getTable("EiCampaignExecution")->getUnExecutedFunctions($this->project_id,$this->project_ref,$this->campaign_execution_id);   
        $partialResults=$this->urlParameters;
        $partialResults['ei_campaign']=$this->ei_campaign;
        $partialResults['campaign_execution']=$this->campaign_execution;
        $partialResults['unexecFunctions']=$this->unexecFunctions;        
        return $this->renderText(json_encode(array(
                        'html' => $this->getPartial("eicampaignexecution/unexecutedFunctions",$partialResults) ,
                        'success' => true)));
        return sfView::NONE;
    }
    /**
     * Show execution report.
     *
     * @param sfWebRequest $request
     */
    public function executeCampaignReportsShow(sfWebRequest $request)
    {
        $this->checkProject($request); //Recherche du projet concerné
        $this->checkProfile($request,$this->ei_project); //Recherche du profil
        $this->checkCampaign($request,$this->ei_project); // Recherche de la camapagne.
        $this->checkCampaignExecution($request,$this->ei_project, $this->ei_campaign); // Recherche de l'exécution de camapagne.

        /** @var EiProfilTable $tableProfils */
        $tableProfils = Doctrine_Core::getTable("EiProfil");
        /** @var EiCampaignExecutionGraphTable $tableGraphs */
        $tableGraphs = Doctrine_Core::getTable('EiCampaignExecutionGraph');
        /** @var EiTestSetStateTable $tableStates */
        $tableStates = Doctrine_Core::getTable("EiTestSetState");

        // Récupération des profils.
        $this->ProjectProfilesArray = $tableProfils->getProjectProfilesAsArray($this->ei_project);
        $this->graphs = $tableGraphs->getGraphHasChainedList($this->campaign_execution);
        $this->graphsResults = $tableGraphs->getTestSetExecutionInfos($this->campaign_execution);

        // Récupération des statuts.
        $this->states = $tableStates->findByProjectIdAndProjectRef($this->project_id, $this->project_ref);
    }

     /**
      * Create an execution.
      *
      * @param sfRequest $request A request object
      */
      public function executeCreate(sfWebRequest $request)
      {
          $this->setLayout(false);
          $this->getResponse()->setContentType('application/json');

          $resultat = array();
          $erreur = false;
          $id = false;
          $url = false;
          $internal = false;

          // Récupération des valeurs POST.
          if( $request->getParameter("internal") != null && $request->getParameter("internal") == true ){
              $projet_nom = $request->getParameter("projet");
              $profil_nom = $request->getParameter("profil");
              $campagne_id = $request->getParameter("campagne");
              $positionDebut = $request->getParameter("position_debut");
              $positionFin = $request->getParameter("position_fin");
              $onError = $request->getParameter("onError");

              $internal = true;
          }
          else{
              $projet_nom = $request->getPostParameter("projet");
              $profil_nom = $request->getPostParameter("profil");
              $campagne_id = $request->getPostParameter("campagne");
              $positionDebut = $request->getPostParameter("position_debut");
              $positionFin = $request->getPostParameter("position_fin");
              $onError = $request->getPostParameter("onError");
          }

          // Recherche des éléments.
          // On récupère le projet, le profil et la campagne.
          /** @var EiProjet $oProjet */
          $oProjet = Doctrine_Core::getTable("EiProjet")->findOneByName($projet_nom);
          /** @var EiProfil $oProfil */
          $oProfil = ( $oProjet != null ) ? Doctrine_Core::getTable("EiProfil")->findOneByNameAndProjectIdAndProjectRef($profil_nom, $oProjet->getProjectId(), $oProjet->getRefId()):null;
          /** @var EiCampaign $oCampagne */
          $oCampagne = Doctrine_Core::getTable("EiCampaign")->find($campagne_id);

          // On vérifie la concordance entre le projet et le profil puis le projet et la campagne.
          if (!($oProfil != null && $oProjet != null && $oCampagne != null)) {
              $erreur = "Impossible to retrieve Environment, project or campaign.";
          }
          // Concordance Projet/Profil
          elseif (!($oProjet->getProjectId() == $oProfil->getProjectId() && $oProjet->getRefId() == $oProfil->getProjectRef())) {
              $erreur = "Impossible to retrieve project's Environment.";
          }
          // Concordance Projet/Campagne.
          elseif (!($oProjet->getProjectId() == $oCampagne->getProjectId() && $oProjet->getRefId() == $oCampagne->getProjectRef())) {
              $erreur = "Impossible to retrieve project's campaign.";
          }
          // Recherche des scénarios à récupérer.
          else {
              $graphs = Doctrine_Core::getTable("EiCampaign")->getCampaignScenarios($oCampagne, $positionDebut, $positionFin);

              $execution = new EiCampaignExecution();
              $execution->setAuthorId($this->getUser()->getGuardUser()->getId());
              $execution->setProfileId($oProfil->getProfileId());
              $execution->setProfileRef($oProfil->getProfileRef());
              $execution->setProjectId($oProjet->getProjectId());
              $execution->setProjectRef($oProjet->getRefId());
              $execution->setCampaignId($campagne_id);
              $execution->setTermine(false);

              if( is_array($onError) ){
                  $execution->setOnError($onError["id"]);
              }

              $execution->save();

              $position = 1;

              /** @var EiCampaignGraph $graph */
              foreach( $graphs as $graph ){
                  $executionGraph = new EiCampaignExecutionGraph();

                  $executionGraph->setDataSetId($graph->getDataSetId());
                  $executionGraph->setDescription($graph->getDescription());
                  $executionGraph->setExecutionId($execution->getId());
                  $executionGraph->setFilename($graph->getFilename());
                  $executionGraph->setStepTypeId($graph->getStepTypeId());
                  $executionGraph->setGraphId($graph->getId());
                  $executionGraph->setMimeType($graph->getMimeType());
                  $executionGraph->setPath($graph->getPath());
                  $executionGraph->setPosition($position++);
                  $executionGraph->setScenarioId($graph->getScenarioId());
                  $executionGraph->setEiVersion($graph->getEiScenario()->getVersionForProfil($oProfil));
                  $executionGraph->setState($graph->getState());

                  $executionGraph->save();

                  $this->execution = $executionGraph;
              }

              $id  = $execution->getId();
              $url = $this->generateUrl("executionGraphHasChainedList", array(
                  'project_id' => $oProjet->getProjectId(),
                  'project_ref' => $oProjet->getRefId(),
                  'profile_ref' => $request->getParameter("profile_ref"),
                  'profile_id' => $request->getParameter("profile_id"),
                  'profile_name' => $request->getParameter("profile_name"),
                  'campaign_id' => $oCampagne->getId(),
                  'execution_id' => $execution->getId()
              ));

              $this->getUser()->setAttribute("executionIdForRobot", $execution->getId());
          }

          $resultat["erreur"] = $erreur;
          $resultat["id"] = $id;
          $resultat["url"] = $url;

          return $internal != null ? sfView::NONE:$this->renderText(json_encode($resultat));
      }
}
