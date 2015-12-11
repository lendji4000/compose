<?php

/**
 * Class ShowOracleAction
 */
class ShowOracleAction extends sfActions
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
        $this->getResponse()->setContentType('application/xml');

        $this->getUser()->signIn($this->user, true);

        /* @var EiTestSet $test_set */
        $test_set = $this->getRoute()->getObject();

        $request->setParameter("ei_test_set_id", $test_set->getId());
        $request->setParameter("project_ref", $test_set->getEiScenario()->getProjectRef());
        $request->setParameter("project_id", $test_set->getEiScenario()->getProjectId());
        $request->setParameter("ei_scenario_id", $test_set->getEiScenario()->getId());

        $content = $this->getController()->getPresentationFor("eitestset", "downloadOracle");

        return $this->renderText($content);
    }
}
