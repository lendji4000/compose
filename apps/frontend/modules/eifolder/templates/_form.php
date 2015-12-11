<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php 
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);

$urlArray = $url_tab;
if ($form->getObject()->isNew()):
    $urlArray['action'] = 'create';
    $urlArray['root_id'] = $root_id;
    $url_form='create_folder';
else:
$urlArray['action'] = 'update'; 
$urlArray['node_id'] = $form->getObject()->getNode()->getId();
$urlArray['folder_id'] = $form->getObject()->getId();
$url_form="path_folder";
endif;
$url = url_for2($url_form, $urlArray);
?>

<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_folder_form' )) ?>
<form class="form-horizontal" id="folder_edit_form" action="<?php echo $url ?>"
              method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?> >
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <?php echo ei_icon('ei_folder') ?> Properties
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    <div class="panel-body">
        
                  <?php echo $form->renderHiddenFields(true); ?> 
                  <?php echo $form->renderGlobalErrors() ?>
            <div class="form-group"> 
                <label class="control-label col-md-3" for="text-input">
                    <?php echo $form['name']->renderLabel() ?>
                </label>
                <div class="col-md-9"> 
                    <?php echo $form['name']->renderError() ?>
                    <?php echo $form['name']->render() ?> 
                    <span class="help-block"> &nbsp;</span>
                </div>
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
        <div class="input-group-btn">
              <button class="btn btn-small btn-success" type="submit" id="saveFolder">
                  <i class="fa fa-check"></i> Save
              </button>
            <?php if (!$form->getObject()->isNew()) :?>
            <a class=" btn btn-default btn-small" data-toggle="modal" role="button" title="Change Node Parent" href="#modalDiagram">
                    <?php echo ei_icon('ei_folder') ?> Change parent
                </a>
                <!--<a id="delete_folder" class="btn btn-small btn-danger"  title="Delete"
                   href="<?php $urlArray['action'] = "delete"; echo url_for2('path_folder', $urlArray) ?>     ">
                    <?php echo ei_icon('ei_delete') ?> Delete
                </a>-->
            <?php endif; ?>
        </div>
    </div>
</div>  
</form>




 

