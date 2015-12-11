<?php

/**
 * Class deleteDataSetsDirAction
 */
class deleteDataSetsDirAction extends sfActions
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

        $response = array(
            "error" => "An error occured when we try to delete your directory."
        );

        try{
            $this->getUser()->signIn($this->user, true);

            /** @var EiNodeTable $tableEiNode */
            $tableEiNode = Doctrine_Core::getTable("EiNode");

            // Récupération des données.
            $nodeId = $request->getPostParameter("node_id");

            if( $nodeId == null ){
                $response["error"] = "You have to select a directory";
            }
            else{
                /** @var EiNode $node Récupération du noeud à modifier. */
                $node = $tableEiNode->find($nodeId);

                if( $node != null && $node->getId() != "" && $node->getEiScenarioNode()->getObjId() == $this->scenario->getId() ){
                    try{
                        if( $node->getNodes(true, true, true)->count() == 0 ){

                            unset($response["error"]);
                            $node->delete();

                            $response["status"] = "OK";
                        }
                        else{
                            $response["error"] = "We are not able to accommodate your request because the directory is not empty.";
                        }
                    }
                    catch(Exception $exc){
                        $response["error"] = "An error occured when we tried to delete directory : " . $exc->getMessage();
                    }
                }
                else{
                    $response["error"] = "We are not able to accommodate your request.";
                }
            }

        }
        catch( Exception $e ){
            $response = array();
        }

        return $this->renderText(json_encode($response));
    }
} 