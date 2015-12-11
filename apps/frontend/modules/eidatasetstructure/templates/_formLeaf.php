<?php

$params = array(
    'ei_node_id' => $ei_node_id,
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'ei_scenario_id' => $ei_scenario,
    'nom_profil' => $nom_profil,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref
);

if($form->isNew())
{
    if(isset($insert_after))
        $params['insert_after'] = $insert_after;

    $class = "new";

    $url_delete = "#";
    $url = url_for2("eidatasetstructure_node_create_leaf", $params);
}
else
{
    $class = "old";

    $params["ei_leaf_id"] = $form->getObject()->getId();

    $url_delete = url_for2('eidatasetstructure_node_delete_leaf', $params);
    $url = url_for2("eidatasetstructure_node_update_leaf", $params);
}

?>
<tr>
    <td colspan="3">
        <form class="addLeafToNodeForm form-inline padding-left row" action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

            <?php if (!$form->getObject()->isNew()): ?>
                <input type="hidden" name="sf_method" value="put" />
            <?php endif; ?>

            <?php
                echo $form->renderGlobalErrors();
                echo $form->renderHiddenFields();
            ?>

            <div class="row-fluid">
                <div class="span4">
                        <?php echo $form['name'] ?>
                        <?php echo $form['name']->renderError() ?>
                </div>
                <div class="span5">
                        <?php echo $form['description'] ?>
                        <?php echo $form['description']->renderError() ?>
                </div>
                <div class="span3">
                    <div class="btn-group">
                        <button class="btn btn-mini btn-success submit_dataset_node_leaf" value="Save">Save</button>
                        <a href="<?php echo $url_delete ?>" class="btn btn-mini btn-danger delete-btn delete_dataset_node_leaf_<?php echo $class; ?>"><i class="icon-trash"></i></a>
                    </div>
                </div>

            </div>
        </form>

    </td>
</tr>
