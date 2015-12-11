<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>  
<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'subject_id' => $subject_id )
?>   

<?php 
if(!$form->getObject()->isNew()):
    $url_form='editSubjectCampaign'; 
    $url_params['action']='update';
    $url_params['campaign_id']=$form->getObject()->getCampaignId();
    else:
        $url_form='createSubjectCampaign';
        $url_params['action'] ='create';
endif;
?>  
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_campaign_form' )) ?> 
<form action="<?php echo url_for2($url_form,$url_params) ?>" id="campaignForm"
      class="form-horizontal "  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
   
    <div>
        <?php echo $form->renderHiddenFields(true) ?>
        <?php echo $form->renderGlobalErrors() ?> 
    </div> 
    <?php if (isset($form['ei_subject_campaign'])): ?> 
    <div class="row">  
        <?php echo $form['ei_subject_campaign']->renderError() ?> 
    </div>
    <?php $url_params_form=$url_params;
        $url_params_form['form']=$form['ei_subject_campaign'] ?>
    <?php include_partial('eicampaign/miniForm',$url_params_form) ?> 
    <?php endif; ?>
    
    <button class="btn btn-sm btn-success pull-right" type="submit">
        <i class="fa fa-check"></i> Save 
    </button> 
</form> 


