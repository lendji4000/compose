<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 
<?php $url_params = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'subject_id' => $subject_id); 
 
if (!$form->getObject()->isNew()):
    $url_form = 'subject_migration_edit';
    $url_params['id'] = $form->getObject()->getId();
    $url_params['action'] = 'update';
else:
    $url_form = 'subject_migration_create';
    $url_params['action'] = 'create';
endif;
     
    
?>  
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_migration_form' )) ?> 
<form action="<?php echo url_for2($url_form, $url_params) ?>" id="detailOrSolutionOrMigrationForm"
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
          <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>    
    <?php echo $form->renderGlobalErrors() ?>
    <?php echo $form->renderHiddenFields() ?> 
    <?php echo $form['migration']->renderError() ?>
    <?php echo $form['migration'] ?>
    <button class="btn btn-sm btn-success  updateDetailOrSolutionOrMigration" type="submit">
        <i class="fa fa-check"></i> Save 
    </button>
</form>  