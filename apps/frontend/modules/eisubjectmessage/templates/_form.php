<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 
<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'subject_id' => $subject_id)
?>

<?php
if (!$form->getObject()->isNew()):
    $url_form = 'subject_message_edit';
    $url_params['id'] = $form->getObject()->getId();
    $url_params['action'] = 'update';
else:
    $url_form = 'subject_message_create';
    $url_params['action'] = 'create';
    $url_params['type'] = $type;
    $url_params['parent_id'] = $parent_id;
endif;
?>  
    <div class="modal-body">
        <form action="<?php echo url_for2($url_form, $url_params) ?>" id="subjectMessageForm"
              method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
            <?php if (!$form->getObject()->isNew()): ?>
                <input type="hidden" name="sf_method" value="put" />
            <?php endif; ?>
            <?php echo $form->renderHiddenFields(); ?> 

            <div> 
                <?php echo $form['message']->renderError() ?>
                <?php echo $form['message'] ?>
            </div> 

        </form>
    </div> 
