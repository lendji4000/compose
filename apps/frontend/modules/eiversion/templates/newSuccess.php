<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php
    $paramsForUrl = array('project_id' => $project_id,
    'project_ref' => $project_ref,    
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'ei_scenario_id' => $ei_scenario_id,
    'action' => 'create');
     
    $urlAction = url_for2('projet_new_eiversion', $paramsForUrl);
?>
 
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_version_form' )) ?> 
 
<form action="<?php echo $urlAction ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <i class="fa fa-wrench"></i> Properties
        </h2>
        <div class="panel-actions"> 
        </div>
    </div>
    <div class="panel-body">
        
                  <?php echo $form->renderHiddenFields(); ?> 
                  <?php echo $form->renderGlobalErrors(); 
                  echo $form['ei_scenario_package']['ei_scenario_id']->renderError();
                  echo $form['ei_scenario_package']['package_id']->renderError() ;
                          echo $form['ei_scenario_package']['package_ref']->renderError() ; ?> 
            <?php if(isset($ei_package) && $ei_package!=null): ?>
            <div class="form-group"> 
                <label class="control-label col-md-3" for="text-input">
                    Package
                </label>
                <div class="col-md-9">
                    <?php echo $ei_package->getName() ?> 
                </div>
            </div> 
            <?php endif; ?>
            <div class="form-group"> 
                <label class="control-label col-md-3" for="text-input">
                    <?php echo $form['libelle']->renderLabel() ?>
                </label>
                <div class="col-md-9">
                    <?php echo $form['libelle']->renderError() ?>
                    <?php echo $form['libelle']->render() ?>  
                </div>
            </div>    
            <div class="form-group"> 
                <label class=" col-md-3 control-label" for="textarea-input">
                    <?php echo $form['description']->renderLabel() ?>
                </label>
                <div class="col-md-9">
                    <?php echo $form['description']->renderError() ?>
                    <?php echo $form['description']->render() ?>
                </div>
            </div>   
    </div>
    <?php if (!$sf_request->isXmlHttpRequest()): ?>
    <div class="panel-footer">  
        <!-- Pour des ajouts non éffectué via Ajax  --> 
            <button type="submit" class="btn btn-sm btn-success">
                <?php $saveText="Save";
                if(isset($ei_scenario_package) && $ei_scenario_package!=null) : $saveText="Save And Associate to current package"; endif; ?>
                <i class="fa fa fa-check"></i> <?php echo $saveText ?>
            </button>  
        <?php if(isset($ei_scenario_package) && $ei_scenario_package!=null): ?>
        <?php $editVersion=$paramsForUrl;
            $editVersion['action']='edit'; 
            $editVersion['ei_version_id']=$ei_scenario_package->getEiVersionId(); 
            $urlActionEditVersion = url_for2('projet_edit_eiversion', $editVersion); ?>
        <a href="<?php echo $urlActionEditVersion ?>" class="btn btn-sm btn-link "> 
            <?php echo ei_icon('ei_version') ?>
            Go to Associate version
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?> 
</div>  
</form>
