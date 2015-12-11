<?php

/**
 * Class addCampaignToExecutionStackAction
 */
class addCampaignToExecutionStackAction extends sfActions
{
    /** @var sfLogger */
    private $logger;

    /**
     * @var EiCampaign
     */
    private $campaign;

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
        $this->campaign = Doctrine_Core::getTable('EiCampaign')->findOneById($this->getRequest()->getParameter("ei_campaign_id"));
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

        /** @var EiCampaignGraphTable $tableCampaignGraph */
        $tableCampaignGraph = Doctrine_Core::getTable("EiCampaignGraph");
        $found = false;

        $profileRef = $request->getParameter("profile_ref");
        $profileId = $request->getParameter("profile_id");
        $projectRef = $request->getParameter("project_ref");
        $projectId = $request->getParameter("project_id");
        $startPos = $request->getParameter("start");
        $endPos = $request->getParameter("end");
        $deviceId = $request->getParameter("device_id");
        $driverId = $request->getParameter("driver_id");
        $browserId = $request->getParameter("browser_id");
        $date = $request->getParameter("date");
        $expectedDate = str_replace("*", " ", $date);
        $expectedDate = str_replace("_", ":", $expectedDate);

        $response = array(
            "error" => "An error occured when we try to add campaign into execution stack."
        );

        try{
            $this->getUser()->signIn($this->user, true);

            $execution = new EiExecutionStack();
            $execution->setEiCampaign($this->campaign);
            $execution->setSfGuardUser($this->user);
            $execution->setProfileRef($profileRef);
            $execution->setProfileId($profileId);
            $execution->setProjectRef($projectRef);
            $execution->setProjectId($projectId);

            if( $startPos != null && $endPos != null ){
                /** @var EiCampaignGraph $start */
                $start = $tableCampaignGraph->find($startPos);
                /** @var EiCampaignGraph $end */
                $end = $tableCampaignGraph->find($endPos);


                if( $start != null && $start->getId() != "" && $end != null && $end->getId() != "" ){
                    $execution->setStartPos($start->getId());
                    $execution->setEndPos($end->getId());

                    $found = true;
                }
            }

            if( !$found ){
                /** @var EiCampaignGraph $start */
                $start = $tableCampaignGraph->getFirstStep($this->campaign->getId());
                /** @var EiCampaignGraph $end */
                $end = $tableCampaignGraph->getLastStep($this->campaign->getId());

                $execution->setStartPos($start->getId());
                $execution->setEndPos($end->getId());
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