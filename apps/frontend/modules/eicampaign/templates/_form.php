<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name);
if(isset($ei_delivery) && $ei_delivery!=null) $url_params['delivery_id']=$ei_delivery->getId();
if(isset($ei_subject) && $ei_subject!=null) $url_params['subject_id']=$ei_subject->getId();
?>
<?php 
if(!$form->getObject()->isNew()):
    $url_form='campaign_update';
    $url_params['campaign_id']=$form->getObject()->getId();
    else:
        $url_form='campaign_create';
endif;
?>  
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_campaign_form' )) ?> 
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <?php if (!$form->getObject()->isNew()) : ?>
            <?php echo ei_icon('ei_edit') ?> Edit
            <?php else:?>
            <h2><strong><i class="fa fa-wrench"></i>Properties </strong> /  <?php echo ei_icon('ei_edit') ?>  </h2>
            <?php endif;?>
        </h2>
        <div class="panel-actions"> 
        </div>
    </div>
    <form id="campaignForm"  action="<?php echo url_for2($url_form,$url_params) ?>" 
                method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="panel-body">
        
            <?php if (!$form->getObject()->isNew()): ?>
            <input type="hidden" name="sf_method" value="put" />
            <?php endif; ?>
            <div class="row">
                <?php echo $form->renderHiddenFields(false) ?> 
                <?php echo $form->renderGlobalErrors() ?> 
            </div>  
            <?php include_partial('eicampaign/miniForm',array('form' =>$form ) ) ?>  
            <div class="form-group form-actions">
                
            </div> 
        
    </div>
    <div class="panel-footer">  
        <button class="btn btn-sm btn-success eiBtnSave " type="submit" id="saveCampaign">
            <i class="fa fa-check"></i> Save 
        </button>
    </div>       
    </form>     
</div>  
