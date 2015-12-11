<?php

/**
 * Class GetAllProjectProfilesAction
 */
class GetAllProjectProfilesAction extends sfActionsKalifast
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
        // Récupération des informations relatives au projet.
        $this->checkProject($this->getRequest());

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
            $profils = array();

            /** @var EiProfilTable $tableProfils */
            $tableProfils = Doctrine_Core::getTable("EiProfil");

            $profilsListe = $tableProfils->findByProjectIdAndProjectRef($this->ei_project->getProjectId(), $this->ei_project->getRefId());

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
