<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php

    $urlAction = url_for2("eiversionstructure_update_block", array(
        'ei_version_structure_id' => $ei_version_structure_id,
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'ei_version_id' => $ei_version_id,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref
    ));

    if( $form->getObject() instanceof EiBlockForeach ){
        $type = EiVersionStructure::$TYPE_FOREACH;
    }
    else{
        $type = "EiBlock";
    }
?>

<form class="form-horizontal col-lg-12 col-md-12" id="EditEiBlockParamsForm" action="<?php echo $urlAction ?>" method="post">

    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><i class="fa fa-wrench"></i> Properties</h2>
        </div>

        <div class="panel-body">
            <div class="row">
                <?php echo $form->renderGlobalErrors() ?>

                <?php if (!$form->getObject()->isNew()): ?>
                    <input type="hidden" name="sf_method" value="put" />
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-lg-10 col-md-10">
                    <div class="form-group">
                        <label class="control-label col-md-4">Block Name</label>
                        <div class="col-md-8"><?php echo $form['name'] ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-md-10">
                    <div class="form-group">
                        <label class="control-label col-md-4">Block Description</label>
                        <div class="col-md-8"><?php echo $form['description'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_bloc_parameter') ?> Parameters</h2>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <table class="table table-striped dataTable editBlockParamsFormList">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($form['EiBlockParams'])): ?>
                            <?php foreach($form['EiBlockParams'] as $key => $fieldSchema): ?>
                                <?php include_partial('blockparam/newBlockParam',array('form' => $form, 'size' => $key)) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3">
                                <a href="<?php echo url_for2('addBlockParam') ?>" class="btn btn-mini btn-success addParamToBlockButton">
                                    <?php echo ei_icon('ei_add','lg') ?>
                                </a>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if( $form->getObject() instanceof EiBlockForeach ){ ?>
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2><i class="fa fa-repeat"></i> Node to iterate</h2>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <?php if (isset($form['Iterator'])): ?>
                            <?php echo $form['Iterator']->renderHiddenFields(); ?>
                        <?php endif; ?>

                        <div>
                            <?php include_component("block", "selectorNodeForm", array(
                                "ei_version_id" => $ei_version_id,
                                "block" => $form->getObject()
                            )); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
    if (!$form->getObject()->isNew()){

        $treeDisplay->importAssets();

        include_component("eiblockdatasetmapping", "blockMapping", array(
            'ei_version_structure_id' => $ei_version_structure_id,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'ei_version_id' => $ei_version_id,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'ei_scenario_id' => $ei_scenario_id,
            'tree' => $treeDisplay
        ));

        include_component("eiblockdatasetmapping", "dataSetSynchronization", array(
            'ei_version_structure_id' => $ei_version_structure_id,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'ei_version_id' => $ei_version_id,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'ei_scenario_id' => $ei_scenario_id,
            'tree' => $treeDisplayOut
        ));

    }
    ?>

    <div class="row-fluid">

        <?php echo $form->renderHiddenFields(false) ?>

        <input type="hidden" name="typeBlock" value="<?php echo $type ?>" />

<!--        <button class="btn btn-small btn-success pull-right" id="updateBlockScenarioVersion" type="submit">-->
<!--            <i class="icon icon-ok-circle"></i> Save-->
<!--        </button>-->
    </div>
</form>

<script type="text/javascript">
    ei_block_params = <?php echo html_entity_decode($ei_block_parameters); ?>;
</script>