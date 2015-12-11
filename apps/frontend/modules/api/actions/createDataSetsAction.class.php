<?php

/**
 * api actions.
 *
 * @package    kalifastRobot
 * @subpackage api
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateDataSetsAction extends sfActions
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
     * Action permettant de créer un data set.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   DEBUT SAUVEGARDE DATA SET");

        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $this->getUser()->signIn($this->user, true);

        // On récupère le noeud parent du scénario.
        $noeud_id = $this->scenario->getEiNode()->getId();
        $noeud_parent = Doctrine_Core::getTable("EiNode")->findOneByRootIdAndType($noeud_id, "EiDataSetFolder");
        $noeud_parent_id = $noeud_parent->getId();

        // Récupération du nom & description.
        $nom = $request->getPostParameter("name");
        $desc = $request->getPostParameter("description");
        // On récupère le data set source (si enregistrement).
        $dataSetSourceId = $request->getPostParameter("jddSource");
        // On récupère le data set template source (si enregistrement).
        $dataSetTemplateSourceId = $request->getPostParameter("jddTemplateSource");
        // On récupère le dossier où enregistrer le JDD.
        $dataSetDirId = $request->getPostParameter("jddDir");

        // On décode le fichier.
        $request->setParameter("file", str_replace("%3E", ">", str_replace("%3C", "<", $request->getParameter("file"))));

        // LOGS
        $this->logger->info("-- NOM : " . $nom);
        $this->logger->info("-- DESCRIPTION : " . $desc);
        $this->logger->info("-- JDD SOURCE ID : " . $dataSetSourceId);
        $this->logger->info("-- JDD TEMPLATE SOURCE ID : " . $dataSetTemplateSourceId);
        $this->logger->info("-- NOEUD ID PARENT : " . $noeud_parent_id);
        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   FIN SAUVEGARDE DATA SET");
        $this->logger->info("----------------------------------------------------------");

        $request->setParameter("ei_user", $this->user->getEiUser());
        $request->setParameter("name", $nom);
        $request->setParameter("description", $desc);
        $request->setParameter("ei_node_parent_id", $noeud_parent_id);

        if( $dataSetSourceId != null && $dataSetSourceId != -1 ){
            $request->setParameter("dataSetSource", $dataSetSourceId);
        }

        if( $dataSetTemplateSourceId != null && $dataSetTemplateSourceId != -1 ){
            $request->setParameter("dataSetTemplateSource", $dataSetTemplateSourceId);
        }

        if( $dataSetDirId != null && $dataSetDirId != -1 ){
            $oJddDir = Doctrine_Core::getTable("EiNode")->find($dataSetDirId);

            if( $oJddDir != null && $oJddDir->getObjId() != "" ){
                $request->setParameter("dataSetDir", $dataSetDirId);
                $request->setParameter("ei_node_parent_id", $dataSetDirId);
            }
        }

        $this->logger->info("----------------------------------------------------------");
        $chronometre = new Chronometre();
        $chronometre->lancerChrono("PERFORMANCE CREATION JDD");
        try{
            $content = $this->getController()->getPresentationFor("eidataset", "lightCreateFromXml");
        }
        catch( Exception $exc ){
            $this->logger->info("--- ERREUR : ".$exc->getMessage()." ---");
        }
        // Fin mesure
        $chronometre->arreterEtAfficherChrono();
        $this->logger->info("----------------------------------------------------------");

        return $this->renderText($content);
    }
}
