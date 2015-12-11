<?php
$urlAddNode = url_for2("eidatasetstructure_node_add_childnode", array(
    'project_id' => $ei_node->getProjectId(),
    'project_ref' => $ei_node->getProjectRef(),
    'ei_scenario_id' => $ei_node->getEiScenarioId(),
    'nom_profil' => $nom_profil,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'ei_node_parent_id' => $ei_node->getEiDatasetStructureParentId(),
    'insert_after' => $ei_node->getId()
));

$urlEditNode = url_for2('eidatasetstructure_edit_node', array(
    'ei_root_node_id' => $ei_node->getId(),
    'project_id' => $ei_node->getProjectId(),
    'project_ref' => $ei_node->getProjectRef(),
    'ei_scenario_id' => $ei_node->getEiScenarioId(),
    'nom_profil' => $nom_profil,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref
));

$urlRemoveNode = url_for2('eidatasetstructure_node_remove_childnode', array(
    'ei_node_id' => $ei_node->getId(),
    'project_id' => $ei_node->getProjectId(),
    'project_ref' => $ei_node->getProjectRef(),
    'ei_scenario_id' => $ei_node->getEiScenarioId(),
    'nom_profil' => $nom_profil,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref
));

?>

<div class="sortable ei_datastructure_childnode" ei_datasetstructure_node="<?php echo $ei_node->getId()?>">
    <div class="well">

        <a href="#modalChildNode<?php echo $ei_node->getId() ?>" data-toggle="modal" class="removeDataSetChildNode pull-right">Delete</a>

        <div>
            <strong>
                <a href="<?php echo $urlEditNode; ?>" class="go_to_block_eidatasetstructure">
                    <?php echo $ei_node->getName() ?>
                </a>
            </strong>

            <p class="no-margin padding-left"><?php echo $ei_node->getDescription(); ?></p>
        </div>


        <div id="modalChildNode<?php echo $ei_node->getId() ?>" class="modal hide" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete <?php echo $ei_node->getName(); ?></h3>
            </div>
            <div class="modal-body modal-body-visible-overflow">
                <?php echo "You are about to delete node <strong>".$ei_node->getName()."</strong>.
                    All its children will be deleted as well as content.<br/> Do you really want to delete node <strong>"
                    . $ei_node->getName() . "</strong> ?"; ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Cancel</a>
                <a href="#" data-href="<?php echo $urlRemoveNode; ?>" class="delete_datasetstructure_node btn btn-danger">Delete</a>
            </div>
        </div>
    </div>

    <div class="padding-left">
        <a href="<?php echo $urlAddNode; ?>" class ="add_datasetstructure_node">New node</a>
    </div>
</div>