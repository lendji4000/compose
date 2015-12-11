<?php

/**
 * eiblockdatasetmapping actions.
 *
 * @package    kalifastRobot
 * @subpackage eiblockdatasetmapping
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiblockdatasetmappingActions extends sfActionsKalifast
{
    /**
     * @param sfWebRequest $request
     */
    public function executeSelectMapping(sfWebRequest $request)
    {
        $this->getContext()->getConfiguration()->loadHelpers(array('Url','I18N','Date', 'Tag','Number','Text', 'Partial') );

        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Vérifications du projet et du profil.
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        //**************************************************************************************************************
        // RECUPERATION ET VERIFICATIONS DES OBJETS : SCENARIO, NOEUD ROOT STRUCTURE,
        // CHEMIN DEPUIS SCENARIO.

        // Récupération & vérification du scénario.
        $this->ei_scenario = Doctrine_Core::getTable('EiScenario')->find($this->request->getParameter('ei_scenario_id'));
        $this->forward404Unless($this->ei_scenario);
        // Récupération & vérification du paramètre du block de la structure du scénario.
        $this->ei_block_param_id = $request->getParameter("ei_block_param_id");

        $this->ei_block_param = Doctrine_Core::getTable("EiBlockParam")->find($this->ei_block_param_id);
        // On récupère la racine du scénario.
        $this->ei_root_node = Doctrine_Core::getTable("EiDataSetStructure")->getRoot($this->ei_scenario->getId());

        $this->forward404Unless($this->ei_block_param);
        $this->forward404Unless($this->ei_root_node);

        /** @var TreeView $treeDisplay */
        $treeDisplay = new TreeView($this->ei_root_node, new ModeSelectTreeStrategy(), array(
            "id" => "datasetstructure_tree_select",
            "formats" => array(
                "node" => EiNodeDataSet::getFormNameFormat(),
                "leaf" => EiLeafDataSet::getFormNameFormat()
            ),
            "actions" => array(
                "select" => array(
                    "route" => "eiblockdatasetmapping_do_select_mapping",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $this->ei_scenario->getId(),
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_block_param_id' => $this->ei_block_param_id
                    )
                )
            )
        ));

        return $this->renderText(json_encode(array(
            "success" => true,
            "html" => $treeDisplay->render()
        )));
    }

    /**
     * Action permettant de persister le choix de mapping entre un paramètre de block et un paramètre de jeu de données.
     * Si un mapping existe déjà, il sera remplacé.
     *
     * @param sfWebRequest $request
     */
    public function executeDoSelectMapping(sfWebRequest $request)
    {
        // Appel AJAX requis.
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->setLayout(sfView::NONE);
        $this->getResponse()->setContentType('application/json');

        // Vérifications du projet et du profil.
        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        $requestContent = json_decode($request->getContent());

        // Récupération du paramètre du block.
        $paramBlockId = $request->getParameter("ei_block_param_id");
        /** @var EiBlockParam $paramBlock */
        $paramBlock = Doctrine_Core::getTable("EiBlockParam")->find($paramBlockId);

        $this->forward404Unless($paramBlock);

        // Récupération du type affecté.
        $typeMapping = $request->getParameter("type");

        $this->forward404Unless($typeMapping != EiBlockDataSetMapping::$TYPE_IN || $typeMapping != EiBlockDataSetMapping::$TYPE_OUT);

        // Récupération du noeud affecté.
        $nodeDataSetId = $requestContent->node;

        if( $nodeDataSetId != null ){
            /** @var EiLeafDataSet $nodeDataSet */
            $nodeDataSet = Doctrine_Core::getTable("EiLeafDataSet")->find($nodeDataSetId);

            $this->forward404Unless($nodeDataSet);

            // Vérification si un ancien mapping existe.
            /** @var EiBlockDataSetMapping $mapping */
            if( ($mapping = $paramBlock->getMapping($typeMapping)) ){
                $mapping->setEiDatasetStructureId($nodeDataSetId);

                $mapping->save();
            }
            else{
                // Création du Mapping.
                $mapping = new EiBlockDataSetMapping();
                $mapping->setEiDatasetStructureId($nodeDataSetId);
                $mapping->setEiVersionStructureId($paramBlockId);
                $mapping->setType($typeMapping);

                $mappingCollection = $paramBlock->getEiVersionStructureDataSetMapping();
                $mappingCollection->add($mapping);

                $paramBlock->setEiVersionStructureDataSetMapping($mappingCollection);
                $paramBlock->save();
            }

            $message = "Mapping between block parameter '".$paramBlock->getName()."' and data set attribute '".$nodeDataSet->getName()."' has been successfuly saved.";
            $path = $nodeDataSet->getPath();
        }
        else{
            /** @var EiBlockDataSetMapping $mapping */
            $mapping = $paramBlock->getMapping($typeMapping);
            $oldMapping = $mapping->getEiDataSetStructureMapping()->getName();
            $mapping->delete();

            $message = "Mapping between block parameter '".$paramBlock->getName()."' and data set attribute '".$oldMapping."' has been successfuly removed.";
            $path = "";
        }

        return $this->renderText(json_encode(array(
            "success" => true,
            "message" => $message,
            "path" => $path
        )));
    }
}
