<?php

/**
 * Class GetScenariosAction
 */
class GetScenariosAction extends sfActions
{

    /**
     * @var EiUser
     */
    private $user;

    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );
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

        try{
            // Création du tableau de résultats.
            $scenarios = array();

            /** @var EiScenarioTable $tableScenario */
            $tableScenario = Doctrine_Core::getTable("EiScenario");

            $scenariosListe = $tableScenario->getScenariosFromUser($this->user);

            if( $scenariosListe !== null ){
                /** @var EiScenario $scenario */
                foreach( $scenariosListe as $scenario ){
                    $scenarios[] = array(
                        "id" => $scenario->getId(),
                        "nom" => $scenario->getNomScenario()
                    );
                }
            }
        }
        catch( Exception $e ){
            $scenarios = array();
        }

        return $this->renderText(json_encode(array("scenarios" => $scenarios)));
    }
}
