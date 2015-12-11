<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
        'delivery_id' => $delivery_id 
     )?>  
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_campaign_form' )) ?>
<form action="<?php echo url_for2('createNewDeliveryCampaign',$url_params) ?>" id="campaignForm"
      class="form-horizontal "  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
   
    <div>
        <?php echo $form->renderHiddenFields(true) ?>
        <?php echo $form->renderGlobalErrors() ?> 
    </div> 
    <?php if (isset($form['ei_delivery_campaign'])): ?> 
    <div>  <?php echo $form['ei_delivery_campaign']->renderError() ?>  </div>
    <?php include_partial('eicampaign/miniForm',array(
        'form' =>$form['ei_delivery_campaign']) ) ?> 
    <?php endif; ?>
    <button class="btn btn-sm btn-success pull-right eiBtnSave" type="submit">
        <i class="fa fa-check"></i> Save 
    </button> 
</form> 


