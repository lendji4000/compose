<?php

/**
 * eidatasetstructure actions.
 *
 * Contrôleur permettant de gérer la structure des jeux de données.
 *
 * @package    kalifastRobot
 * @subpackage eidatasetstructure
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eidatasetstructureActions extends sfActionsKalifast
{
    //******************************************************************************************************************
    //***************          INTEROPERABILITE ET OPERATIONS SUR LA STRUCTURE
    //******************************************************************************************************************

    /**
     * @param sfWebRequest $request
     */
    public function executeDownloadXSD(sfWebRequest $request)
    {
        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS PROJET/PROFIL.

        $this->checkUpParameters($request);

        // Récupération & vérification du scénario.
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->request->getParameter('ei_scenario_id'));
        $this->forward404Unless($this->ei_scenario);

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

    /**
     * @param sfWebRequest $request
     */
    public function executeDownloadXMLSample(sfWebRequest $request)
    {
        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS PROJET/PROFIL.

        $this->checkUpParameters($request);

        // Récupération & vérification du scénario.
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->request->getParameter('ei_scenario_id'));
        $this->forward404Unless($this->ei_scenario);

        /** @var EiDataSetStructure $ei_root_node */
        $ei_root_node = Doctrine_Core::getTable("EiDataSetStructure")->getRoot($this->ei_scenario->getId());
        $this->forward404Unless($ei_root_node);

        /** @var DOMDocument $xml */
        $xml = EiDataSetStructure::createXML($ei_root_node);
        $xml->formatOutput = true;
        $xml = $xml->saveXML();

        $response = $this->getResponse();

        $response->setContentType('text/xml');
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="' . $this->ei_scenario->getNomScenario() . '-XMLSample.xml');
        $response->setContent($xml);

        return sfView::NONE;
    }

    //******************************************************************************************************************
    //***************          GESTION DE LA STRUCTURE
    //******************************************************************************************************************

    /**
     * @param sfWebRequest $request
     */
    private function prepareNodeEdit(sfWebRequest $request)
    {
        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS PROJET/PROFIL.

        $this->checkUpParameters($request);

        //**************************************************************************************************************
        // RECUPERATION ET VERIFICATIONS DES OBJETS : SCENARIO, NOEUD ROOT STRUCTURE,
        // CHEMIN DEPUIS SCENARIO.

        // Récupération & vérification du scénario.
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->request->getParameter('ei_scenario_id'));
        $this->forward404Unless($this->ei_scenario);
        // Récupération & vérification du noeud root de la structure du jeu de données.
        $this->ei_root_node_id = $request->getParameter("ei_root_node_id");

        // Si le paramètre ei_root_node_id a bien été renseigné, on le recherche en base, sinon, on choisit le root du scénario.
        if( $this->ei_root_node_id != "" ){
            $this->ei_root_node = Doctrine_Core::getTable("EiNodeDataSet")->find($this->ei_root_node_id);
        }
        else{
            $this->ei_root_node = Doctrine_Core::getTable("EiDataSetStructure")->getRoot($this->ei_scenario->getId());
        }

        $this->forward404Unless($this->ei_root_node);

        // Récupération du chemin du scénario.
        $this->chemin = $this->ei_root_node->getPathTo();
        // Récupération des feuilles du noeud.
        $this->ei_node_leaves = $this->ei_root_node->getLeaves();
        $this->ei_node_leaves_form = array();

        foreach( $this->ei_node_leaves as $leaf ){
            $this->ei_node_leaves_form[] = new EiLeafDataSetForm($leaf);
        }

        // Récupération des noeuds fils.
        $this->ei_node_children = $this->ei_root_node->getChildren();

        //**************************************************************************************************************
        // CREATION DU FORMULAIRE DU NOEUD.

        $this->ei_node_root_form = new EiNodeDataSetForm($this->ei_root_node);
    }

    /**
     * Action retournant le formulaire d'édition de la structure du jeu de données.
     *
     * @param sfWebRequest $request
     */
    public function executeEdit(sfWebRequest $request)
    {
        // Préparation des éléments relatifs à la vue.
        $this->prepareNodeEdit($request);

        //**************************************************************************************************************
        // RECUPERATION ET VERIFICATIONS DES OBJETS : VERSIONS DU SCENARIO.

        // Récupération & vérification des versions du scénario.
        $this->ei_versions = Doctrine_Core::getTable('EiVersion')->findByEiScenarioId($this->ei_scenario->getId());
        $this->forward404Unless($this->ei_versions);

        // Réupération de la structure du scénario.
        $structures = Doctrine_Core::getTable("EiDataSetStructure")->getTreeArrayForITree($this->ei_scenario->getId());

        // Création du TreeViewer.
        $treeViewer = new TreeViewer("EiDataSetStructure");
        $treeViewer->import($structures);

        $this->treeDisplay = new TreeView($treeViewer, new ModeEditTreeStrategy(), array(
            "id" => "datasetstructure_tree",
            "formats" => array(
                "node" => EiNodeDataSet::getFormNameFormat(),
                "leaf" => EiLeafDataSet::getFormNameFormat()
            ),
            "actions" => array(
                "rename" => array(
                    "route" => "eidatasetstructure_rename_node",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $this->ei_scenario->getId(),
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_node_id' => 'ei_node_id'
                    ),
                    "target" => "ei_node_id"
                ),
                "new" => array(
                    "route" => "eidatasetstructure_create_node",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $this->ei_scenario->getId(),
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_node_parent_id' => 'ei_node_parent_id'
                    ),
                    "target" => "ei_node_parent_id"
                ),
                "remove" => array(
                    "route" => "eidatasetstructure_remove_node",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $this->ei_scenario->getId(),
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_node_id' => 'ei_node_id'
                    ),
                    "target" => "ei_node_id"
                ),
                "dragndrop" => array(
                    "route" => "eidatasetstructure_move_node",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $this->ei_scenario->getId(),
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref
                    )
                )
            )
        ));
    }

    //******************************************************************************************************************
    //***************          GESTION DE LA STRUCTURE
    //******************************************************************************************************************

    /**
     * @param sfWebRequest $request
     * @return string
     */
    public function executeCreateNode(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Déclaration de la table des data sets structure.
        /** @var EiDataSetStructureTable $tableDs */
        $tableDs = Doctrine_Core::getTable("EiDataSetStructure");

        // Vérification des paramètres.
        $this->checkUpParameters($request);

        // Récupération du nouveau nom, du type, du parent, de la position.
        $type = $request->getPostParameter("type");
        $position = $request->getPostParameter("positionReelle");
        /** @var EiNodeDataSet $parent */
        $parent = $tableDs->find($request->getParameter("ei_node_parent_id"));

        $this->forward404Unless($parent);

        if( $position != 0 )
        {
            $children = $type == TreeView::$TYPE_NODE ? $parent->getChildren():$parent->getLeaves();
            $prev = null;
            $next = null;
            $cpt = 0;
            $done = false;
            $realType = $type == TreeView::$TYPE_NODE ? EiDataSetStructure::$TYPE_NODE:EiDataSetStructure::$TYPE_LEAF;

            if( $children->count() > 0 ){
                /** @var EiDataSetStructure $child */
                foreach( $children as $child ){

                    if( $child->getType() == $realType && $cpt == $position && !$done ){
                        $prev = $child;
                        $done = 1;
                    }
                    elseif( $child->getType() == $realType && ++$cpt == $position && !$done ){
                        $next = $child;
                        $done = 1;
                    }
                }
            }
            else{
                $position = 0;
            }
        }

        sfForm::disableCSRFProtection();

        if( $type == TreeView::$TYPE_NODE ){
            $this->ei_node = new EiNodeDataSet();
            $this->ei_node->setEiDataSetStructureParent($parent);

            $this->form = new EiNodeDataSetForm($this->ei_node);
            $JSONResponse = $this->processFormNode($request, $this->form);
            $type = 'Node';
        }
        elseif( $type == TreeView::$TYPE_LEAF ){
            $this->ei_leaf = new EiLeafDataSet();
            $this->ei_leaf->setEiDataSetStructureParent($parent);

            $this->form = new EiLeafDataSetForm($this->ei_leaf);
            $JSONResponse = $this->processFormLeaf($request, $this->form);
            $type = 'Attribute';
        }

        if ( !$JSONResponse ){

            if( $position == 0 ){
                $this->form->getObject()->getNode()->insertAsFirstChildOf($parent);
            }
            else{
                if( $next == null && $prev != null ){
                    $this->form->getObject()->getNode()->insertAsPrevSiblingOf($prev);
                }
                elseif( $next != null && $prev == null ){
                    $this->form->getObject()->getNode()->insertAsNextSiblingOf($next);
                }
                else{
                    $this->form->getObject()->getNode()->insertAsLastChildOf($parent);
                }
            }

            // Mise à jour des jeux de données existants.
            $tableDs->completeAllDataSets($this->form->getObject()->getEiScenarioId());


            $JSONResponse = $this->createJSONResponse('saved', 'ok', $type);
            $JSONResponse["id"] = $this->form->getObject()->getId();
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return string
     */
    public function executeMoveNode(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Vérification des paramètres.
        $this->checkUpParameters($request);

        $noeuds = json_decode($request->getContent());
        $noeudsModifies = array();
        $tableStructure = Doctrine_Core::getTable("EiDataSetStructure");

        try{

            /** @var EiDataSetStructure $oNoeud */
            foreach( $noeuds->noeuds as $ind => $noeud ){
                $oNoeud = $tableStructure->find($noeud->id);
                $parent = $tableStructure->find($noeud->parent);

                $noeudsModifies[] = $oNoeud->getName();
                $oNoeud->getNode()->moveAsFirstChildOf($parent);
            }

            /** @var EiDataSetStructure $oNoeud */
            foreach( $noeuds->noeuds as $ind => $noeud ){
                $oNoeud = $tableStructure->find($noeud->id);
                $parent = $tableStructure->find($noeud->parent);

                if( $noeud->prev != null ){
                    $prev = $tableStructure->find($noeud->prev);
                    $oNoeud->getNode()->moveAsNextSiblingOf($prev);
                }
                elseif( $noeud->prev == null && $noeud->suiv != null ){
                    $suiv = $tableStructure->find($noeud->suiv);
                    $oNoeud->getNode()->moveAsPrevSiblingOf($suiv);
                }

                $oNoeud->setEiDataSetStructureParent($parent);
                $oNoeud->save();
            }

            if( count($noeudsModifies) > 1 ){
                $JSONResponse = $this->createJSONResponse('saved', 'ok', null, 'Nodes '.implode(", ",$noeudsModifies).' has been moved successfully.');
            }
            else{
                $JSONResponse = $this->createJSONResponse('saved', 'ok', null, 'Node '.implode(", ",$noeudsModifies).' has been moved successfully.');
            }
        }
        catch( \Exception $exc ){
            $JSONResponse = $this->createJSONResponse('saved', 'ok', null, 'Unable to move node(s).');
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     */
    public function executeRenameNode(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Vérification des paramètres.
        $this->checkUpParameters($request);

        sfForm::disableCSRFProtection();

        $JSONResponse = array();
        $this->ei_node = Doctrine_Core::getTable("EiDataSetStructure")->find($request->getParameter("ei_node_id"));

        // On instancie le formulaire en fonction du type de noeud.
        if($this->ei_node instanceof EiNodeDataSet){
            $this->form = new EiNodeDataSetForm($this->ei_node);
            $JSONResponse = $this->processFormNode($request, $this->form);
            $type = 'Node';
        }
        elseif($this->ei_node instanceof EiLeafDataSet){
            $this->ei_leaf = $this->ei_node;
            $this->form = new EiLeafDataSetForm($this->ei_node);
            $JSONResponse = $this->processFormLeaf($request, $this->form);
            $type = 'Attribute';
        }

        if ( !$JSONResponse ){
            $JSONResponse = $this->createJSONResponse('saved', 'ok', $type);
        }

        return $this->renderText(json_encode($JSONResponse));
    }

    /**
     * @param sfWebRequest $request
     * @return string
     */
    public function executeRemoveNode(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Vérification des paramètres.
        $this->checkUpParameters($request);

        $this->ei_node = Doctrine_Core::getTable("EiDataSetStructure")->find($request->getParameter("ei_node_id"));

        $type = ($this->ei_node instanceof EiNodeDataSet) ? 'Node':'Attribute';

        if ($this->ei_node == null) {

            $JSONResponse['status'] = "error";
            $JSONResponse['message'] = $type." named ".$this->ei_node->getName()." not found.";

        } else {

            $this->ei_node->getNode()->delete();

            $this->getResponse()->setContentType('application/json');

            $JSONResponse = $this->createJSONResponse('deleted', 'ok');
        }

        return $this->renderText(json_encode($JSONResponse));

    }

    //******************************************************************************************************************
    //***************          GESTION DES NOEUDS
    //******************************************************************************************************************

    /**
     * @param sfWebRequest $request
     * @param sfForm $form
     * @return bool
     */
    protected function processFormNode(sfWebRequest $request, sfForm $form)
    {
        $this->getResponse()->setContentType('application/json');

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid())
        {
            $this->ei_node = $form->save();


            return false;
        }
        else {
            $JSONResponse['status'] = "error";
            $errors  = $form->getErrorSchema()->getErrors();

            if( isset($errors["name"]) ){
                $nomErreur = $errors["name"];
            }
            else{
                $nomErreur = "";
            }

            $JSONResponse = $this->createJSONResponse("created", "error", "Node", "Unable to save the node. ". $nomErreur);
        }

        return $JSONResponse;
    }

    //******************************************************************************************************************
    //***************          GESTION DES FEUILLES
    //******************************************************************************************************************

    /**
     * @param sfWebRequest $request
     * @param sfForm $form
     */
    private function processFormLeaf(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $new = $this->ei_leaf->isNew();

            // On sauvegarde le formulaire.
            $this->ei_leaf = $form->save();

            if( $new && $this->ei_node != null ){
                // Puis on place le noeud dans l'arbre.
                $this->ei_leaf->getNode()->insertAsFirstChildOf($this->ei_node);
            }

            return false;
        }
        else{
            $errors  = $form->getErrorSchema()->getErrors();

            if( isset($errors["name"]) ){
                $nomErreur = $errors["name"];
            }
            else{
                $nomErreur = "";
            }

            return $this->createJSONResponse("created", "error", "Leaf", "Unable to save the node. ". $nomErreur);
        }
    }

    //******************************************************************************************************************
    //***************          GESTION COMMUNE / UTILITAIRES
    //******************************************************************************************************************

    /**
     * Méthode permettant de gérer la vérification des paramètres.
     *
     * @param sfWebRequest $request
     */
    private function checkUpParameters(sfWebRequest $request)
    {
        //**************************************************************************************************************
        // VERIFICATION DES INFORMATIONS PROJET/PROFIL.

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);
    }

    /**
     * Format les réponses JSON de succès.
     *
     * @param type $action
     * @param type $status
     * @return string
     */
    private function createJSONResponse($action, $status, $type = "Node", $message = "") {

        if( $type == "Node" ){
            /** @var EiNodeDataSet $objet */
            $objet = $this->ei_node;
        }
        elseif( $type == "Leaf" || $type == "Attribute" ){
            /** @var EiLeafDataSet $objet */
            $objet = $this->ei_leaf;
        }
        else{
            $objet = new EiNodeDataSet();
        }

        $JSONResponse['status'] = $status;
        $JSONResponse['success'] = $status == "error" ? false:true;
        $JSONResponse['message'] = $message == "" ? $type . " " . $objet->getName() . " has been $action successfully.":$message;

        return $JSONResponse;
    }
}
?>