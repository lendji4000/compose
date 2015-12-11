<?php

/**
 * Class ShowTestSetStatusAction
 */
class ShowTestSetStatusAction extends sfActions
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
     * @param sfRequest $request
     * @return string
     */
    public function execute($request)
    {
        $this->setLayout(false);
        // On détermine le type de contenu retourné.
        $this->getResponse()->setContentType('application/json');

        /* @var EiTestSet $test_set */
        $test_set = $this->getRoute()->getObject();

        $this->forward404If($test_set == null || is_bool($test_set) || ($test_set != null && $test_set->getId() == ""), "You are not allowed to access this page.");

        /** @var EiProfil $profile */
        $profile = $test_set->getProfile();

        return $this->renderText(json_encode(array(
            "id" => $test_set->getId(),
            "status" => $test_set->getStatus(),
            "url" => $this->generateUrl("eitestset_oracle", array(
                "project_id" => $profile->getProjectId(),
                "project_ref" => $profile->getProjectRef(),
                "profile_id" => $profile->getProfileId(),
                "profile_ref" => $profile->getProfileRef(),
                "profile_name" => $profile->getName(),
                "ei_scenario_id" => $test_set->getEiScenarioId(),
                "ei_test_set_id" => $test_set->getId()
            ), true)
        )));
    }
}
