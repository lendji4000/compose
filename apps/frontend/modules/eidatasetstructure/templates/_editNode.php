<div class="row-fluid">
    <div class="span1"></div>

<?php

/*****     PARTIE GESTION DU NOEUD PARENT.
/**********************************************************************************************************************/

?>

    <div class="span10">
        <div>
            <h4>Node Properties</h4>

            <?php
            $ei_node = $ei_node_root_form->getObject();

            include_partial('formNode', array(
                'form' => $ei_node_root_form,
                'project_id' => $project_id,
                'project_ref' => $project_ref,
                'ei_scenario' => $ei_scenario->getId(),
                'nom_profil' => $nom_profil,
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref
            ));
            ?>
        </div>

<?php

/*****     PARTIE GESTION DES FEUILLES.
/**********************************************************************************************************************/

?>

        <div class="table-responsive">
            <h4>Leaves</h4>

            <table class="table table-bordered table-striped dataTable nodeDataSetLeavesFormList">
                <thead>
                    <tr>
                        <th class="span4">Name</th>
                        <th class="span5">Description</th>
                        <th class="span3"></th>
                    </tr>
                </thead>
                <tbody class="contentForm">
                <?php

                if( isset($ei_node_leaves_form) )
                {
                    foreach( $ei_node_leaves_form as $leaf_form ){

                        include_partial('formLeaf', array(
                            'form' => $leaf_form,
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'ei_scenario' => $ei_scenario->getId(),
                            'nom_profil' => $nom_profil,
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'ei_node_id' => $ei_node->getId(),
                            'ei_leaf_id' => $leaf_form->getObject()->getId()
                        ));
                    }
                }

                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <a href="<?php echo url_for2('eidatasetstructure_node_add_leaf', array(
                                'project_id' => $project_id,
                                'project_ref' => $project_ref,
                                'ei_scenario_id' => $ei_scenario->getId(),
                                'nom_profil' => $nom_profil,
                                'profile_id' => $profile_id,
                                'profile_ref' => $profile_ref,
                                'ei_node_id' => $ei_node->getId()
                            )) ?>" class="btn btn-mini btn-success addLeafToNodeButton">
                                <?php echo ei_icon('ei_add','lg') ?>
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

<?php

/*****     PARTIE GESTION DES BLOCKS ENFANTS.
/**********************************************************************************************************************/

?>

        <div>
            <h4>Children</h4>

            <div id="datasetstructure_node_children">

            <?php
                $urlAddNode = url_for2("eidatasetstructure_node_add_childnode", array(
                    'project_id' => $project_id,
                    'project_ref' => $project_ref,
                    'ei_scenario_id' => $ei_scenario->getId(),
                    'nom_profil' => $nom_profil,
                    'profile_id' => $profile_id,
                    'profile_ref' => $profile_ref,
                    'ei_node_parent_id' => $ei_node->getId()
                ));
            ?>

                <div class="padding-left">
                    <a href="<?php echo $urlAddNode; ?>" class ="add_datasetstructure_node">New node</a>
                </div>

                <?php

                // Pour chaque enfant, s'il en existe, on ajoute le partial relatif au noeud.
                if( isset($ei_node_children) ){
                    foreach( $ei_node_children as $child_node ){
                        include_partial('childNode', array(
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'ei_scenario' => $ei_scenario->getId(),
                            'nom_profil' => $nom_profil,
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'ei_node' => $child_node,
                            'ei_node_id' => $child_node->getId()
                        ));
                    }
                }

                ?>

            </div>
        </div>
    </div>

    <div class="span1"></div>
</div>