<?php

/**
 * Class getDataSetTreeAction
 */
class getDataSetTreeAction extends sfActions
{
    /** @var sfLogger */
    private $logger;

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
     * Action permettant de récupérer l'arbre des jeux de données.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT RECUPERATION ARBRE DATA SET");

        $this->getResponse()->setContentType('application/json');
        $this->setLayout(false);

        try{
            $this->getUser()->signIn($this->user, true);

            /** @var EiNodeTable $tableEiNode */
            $tableEiNode = Doctrine_Core::getTable("EiNode");

            // Récupération du noeud du scénario.
            $node = $this->scenario->getEiNode();

            // Recherche de la structure des fichiers des jeux de données.
            $rootFolder = Doctrine_Core::getTable('EiNode')->findOneByRootIdAndType($node->getId(), 'EiDataSetFolder');

            // On récupère ensuite la structure brute des dossiers.
            $structureBrute = $tableEiNode->getStructureDataSets($rootFolder);
            $structureBrute["root"]["name"] = "Root";

            // Puis on la retravaille.
//            $structure = $this->getReorderedStructure(array(), $structureBrute);

            $response = json_encode(array(
                "tree" => array(
                    $structureBrute
                )
            ));
        }
        catch( Exception $e ){
            $response = array();
        }

        return $this->renderText($response);
    }

    /**
     * @param $newStr
     * @param $oldStr
     */
    private function getReorderedStructure($newStr, $oldStr){

        $newStr[$oldStr["root"]["id"]] = array(
            "id" => $oldStr["root"]["id"],
            "name" => $oldStr["root"]["name"],
            "childs" => array()
        );

        foreach( $oldStr["childs"] as $child ){
            $newStr[$oldStr["root"]["id"]]["childs"] = $this->getReorderedStructure($newStr[$oldStr["root"]["id"]]["childs"], $child);
        }

        return $newStr;
    }
} 