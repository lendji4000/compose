<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<?php 
if(!$form->getObject()->isNew()):
    $url_form='subject_update';
    $url_params['subject_id']=$form->getObject()->getId();
    else:
        $url_form='subject_create';
endif;
?>   
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_form' )) ?>
<form  class="form-horizontal " id="subjectForm"
    action="<?php echo url_for2($url_form,$url_params) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>   
    <div class="panel panel-default eiPanel">
        <div class="panel-heading"> 
            <h2><strong><i class="fa fa-wrench"></i>Properties </strong> /  <?php echo ei_icon('ei_edit') ?>  </h2>
            <div class="panel-actions">
                <?php if (!$form->getObject()->isNew()): ?>
                <a class="  btn-default" id="accessBugProperties" 
                       href="<?php  echo url_for2('subject_show', $url_params) ?>"> 
                        <?php echo ei_icon('ei_show') ?>  
                    </a>  
                <?php endif; ?>
            </div>
        </div> 

        <div class="panel-body"> 
            <div class="panel panel-default eiPanel">
                <div class="panel-heading"> 
                    <h2><strong><i class="fa fa-wrench"></i>Main informations </strong>   </h2> 
                </div> 
                <div class="panel panel-body dateTimePickerFix">
            <div class="row" >
                <?php echo $form->renderHiddenFields() ?> 
                <?php echo $form->renderGlobalErrors() ?> 
            </div>  
            <div class="row">
                <div class="col-lg-6 col-md-6 ">
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="inputEmail">Title</label>
                        <div class="col-md-8"> 
                            <?php echo $form['name']->renderError() ?>
                            <?php echo $form['name'] ?>
                        </div>
                    </div>
                    <div class=" form-group">
                         <label class="control-label col-md-4" for="subjectState">State</label>
                         <div class="col-md-8"> 
                            <?php echo $form['subject_state_id']->renderError() ?>
                            <?php echo $form['subject_state_id'] ?>
                         </div>
                    </div> 
                    <div class=" form-group">
                         <label class="control-label col-md-4" for="subjectPriority">Priority</label>
                         <div class="col-md-8"> 
                            <?php echo $form['subject_priority_id']->renderError() ?>
                            <?php echo $form['subject_priority_id'] ?>
                         </div>
                    </div> 
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="subjectDelivery">Delivery</label>
                        <div class=" col-md-8"> 
                            <?php echo $form['delivery_id']->renderError() ?>
                            <?php echo $form['delivery_id'] ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="subjectType">Type</label>
                        <div class=" col-md-8"> 
                            <?php echo $form['subject_type_id']->renderError() ?>
                            <?php echo $form['subject_type_id'] ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="subjectExternalId">External ID</label>
                        <div class="col-md-8">  
                            <?php echo $form['alternative_system_id']->renderError() ?>
                            <?php echo $form['alternative_system_id'] ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 ">
                    <div class=" form-group">
                         <label class="control-label col-md-4" for="developmentEstimation">Development estimation</label>
                         <div class="col-md-8"> 
                            <?php echo $form['development_estimation']->renderError() ?>
                            <?php echo $form['development_estimation'] ?>
                         </div>
                    </div> 
                    <div class=" form-group">
                        <label class="control-label col-md-4" for="testEstimation">Test estimation</label>
                        <div class=" col-md-8"> 
                            <?php echo $form['test_estimation']->renderError() ?>
                            <?php echo $form['test_estimation'] ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <?php echo $form['expected_date']->renderError() ?>
                        <label class="control-label col-md-4" for="expectedDate">Expected date</label>
                        <div class="col-md-8">
                            <div id="datetimepickerExpectedDate" class="input-group input-append  date">
                                <?php echo $form['expected_date'] ?>
                                <span class="input-group-addon add-on">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>  

        </div> 
    </div>
    <?php //if (!$form->getObject()->isNew()): ?>
    <div class="panel panel-default  " id="subjectContentDescription">
        <div class="panel-heading">
            <h2><i class="fa fa-text-width "></i>  Description</h2>
        </div> 
        
        <div class="panel-body">  
            <?php echo $form['description']->renderError() ?>
            <?php echo $form['description'] ?>
        </div> 
     </div>
    <?php //endif; ?> 
        </div>
        <div class="panel-footer">
            <button id="saveBug" class="btn btn-sm btn-success  " type="submit">
                <i class="fa fa-check"></i> Save 
            </button>
        </div>
    
</form>



         