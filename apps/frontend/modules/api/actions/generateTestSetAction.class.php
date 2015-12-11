<?php

/**
 * Class GenerateTestSetAction
 */
class GenerateTestSetAction extends sfActions
{
    /**
     * @var EiScenario
     */
    private $scenario;

    /**
     * @var SfGuardUser
     */
    private $user;

    /**
     *
     */
    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );
        $this->user = $this->user->getGuardUser();
        $this->scenario = Doctrine_Core::getTable('EiScenario')->findOneById($this->getRequest()->getParameter("ei_scenario_id"));
    }

    /**
     * @param sfRequest $request
     * @return sfView|string
     */
    public function execute($request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $this->getUser()->signIn($this->user, true);

        $content = $this->getController()->getPresentationFor("eitestset", $this->getActionName());

        return $this->renderText($content);
    }
}
