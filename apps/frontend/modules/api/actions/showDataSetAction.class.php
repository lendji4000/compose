<?php

/**
 * Class ShowDataSetAction
 */
class ShowDataSetAction extends sfActions
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
        $this->logger = sfContext::getInstance()->getLogger();
        // On détermine le type de contenu retourné.
        $this->getResponse()->setContentType('application/xml');

        $this->getUser()->signIn($this->user, true);

        /** @var EiProfilTable $tableProfil */
        $tableProfil = Doctrine_Core::getTable("EiProfil");

        /* @var EiDataSet $data_set */
        $data_set = $this->getRoute()->getObject();
        /** @var EiScenario $scenario */
        $scenario = Doctrine_Core::getTable("EiDataSet")->getDataSetScenario($data_set);
        /** @var EiProfil $profil */
        $profil = $tableProfil->findOneByProfileRefAndProfileId($request->getParameter("profil_ref"), $request->getParameter("profil_id"));

        $request->setParameter("ei_test_set_id", $data_set->getId());
        $request->setParameter("project_ref", $scenario->getProjectRef());
        $request->setParameter("project_id", $scenario->getProjectId());
        $request->setParameter("profile_ref", $profil->getProfileRef());
        $request->setParameter("profile_id",  $profil->getProfileId());
        $request->setParameter("profile_name", $profil->getName());

        $this->logger->info("----------------------------------------------------------");
        $chronometre = new Chronometre();
        $chronometre->lancerChrono("PERFORMANCE RECUPERATION JDD");
        $content = $this->getController()->getPresentationFor("eidataset", "download");
        // Fin mesure
        $chronometre->arreterEtAfficherChrono();
        $this->logger->info("----------------------------------------------------------");

        return $this->renderText($content);
    }
}
