<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $url_params=array( 
              'project_id' => $project_id,
              'project_ref' => $project_ref,
              'profile_id' => $profile_id,
              'profile_ref' => $profile_ref,
              'profile_name' => $profile_name,
              'campaign_graph_id'=> $campaign_graph_id)  ?>
<?php 
if(!$form->getObject()->isNew()):
    $url_form='campaign_graph_create_bug_context';
    else:
        $url_form='campaign_graph_create_bug_context';
endif;
?> 
<form  class="form-horizontal " id="bugContextForm"
    action="<?php echo url_for2($url_form,$url_params) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    
        <div class="row">
            <?php echo $form->renderHiddenFields() ?> 
            <?php echo $form->renderGlobalErrors() ?> 
        </div> 
        <div class="row"> 
        <?php if (isset($form['ei_bug_context'])): ?> 
            <?php echo $form['ei_bug_context']->renderError() ?>
            <?php echo $form['ei_bug_context']->renderHiddenFields() ?>
        
        <div class="col-lg-6 col-md-6 ">
            <div class=" form-group">
                 <label class="control-label" for="inputEmail">Title</label>
                 <div class="controls">
                     <!--<input type="text" id="inputEmail" placeholder="Title">-->
                     <?php echo $form['name']->renderError() ?>
                     <?php echo $form['name'] ?>
                 </div>
             </div>
             <div class=" form-group">
                 <label class="control-label" for="subjectState">State</label>
                 <div class="controls"> 
                     <?php echo $form['subject_state_id']->renderError() ?>
                     <?php echo $form['subject_state_id'] ?>
                 </div>
             </div> 
            <div class=" form-group">
                 <label class="control-label" for="subjectPriority">Priority</label>
                 <div class="controls"> 
                     <?php echo $form['subject_priority_id']->renderError() ?>
                     <?php echo $form['subject_priority_id'] ?>
                 </div>
             </div> 
         </div>
         <div class="col-lg-6 col-md-6 ">
             <div class=" form-group">
                 <label class="control-label" for="subjectDelivery">Delivery</label>
                 <div class="controls"> 
                     <?php echo $form['delivery_id']->renderError() ?>
                     <?php echo $form['delivery_id'] ?>
                 </div>
             </div>
             <div class=" form-group">
                 <label class="control-label" for="subjectType">Type</label>
                 <div class="controls"> 
                     <?php echo $form['subject_type_id']->renderError() ?>
                     <?php echo $form['subject_type_id'] ?>
                 </div>
             </div>
             <div class=" form-group">
                 <label class="control-label" for="subjectExternalId">External ID</label>
                 <div class="controls"> 
                     <?php echo $form['alternative_system_id']->renderError() ?>
                     <?php echo $form['alternative_system_id'] ?>
                 </div>
             </div>
         </div>     
        <?php endif; ?>
        </div> 
</form>  

 



         