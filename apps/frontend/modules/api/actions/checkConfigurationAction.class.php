<?php

/**
 * Class CheckConfigurationAction
 */
class CheckConfigurationAction extends sfActions
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
    }

    /**
     * Action permettant de créer un data set.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );

        return $this->renderText(json_encode(array("resultat" => "ok")));
    }
}
