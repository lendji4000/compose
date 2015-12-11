<?php if(isset($project_id) && isset($profile_id) && isset($subject_id) && isset($message_type_id) && isset($type)): ?>
<?php 
$url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'subject_id' => $subject_id,
        'message_type_id' => $message_type_id,
        'parent_id' =>$parent_id,
        'ei_message_type' =>$type
    ); 
?>
<form class="form-horizontal " action="<?php echo url_for2('ei_msg_subject_add', $url_tab) ?>" method="post">
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                <input type="message" placeholder="Enter new message" class="form-control" name="ei_message_text" id="input2-group2">
                <span class="input-group-btn">
                    <button class="btn btn-success addSubjectMsg" type="submit"><i class="fa fa-comment"></i></button>
                </span>
            </div>
        </div>
    </div> 
</form>
<?php endif; ?>