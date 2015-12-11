<?php

/**
 * Class BlockComponents
 */
class BlockComponents extends sfComponents
{
    public function executeSelectorNodeForm(sfWebRequest $request)
    {
        $versionId = $request->getParameter("ei_version_id") ? $request->getParameter("ei_version_id"):$this->ei_version_id;
        /** @var EiVersion $version */
        $version = Doctrine_Core::getTable("EiVersion")->find($versionId);
        $tree = null;
        $selected = null;

        if( $request->getMethod() === "POST" ){
            $blockForeach = $request->getPostParameter("ei_block_foreach");

            if( $blockForeach != null && isset($blockForeach["Iterator"]["ei_dataset_structure_id"]) ){
                $selected = $blockForeach["Iterator"]["ei_dataset_structure_id"];
            }
        }
        elseif( isset($this->block) && $this->block instanceof EiBlockForeach ){
            $selected = $this->block->getIteratorMapping()->getEiDataSetStructureMapping()->getId();
        }

        if( $version != null && $version->getId() != "" ){

            /** @var EiDataSetStructureTable $tableStructure */
            $tableStructure = Doctrine_Core::getTable("EiDataSetStructure");
            // Réupération de la structure du scénario.
            $structures = $tableStructure->getTreeArrayForITree($version->getEiScenarioId(), array(EiDataSetStructure::$TYPE_NODE));

            // Création du TreeViewer.
            $treeViewer = new TreeViewer("EiDataSetStructure");
            $treeViewer->import($structures);

            // TreeViewer pour le mapping.
            $tree = new TreeView($treeViewer, new ModeSelectInHiddenInputTreeStrategy(), array(
                "id" => "select_node_iterator_".time(),
                "formats" => array(
                    "node" => EiNodeDataSet::getFormNameFormat(),
                    "leaf" => EiLeafDataSet::getFormNameFormat()
                ),
                "inputTarget" => "ei_block_foreach_Iterator_ei_dataset_structure_id",
                "selected" => $selected,
                "actions" => array()
            ));
        }

        $this->tree = $tree;
    }

    /**
     * Composant permettant d'afficher les paramètres d'un bloc et de les mettre à jour.
     *
     * Comprend l'édition du bloc, l'édition des paramètres ainsi que le mapping avec les noeuds du jeu de données.
     *
     * @param sfWebRequest $request
     */
    public function executeShowParams(sfWebRequest $request)
    {
        // On récupère le block parent.
        /** @var EiBlock $block */
        $block = Doctrine_Core::getTable("EiVersionStructure")->findBlock($this->ei_version_structure_id);
        $blockParams = $block->getParams();
        $scenarioId = $this->ei_scenario_id;

        $this->ei_block_parameters = $block->getJSONParameters($block->getAllAscendantsParams());

        // Réupération de la structure du scénario.
        $structures = Doctrine_Core::getTable("EiDataSetStructure")->getTreeArrayForITree($scenarioId);

        // Création du TreeViewer.
        $treeViewer = new TreeViewer("EiDataSetStructure");
        $treeViewer->import($structures);

        $selected = array();

        /** @var EiBlockParam $param */
        foreach( $blockParams as $key => $param ){
            /** @var EiBlockDataSetMapping $mapped */
            $mapped = $param->getMapping(EiBlockDataSetMapping::$TYPE_IN);

            if( $mapped != null ){
                $selected[$key] = $mapped->getEiDatasetStructureId();
            }
        }

        // TreeViewer pour le mapping.
        $this->treeDisplay = new TreeView($treeViewer, new ModeSelectTreeStrategy(), array(
            "baseId" => "datasetstructure_tree_select_".$block->getId(),
            "objects" => $blockParams,
            "selected" => $selected,
            "formats" => array(
                "node" => EiNodeDataSet::getFormNameFormat(),
                "leaf" => EiLeafDataSet::getFormNameFormat()
            ),
            "actions" => array(
                "select" => array(
                    "route" => "eiblockdatasetmapping_select_mapping",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $scenarioId,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_block_param_id' => ':ei_block_param_id'
                    ),
                    "target" => ":ei_block_param_id"
                ),
                "doSelect" => array(
                    "route" => "eiblockdatasetmapping_do_select_mapping",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $scenarioId,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_block_param_id' => ':ei_block_param_id',
                        'type' => EiBlockDataSetMapping::$TYPE_IN
                    ),
                    "target" => ":ei_block_param_id"
                )
            )
        ));

        $selected = array();

        /** @var EiBlockParam $param */
        foreach( $blockParams as $key => $param ){
            /** @var EiBlockDataSetMapping $mapped */
            $mapped = $param->getMapping(EiBlockDataSetMapping::$TYPE_OUT);

            if( $mapped != null ){
                $selected[$key] = $mapped->getEiDatasetStructureId();
            }
        }

        // TreeViewer pour le mapping.
        $this->treeDisplayOut = new TreeView($treeViewer, new ModeSelectTreeStrategy(), array(
            "baseId" => "datasetstructure_tree_select_out_".$block->getId(),
            "objects" => $blockParams,
            "selected" => $selected,
            "formats" => array(
                "node" => EiNodeDataSet::getFormNameFormat(),
                "leaf" => EiLeafDataSet::getFormNameFormat()
            ),
            "actions" => array(
                "select" => array(
                    "route" => "eiblockdatasetmapping_select_mapping",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $scenarioId,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_block_param_id' => ':ei_block_param_id'
                    ),
                    "target" => ":ei_block_param_id"
                ),
                "doSelect" => array(
                    "route" => "eiblockdatasetmapping_do_select_mapping",
                    "parameters" => array(
                        'project_id' => $this->project_id,
                        'project_ref' => $this->project_ref,
                        'ei_scenario_id' => $scenarioId,
                        'profile_name' => $this->profile_name,
                        'profile_id' => $this->profile_id,
                        'profile_ref' => $this->profile_ref,
                        'ei_block_param_id' => ':ei_block_param_id',
                        'type' => EiBlockDataSetMapping::$TYPE_OUT
                    ),
                    "target" => ":ei_block_param_id"
                )
            )
        ));
    }

} 