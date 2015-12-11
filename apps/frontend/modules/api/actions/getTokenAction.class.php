<?php

/**
 * Class GetTokenAction
 */
class GetTokenAction extends sfActions
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

        /** @var EiUser $user */
        $user = $this->getUser()->getGuardUser()->getEiUser();
        $token = $user->getTokenApi();

        return $this->renderText(json_encode(array("token" => $token)));
    }
}
