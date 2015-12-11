<?php
    $urlParameters = $urlParameters->getRawValue();
    if($form->getObject()->isNew()){
        $route = "eidataset_folder_create";
         $title ="Create data set folder";
         $urlParameters['action']='createEiDataSetFolder';
    }else{
        $route = "eidataset_folder_edit";
        $urlParameters['ei_node_id'] = $form->getObject()->getId();
        $urlParameters['action']='updateEiDataSetFolder';
         $title ="Edit data set folder";
    } 
    $url = url_for2($route,$urlParameters );
?>
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_node_form' )) ?>
<form action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> <i class="fa fa-wrench"></i> Properties</h2>
        <div class="panel-actions"> 
            <?php
            $projet_new_version = $urlParameters;
            $projet_new_version['ei_scenario_id'] = $ei_scenario->getId();
            $projet_new_version['action'] = 'edit';
            unset($projet_new_version['ei_data_set_id']);
            ?>
            <a href="<?php echo url_for2('projet_new_eiversion', $projet_new_version); ?>" class="btn-close">
                <i class="fa fa-times"></i>
            </a>
        </div>
    </div> 
    <div class="panel-body" >
        <div class="row">
            <?php echo $form->renderGlobalErrors() ?>
        </div>
        <div class=" form-group">
            <label class="control-label col-md-4" for="inputEmail">Name</label>
            <div class="col-md-8"> 
                <?php echo $form['name']->renderError() ?>
                <?php echo $form['name'] ?>
            </div>
        </div> 
    </div>
    <div class="panel-footer"> 
        <button type="submit" class="btn btn-sm btn-success" id="eiSaveDataSetFolder" >
            <?php echo $form->renderHiddenFields(false) ?>
                <i class="fa fa fa-check"></i> Save    
            </button> 
    </div>
</div>  

</form>
