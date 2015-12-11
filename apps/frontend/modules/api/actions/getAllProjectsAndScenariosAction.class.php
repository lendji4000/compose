<?php

/**
 * Class GetAllProjectsAndScenariosAction
 */
class GetAllProjectsAndScenariosAction extends sfActions
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
            $folders = array();

            /** @var EiProjetTable $tableProjet */
            $tableProjet = Doctrine_Core::getTable("EiProjet");

            $projetsListe = $tableProjet->findUserProjet($this->user->getGuardId());

            if( $projetsListe !== null ){
                /** @var EiProjet $projet */
                foreach( $projetsListe as $projet ){

                    if ($projet != null && $projet->needsReload($this->user->getGuardUser()->getUsername())){
                        $xml = $projet->downloadKalFonctions($request);
                        if($xml != null)
                            $projet->transactionToLoadObjectsOfProject($xml);
                    }

                    /** @var EiNode $oRoot */
                    $oRoot = $projet->getRootFolder();//Récupération du dossier root
                    /** @var EiNode[] $oChilds */
                    $oChilds = $oRoot->getStructure();

                    // Ajout du lien vers le scénario.
                    $oChilds = $this->throwStructureAndAddUrlToScenario($oChilds, $projet->getDefaultProfil());

                    $folders[] = $oChilds;
                }
            }
        }
        catch( Exception $e ){
            $folders = array();
        }

        return $this->renderText(json_encode( array("tree" => $folders) ));
    }

    /**
     * @param $oChilds
     */
    private function throwStructureAndAddUrlToScenario(&$oChilds, EiProfil $profil){
        if( is_array($oChilds) && is_array($oChilds["childs"]) ){
            foreach( $oChilds["childs"] as $ind => $child ){
                if( isset($child["root"]["type"]) && $child["root"]["type"] == EiNode::$TYPE_FOLDER ){
                    $oChilds["childs"][$ind] = $this->throwStructureAndAddUrlToScenario($child, $profil);
                }
                elseif( isset($child["root"]["type"]) && $child["root"]["type"] == EiNode::$TYPE_SCENARIO ){
                    $oChilds["childs"][$ind]["root"]["url"] = $this->generateUrl("projet_new_eiversion", array(
                        "action" => "editVersionWithoutId",
                        "project_id" => $child["root"]["project_id"],
                        "project_ref" => $child["root"]["project_ref"],
                        "profile_name" => $profil->getName(),
                        "profile_id" => $profil->getProfileId(),
                        "profile_ref" => $profil->getProfileRef(),
                        "ei_scenario_id" => $child["root"]["obj_id"]
                    ), true);
                }
            }
        }

        return $oChilds;
    }
}
