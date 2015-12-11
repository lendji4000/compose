<?php

if($form->getObject()->isNew())
{
    $class="form-inline new_datasetstructure_childnode";
    $params = array(
        'ei_node_parent_id' => $ei_node_parent_id,
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'ei_scenario_id' => $ei_scenario,
        'nom_profil' => $nom_profil,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref
    );

    if(isset($insert_after) && $insert_after != null)
        $params['insert_after'] = $insert_after;

    $url = url_for2("eidatasetstructure_node_create_childnode", $params);

}
else{
    $class="form-horizontal update_datasetstructure_node";

    $url = url_for2("eidatasetstructure_update_node", array(
        'ei_node_id' => $form->getObject()->getId(),
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'ei_scenario_id' => $ei_scenario,
        'nom_profil' => $nom_profil,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref
    ));
}

?>


<form class="<?php echo $class ?>" action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>

    <div class="row-fluid">
        <?php echo $form->renderGlobalErrors();
        echo $form->renderHiddenFields(); ?>
    </div>
    <div class="row-fluid ">
        <?php echo $form['name']->renderError() ?>
        <?php echo $form['description']->renderError() ?>
    </div>

    <table class="table table-bordered table-striped dataTable">
        <tr>
            <th>Node Name</th>
            <td colspan="2"><?php echo $form['name'] ?></td>
        </tr>
        <tr>
            <th>Node Description</th>
            <td colspan="2"><?php echo $form['description'] ?></td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="btn-group span2 pull-right">
                    <?php if($form->isNew()){ ?>
                        <button class="btn btn-mini btn-success submit_datasetstructure_node_new" value="Save">Save</button>
                        <a href="#!" class="btn btn-mini btn-danger delete_datasetstructure_node_new"><i class="icon-trash"></i></a>
                    <?php }else{ ?>
                        <button class="btn btn-mini btn-success submit_datasetstructure_node" value="Save">Save</button>
                    <?php }  ?>
                </div>
            </td>
        </tr>
    </table>
</form>