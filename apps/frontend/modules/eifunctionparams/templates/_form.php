<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php if(isset($url_form) && isset($form)): ?>
<?php $url_form=$url_form->getRawValue() ?>
<?php if(!$form->getObject()->isNew()): 
    $urlName="detailsParamActions";
    $url_form['action']="update";
    else: 
        $urlName="functionParamsActions";
        $url_form['action']="create";        
endif; ?>
<form action="<?php echo url_for2($urlName,$url_form) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="row paramsForm" style="margin-bottom:2px;">
            <?php echo $form->renderGlobalErrors(); ?>
            <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form['name']->renderError() ?>
        <?php echo $form['description']->renderError() ?>
        <?php echo $form['default_value']->renderError() ?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"> 
            <?php echo $form['name'] ?>
        </div>
        <div class=" <?php echo ($form['param_type']->getValue()=="IN")?"col-lg-5 col-md-5 col-sm-5 col-xs-5":"col-lg-8 col-md-8 col-sm-8 col-xs-8" ?>">  
            <?php echo $form['description'] ?>
        </div>
        <?php if($form['param_type']->getValue()=="IN"): ?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">  
            <?php echo $form['default_value'] ?>  
        </div>
        <?php endif; ?>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
            <i class="saveLoader" ></i> 
        </div>
    </div>
</form>
<?php endif; ?>