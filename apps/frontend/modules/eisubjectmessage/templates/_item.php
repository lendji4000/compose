<?php if(isset($ei_subject_message) && isset($project_id) && isset($profile_id) ): ?>
<?php 

$url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'subject_id' => $ei_subject_message['subject_id'],
        'type'=> $ei_subject_message['type'],
        'message_type_id' => $ei_subject_message['message_type_id'],
        'parent_id' =>$ei_subject_message['id'], 
    ); 
?>
<?php //var_dump($ei_subject_message) ?>
<div class="panel panel-default eiPanel itemMessagePanel ">
    <div class="panel-heading">
        <h2>
            <i class="fa fa-lg fa-user"></i> 
            <?php echo $ei_subject_message['sfGuardUser']['username'].' : '.$ei_subject_message['created_at']?> 
        </h2>
        <div class="panel-actions"> 
            <a class="btn-minimize" href="#">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="panel-body itemMessages">
        <div class="itemMessage" contenteditable="true">
            <?php echo $ei_subject_message['message'] ?>
         </div>	
    </div>
    <div class="panel-footer itemMessageFooter"> 
                    <?php include_partial('eisubjectmessage/quickForm',$url_tab) ?> 
    </div>
</div> 
<?php endif; ?>