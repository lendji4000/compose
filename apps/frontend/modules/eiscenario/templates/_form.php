<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php 
$paramsForUrl = array('project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name);


if ($form->getObject()->isNew()) :
    $uri_new=$paramsForUrl;
    $uri_new['action'] = 'create';
    $uri_new['root_id'] = $root_id;
    $urlForm = url_for2('projet_eiscenario', $uri_new);
else :
    $projet_eiscenario_action = $paramsForUrl;
    $projet_eiscenario_action['ei_scenario_id'] = $form->getObject()->getId();
    $projet_eiscenario_action['action'] = 'update';
    $urlForm = url_for2('projet_eiscenario_action', $projet_eiscenario_action);
    if (isset($ei_version) && $ei_version != null):
        $projet_new_eiversion=$paramsForUrl;
        $projet_new_eiversion['id_version'] = $ei_version->getId();
        $projet_new_eiversion['action'] = 'update'; 
        $projet_new_eiversion['ei_scenario_id'] = $form->getObject()->getId();
        //$urlForm = url_for2('projet_new_eiversion', $projet_new_eiversion);
        endif;
endif;  ?> 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_scenario_form' )) ?>
<?php //if(isset($defPack) && $defPack!=null): ?>
<form class="form-horizontal" id="ei_scenario_form" action="<?php echo $urlForm ?>"
              method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?> >
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <i class="fa fa-wrench"></i> Properties
        </h2> 
    </div>
    <div class="panel-body">
        
                  <?php echo $form->renderHiddenFields(); ?> 
                  <?php echo $form->renderGlobalErrors() ?>
            <div class="form-group"> 
                <label class="control-label col-md-3" for="text-input">
                    <?php echo $form['nom_scenario']->renderLabel() ?>
                </label>
                <div class="col-md-9">
                    <?php echo $form['nom_scenario']->renderError() ?>
                    <?php echo $form['nom_scenario']->render() ?> 
                    <span class="help-block">Enter scenario name</span>
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
            <div class="form-group form-actions">
                
            </div>
            <div class="row hidden">
                <?php
                $f = $form->getEmbeddedForms();
                foreach ($f as $embedForm):
                    ?>
                    <?php if ($embedForm instanceof EiNodeForm): ?> 

                        <div class="row"> 
                            <div class=" alert-error"> <?php echo $form['ei_node']->renderError() ?> </div>
                        </div>
                        <div class="form-group">  
                            <label class="control-label" >Name</label>  
                            <div class="controls">  
                    <?php echo $form['ei_node']['name'] ?> 
                                <div class=" form-group error">
                                    <span class="help-inline"><?php echo $form['ei_node']['name']->renderError() ?> </span> 
                                </div> 
                            </div>
                        </div>
                        <div class="form-group">  
                            <label class="control-label" >Child Of </label>  
                            <div class="controls">  
                    <?php echo $form['ei_node']['parent_id'] ?> 
                                <div class=" form-group error">
                                    <span class="help-inline"><?php echo $form['ei_node']['parent_id']->renderError() ?> </span> 
                                </div> 
                            </div>
                        </div> 
                        
                    <?php endif; ?>
                    <?php endforeach; ?>
            </div> 
    </div>
    <div class="panel-footer">
        <?php if (!$form->getObject()->isNew()) : ?>
        <a class=" btn btn-default btn-sm" data-toggle="modal" role="button" title="Change Node Parent" href="#modalDiagram">
             <?php echo ei_icon('ei_folder_open') ?>
        </a>
            <?php endif; ?>
        <?php if ($form->getObject()->isNew()) : ?>
        <button  class="btn btn-sm btn-success" id="saveScenarioAndStay">
            <i class="fa fa fa-check"></i> Save  
        </button> 
        <button type="submit" class="btn btn-sm btn-success" id="saveScenario">
            <i class="fa fa fa-check"></i> Save And Edit
        </button> 
        <?php else: ?>
        <button type="submit" class="btn btn-sm btn-success" id="updateScenario">
            <i class="fa fa fa-check"></i> Save
        </button> 
        <?php endif; ?>
        
    </div>
</div>  
</form>
<?php //endif; ?>




 

