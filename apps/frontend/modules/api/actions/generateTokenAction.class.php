<?php

/**
 * Class GenerateTokenAction
 */
class GenerateTokenAction extends sfActions
{
    /**
     * Action permettant de générer un nouveau token pour l'utilisateur courant.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $request->setParameter("ei_user", $this->user);

        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');

        $token = $table::getInstance()->generateToken($this->getUser()->getGuardUser()->getEiUser());

        return $this->renderText(json_encode(array("token" => $token)));
    }
}
