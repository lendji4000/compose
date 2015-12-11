<?php

/**
 * api actions.
 *
 * @package    kalifastRobot
 * @subpackage api
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ShowScenarioAction extends sfActions
{
    /**
     * @var EiScenario
     */
    private $scenario;

    /**
     * @var SfGuardUser
     */
    private $user;

    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );
        $this->user = $this->user->getGuardUser();
        $this->scenario = $this->getRoute()->getObject();
    }

    /**
     * Action permettant de créer un data set.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->scenario->getId());

        $this->forward404Unless($this->ei_scenario);

        $this->forward404Unless(Doctrine_Core::getTable("EiProjectUser")
            ->getEiProjet($this->ei_scenario->getProjectId(), $this->ei_scenario->getProjectRef(), $this->user->getEiUser()));

        /** @var EiDataSetStructure $ei_root_node */
        $ei_root_node = Doctrine_Core::getTable("EiDataSetStructure")->getRoot($this->ei_scenario->getId());
        $this->forward404Unless($ei_root_node);

        /** @var DOMDocument $xsd */
        $xsd = EiDataSetStructure::createXSD($ei_root_node);
        $xsd->formatOutput = true;
        $xsd = $xsd->saveXML();

        $response = $this->getResponse();

        $response->setContentType('text/xml');
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="' . $this->ei_scenario->getNomScenario() . '-XSD.xsd');
        $response->setContent($xsd);

        return sfView::NONE;
    }
}
