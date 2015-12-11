<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<?php
$subject_solution_edit = $url_params;
$subject_solution_edit['id'] = $ei_subject_solution->getId();
$subject_solution_edit['subject_id'] = $ei_subject_solution->getSubjectId();
$subject_solution_edit['action'] = 'edit';
?>
<?php
$eisubjectattachment_list = $url_params;
$eisubjectattachment_list['subjectAttachments'] = $subjectAttachments;
$eisubjectattachment_list['form'] = $newAttachForm;
$eisubjectattachment_list['subject_id'] = $subject_id;
?>
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_form' )) ?> 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_solution_form' )) ?> 
<div id="subjectContent" class="row">    
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <i class="fa fa-lightbulb-o"></i>
                <span class="break"></span> Intervention Solution
            </h2>
            <div class="panel-actions"> 
                <a class="btn-default" 
                   href="<?php echo url_for2('subject_solution_edit', $subject_solution_edit) ?>"> 
                   <?php echo ei_icon('ei_edit') ?>
                </a>
            </div>
        </div>
        <div class="panel-body">  
            <div class="  col-lg-12 col-md-12" contenteditable="false" designMode="on">
            <?php echo html_entity_decode($ei_subject_solution->getSolution(), ENT_QUOTES, "UTF-8") ?>
            </div>
        </div>
    </div>
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <i class="fa fa-upload"></i>
                <span class="break"></span>  Solution attachments
            </h2>
            <div class="panel-actions">
                <a href="#uploadAttachment" role="button" class="btn-default" data-toggle="modal">
                    <?php echo ei_icon('ei_add' ) ?>
                </a> 
            </div>
        </div>
        <div class="panel-body">   
            <?php   include_partial('eisubjectattachment/list', $eisubjectattachment_list) ?> 
        </div>
    </div>
    
    
    
    
</div>

