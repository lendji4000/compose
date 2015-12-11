<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name )?> 
<?php
if (!$form->getObject()->isNew()):
    $url_form = 'delivery_edit';
    $url_params['delivery_id'] = $form->getObject()->getId();
    $url_params['action'] = 'update';
else:
    $url_form = 'delivery_create';
endif;
?>  
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_form' )) ?> 
<form action="<?php echo url_for2($url_form, $url_params) ?>" 
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>
      class="form-horizontal " id="deliveryForm">
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>    
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><strong><i class="fa fa-wrench"></i>Properties </strong> /  <?php echo ei_icon('ei_edit') ?>  </h2>
            <div class="panel-actions">
                <?php if (!$form->getObject()->isNew()): ?>
                <?php $delivery_show=$url_params;  $delivery_show['action']='show' ?>
                    <a class=" btn-default " href="<?php echo url_for2('delivery_edit',$delivery_show) ?>"> 
                        <?php echo ei_icon('ei_show') ?> 
                    </a> 
                <?php endif; ?> 
            </div>
        </div> 

        <div class="panel-body dateTimePickerFix"> 
            <div class="row">
            <?php echo $form->renderHiddenFields(false) ?> 
            <?php echo $form->renderGlobalErrors() ?> 
            </div>  
            <div class=" form-group">
                <label class="control-label col-md-4" for="inputTitle">Title</label>
                <div  class="col-md-8">
                    <?php echo $form['name']->renderError() ?>
                    <?php echo $form['name'] ?>
                </div>
            </div> 
            <div class=" form-group">
                <?php echo $form['delivery_date']->renderError() ?>
                <label class="control-label col-md-4" for="inputDeliveryDate">Delivery date </label>
                <div class="col-md-8">
                    <div id="datetimepickerDeliveryDate" class="input-group input-append  date">
                        <?php echo $form['delivery_date'] ?>
                        <span class="input-group-addon add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div> 
                </div>
            </div>

            <div class=" form-group ">
                <label class="control-label col-md-4" for="deliveryState">State</label>
                <div class="col-md-8"> 
                    <?php echo $form['delivery_state_id']->renderError() ?>
                    <?php echo $form['delivery_state_id'] ?>
                </div>
            </div> 

        </div> 
    </div>
    <div class="panel panel-default eiPanel" id="deliveryContentDescription">
        <div class="panel-heading">
            <h2><i class="fa fa-text-width "></i>  Description</h2>
            <div class="panel-actions"> 
            </div>
        </div> 

        <div class="panel-body">  
            <?php echo $form['description']->renderError() ?>
            <?php echo $form['description'] ?>
        </div> 
        <div class="panel-footer">
            <button class="btn btn-sm btn-success eiBtnSave " type="submit" id="saveDelivery">
                <i class="fa fa-check"></i> Save 
            </button> 
        </div>
    </div>
</form>