<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>  
<?php $url_params=array( 
              'project_id' => $ei_project->getProjectId(),
              'project_ref' => $ei_project->getRefId(),
              'profile_id' => $ei_profile->getProfileId(),
              'profile_ref' => $ei_profile->getProfileRef(),
              'profile_name' => $ei_profile->getName(),
              'function_id' => $kal_function->getFunctionId(),
              'function_ref' => $kal_function->getFunctionRef())  ?>
<?php 
if(!$form->getObject()->isNew()):
    $url_form='editFunctionCampaign'; 
    $url_params['action']='update';
    $url_params['campaign_id']=$form->getObject()->getCampaignId();
    else:
        $url_form='createFunctionCampaign';
        $url_params['action'] ='create';
endif;
?>  
<form action="<?php echo url_for2($url_form,$url_params) ?>" id="campaignForm"
      class="form-horizontal "  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    
<div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2> 
                    <?php if (!$form->getObject()->isNew()) : ?>
                    <?php echo ei_icon('ei_edit') ?> Edit
                    <?php else:?>
                    <?php echo ei_icon('ei_add' ) ?> New Function Campaign
                    <?php endif;?>
                </h2> 
            </div> 
            <div class="panel-body"> 
                   <div>
                        <?php echo $form->renderHiddenFields(true) ?>
                        <?php echo $form->renderGlobalErrors() ?> 
                    </div> 
                    <?php if (isset($form['ei_function_campaign'])): ?> 
                    <div>  <?php echo $form['ei_function_campaign']->renderError() ?>  </div> 
                    <?php include_partial('eicampaign/miniForm',array(
                        'form' =>$form['ei_function_campaign']) ) ?> 
                    <?php endif; ?> 
            </div>
            <div class="panel-footer">  
                <button class="btn btn-sm btn-success  " type="submit">
                    <i class="fa fa-check"></i> Save 
                </button>
            </div>        
</div>  

</form> 