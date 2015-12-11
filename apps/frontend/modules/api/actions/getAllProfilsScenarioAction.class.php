<?php

/**
 * Class GetAllProfilsScenarioAction
 */
class GetAllProfilsScenarioAction extends sfActions
{
    /**
     * @var EiScenario
     */
    private $scenario;

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
        $this->scenario = $this->getRoute()->getObject();
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
            $profils = array();

            /** @var EiProfilTable $tableProfilScenario */
            $tableProfilScenario = Doctrine_Core::getTable("EiProfil");

            $profilsListe = $tableProfilScenario->getProfilsScenarioForUser($this->user, $this->scenario->getId());

            if( $profilsListe !== null ){
                /** @var EiProfil $profil */
                foreach( $profilsListe as $profil ){
                    $profils[] = array(
                        "ref" => $profil->getProfileRef(),
                        "id" => $profil->getProfileId(),
                        "nom" => $profil->getName()
                    );
                }
            }
        }
        catch( Exception $e ){
            $profils = array();
        }

        return $this->renderText(json_encode( array("environnements" => $profils) ));
    }
}
