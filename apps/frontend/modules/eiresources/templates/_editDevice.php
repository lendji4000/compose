<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php
    $url_form='resourcesDeviceUpdate';
?>
<?php if(isset($form)): ?>
<form action="<?php echo url_for2($url_form) ?>" method="post">      
    <div id="editDevice" class="modal " tabindex="-1" role="dialog" aria-labelledby="editDevice" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                     <h3>Edit a device</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <?php echo $form->renderHiddenFields(false) ?> 
                    <?php echo $form->renderGlobalErrors() ?> 
                    </div>  
                    <div class="row">
                        <div class=" form-group">
                            <label class="control-label col-md-4" for="name">Name</label>
                            <div class="col-md-8"> 
                                <?php echo $form['name']->renderError() ?>
                                <?php echo $form['name'] ?>
                            </div>
                        </div>
                        <div class=" form-group">
                             <label class="control-label col-md-4" for="deviceVisibility">Device visibility</label>
                             <div class="col-md-8"> 
                                <?php echo $form['device_user_visibility_id']->renderError() ?>
                                <?php echo $form['device_user_visibility_id'] ?>
                             </div>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" type="submit"></i> Edit</button>
                    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php endif; ?>