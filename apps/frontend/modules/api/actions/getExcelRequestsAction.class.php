<?php

/**
 * Class GetExcelRequestAction
 */
class GetExcelRequestsAction extends sfActions
{
    /**
     * @var SfGuardUser
     */
    private $user;

    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );
        $this->user = $this->user->getGuardUser();
    }

    /**
     * Action permettant de créer un data set.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->setLayout(false);
        // On détermine le type de contenu retourné.
        $this->getResponse()->setContentType('application/json');

        // On authentifie l'utilisateur.
        $this->getUser()->signIn($this->user, true);

        // On déclare le résultat à retourner.
        $resultat = array("not_found" => true);
        /** @var EiExcelRequestsTable $table */
        $table = EiExcelRequestsTable::getInstance();

        // TODO: Amélioration de l'appel EI_EXCEL_REQUESTS NATIVE REQUEST.
        // Mise à jour des états des demandes qui ont plus de 1 jour.
        $sqlUpdate = "UPDATE ei_excel_requests SET state = 1 WHERE created_at <= DATE_ADD(NOW(), INTERVAL -3 MINUTE);";
        // Exécution.
        Doctrine_Manager::connection()->execute($sqlUpdate);

        /** @var EiExcelRequests $requete */
        $requete = $table->getLastUserRequest($this->user->getEiUser()->getRefId(), $this->user->getEiUser()->getUserId());

        if( $requete != null && $requete->getProfil() != null && $requete->getScenario() != null )
        {
            $resultat = array(
                "id" => $requete->getId(),
                "project_id" => $requete->getProjectId(),
                "project_ref" => $requete->getProjectRef(),
                "project_name" => $requete->getScenario()->getEiProjet()->getName(),
                "profile_id" => $requete->getProfileId(),
                "profile_ref" => $requete->getProfileRef(),
                "profile_name" => $requete->getProfil()->getName(),
                "scenario_id" => $requete->getScenario()->getId(),
                "scenario_name" => $requete->getScenario()->getNomScenario(),
                "scenario_url" => $this->generateUrl("projet_new_eiversion", array(
                        "action" => "editVersionWithoutId",
                        "project_id" => $requete->getProjectId(),
                        "project_ref" => $requete->getProjectRef(),
                        "profile_name" => $requete->getProfil()->getName(),
                        "profile_id" => $requete->getProfileId(),
                        "profile_ref" => $requete->getProfileRef(),
                        "ei_scenario_id" => $requete->getScenario()->getId()
                    ), true),
                "ei_data_set_id" => $requete->getEiDataSetId(),
                "ei_data_set_name" => $requete->getEiDataSetId() != "" ? $requete->getEiDataSet()->getName():"",
                "ei_data_set_desc" => $requete->getEiDataSetId() != "" ? $requete->getEiDataSet()->getDescription():"",
                "ei_test_set_id" => $requete->getEiTestSetId(),
                "state" => $requete->getState()
            );

            if( $requete->getEiTestSetId() == "" ){
                $requete->setEiTestSet(null);
            }

            if( $requete->getEiDataSetId() == "" ){
                $requete->setEiDataSet(null);
                $requete->setEiDataSetTemplate(null);
            }

            $requete->setState(true);
            $requete->save();
        }

        return $this->renderText(json_encode($resultat));
    }
}
