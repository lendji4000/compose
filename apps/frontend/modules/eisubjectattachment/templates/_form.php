<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 
<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'subject_id' => $subject_id
);  
?>  
<?php
if (!$form->getObject()->isNew()):
    $url_form = 'subject_attachment_update';
    $url_params['id'] = $form->getObject()->getId();
else:
    $url_form = 'subject_attachment_create';
endif;
?> 

<form action="<?php echo url_for2($url_form, $url_params) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
    <?php echo $form->renderHiddenFields() ; ?>  
        
    <div id="uploadAttachment" class="modal " tabindex="-1" role="dialog"
aria-labelledby="newCampaignStepLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3>Attach file</h3>
            </div>
            <div class="modal-body campaignStepBody">  
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span>
                    <span class="fileupload-exists">Change</span>         <?php echo $form['path']->render() ?></span>
                    <span class="fileupload-preview"></span>
                    <a href="#" class="close btn btn-primary fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                </div>


                <div class="row">
                    <?php echo $form['description']->renderError() ?>
                    <?php echo $form['description'] ?>
                </div> 
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" type="submit">
                    <i class="fa fa-check"></i> Upload 
                </button>
            </div>
        </div>
    </div>
</div>    
        
        
</form>
