<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>  
 
<div class="row">   
        <?php if($sf_user->hasFlash('defaultPackageDefineWell')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php echo $sf_user->getFlash('defaultPackageDefineWell') ?></strong>
            </div>
        <?php endif; ?>
        
           <?php  if(isset($defPack)&& $defPack!=null ) :
               $url_form=url_for2('setDefaultPackage',array(
                    'action' =>'updateDefaultPackage',
                    'project_id' => $ei_project->getProjectId(),
                'project_ref' => $ei_project->getRefId()));
            
            else :
                $url_form=url_for2('setDefaultPackage',array(
                'action' =>'createDefaultPackage',
                'project_id' => $ei_project->getProjectId(),
                'project_ref' => $ei_project->getRefId()));
            endif;
            ?> 
  <div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <?php if(isset($defaultPackage) && $defaultPackage!=null):  ?> 
            Default package is now : &nbsp;
                <strong><?php echo $defaultPackage->getName() ?></strong>  
            <?php else:  ?>  
                    <strong>Warning!</strong> No default package, select one in list ... 
            <?php endif; ?>
        </h2>
        <div class="panel-actions"> 
        </div> 
    </div>
    <form action="<?php echo $url_form ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="panel-body"> 
        <div class="row">
             <?php echo $form->renderHiddenFields(); ?> 
                <?php echo $form->renderGlobalErrors(); ?> 
                <?php echo $form['defaultPackage']->renderError() ?>
                <div class="input-append">
                         
                 </div>
        </div>
        <div class="form-group"> 
                <label class=" col-md-3 control-label" for="textarea-input">
                    Change
                </label>
                <div class="col-md-9"> 
                    <?php echo $form['defaultPackage']->render() ?>  
                </div>
            </div> 
         
    </div> 
        <div class="panel-footer">
            <input type="submit" value="Save" class="btn btn-success " /> 
        </div>
    </form>     
    </div>  
</div>
 