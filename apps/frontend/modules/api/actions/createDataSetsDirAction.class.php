<?php

/**
 * Class createDataSetsDirAction
 */
class createDataSetsDirAction extends sfActions
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
            "error" => "An error occured when we try to create your directory."
        );

        try{
            $this->getUser()->signIn($this->user, true);

            /** @var EiNodeTable $tableEiNode */
            $tableEiNode = Doctrine_Core::getTable("EiNode");

            // Récupération des données.
            $nom = $request->getPostParameter("name");
            $parentNodeId = $request->getPostParameter("parent_node_id");

            if( $nom == null || $parentNodeId == null ){
                $response["error"] = "You have to select a parent directory and type a valid directory name";
            }
            else{
                /** @var EiNode $parentNode Récupération du noeud parent. */
                $parentNode = $tableEiNode->find($parentNodeId);

                if( $parentNode != null && $parentNode->getId() != "" && $parentNode->getEiScenarioNode()->getObjId() == $this->scenario->getId() ){
                    try{
                        $folder = new EiNode();
                        $folder->setName($nom);
                        $folder->setType(EiNode::$TYPE_DATASET_FOLDER);
                        $folder->setProjectId($parentNode->getProjectId());
                        $folder->setProjectRef($parentNode->getProjectRef());
                        $folder->setRootId($parentNode->getId());

                        $folder->save();

                        unset($response["error"]);

                        $response["id"] = $folder->getId();
                    }
                    catch(Exception $exc){
                        $response["error"] = "An error occured when we tried to create directory : " . $exc->getMessage();
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