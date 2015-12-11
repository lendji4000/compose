<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
    
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_form' )) ?>
<form  class="form-horizontal " id="iterationForm"
    action="<?php echo $uri_form ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>    
    <div class="row" >
        <?php echo $form->renderHiddenFields() ?> 
        <?php echo $form->renderGlobalErrors() ?> 
    </div>  
    <div class="row"> 
        <div class=" form-group">
            <label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-4" for="iterationComment">Comment</label>
            <div class="col-lg-10 col-md-9 col-sm-3 col-xs-8"> 
                <?php echo $form['description']->renderError() ?>
                <?php echo $form['description'] ?>
            </div> 
        </div> 
    </div>  

</form>



         