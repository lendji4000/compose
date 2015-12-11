<?php

/**
 * Class addScenarioToExecutionStackAction
 */
class addScenarioToExecutionStackAction extends sfActions
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
        $this->getResponse()->setContentType('application/json');
        $this->setLayout(false);

        $jddId = $request->getParameter("jdd_id");
        $profileRef = $request->getParameter("profile_ref");
        $profileId = $request->getParameter("profile_id");
        $projectRef = $request->getParameter("project_ref");
        $projectId = $request->getParameter("project_id");
        $deviceId = $request->getParameter("device_id");
        $driverId = $request->getParameter("driver_id");
        $browserId = $request->getParameter("browser_id");
        $date = $request->getParameter("date");
        $expectedDate = str_replace("*", " ", $date);
        $expectedDate = str_replace("_", ":", $expectedDate);

        $response = array(
            "error" => "An error occured when we try to add scenario into execution stack."
        );

        try{
            $this->getUser()->signIn($this->user, true);

            $execution = new EiExecutionStack();
            $execution->setEiScenario($this->scenario);
            $execution->setSfGuardUser($this->user);
            $execution->setProfileRef($profileRef);
            $execution->setProfileId($profileId);
            $execution->setProjectRef($projectRef);
            $execution->setProjectId($projectId);

            if( $jddId != null && $jddId != 0 )
            {
                $execution->setEiDataSetId($jddId);
            }
            
            if( $deviceId != null && $deviceId != 'null' )
            {
                $execution->setDeviceId($deviceId);
            }
            
            $execution->setDriverId($driverId);
            $execution->setBrowserId($browserId);
            $execution->setExpectedDate($expectedDate);

            $execution->save();

            unset($response["error"]);

            $response["success"] = true;
            $response["id"] = $execution->getId();

        }
        catch( Exception $e ){
            $response = array();
        }

        return $this->renderText(json_encode($response));
    }
} 