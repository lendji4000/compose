<?php if (isset($ei_subject) && isset( $guard_user) && isset($project_id) && isset($project_ref) ) : ?>
<div class="btn-group subjectAssignment">
    <a class="btn btn-success subjectAssignmentUserName">
        <i class="fa fa-user"></i> 
        <?php echo $guard_user->getUserName() ?>
    </a> 
    <a href="<?php echo url_for2('remove_user_assign_for_subject',
                array('subject_id' => $ei_subject->getId(),
                      'guard_id' =>$guard_user->getId(),
                      'project_id' => $project_id,
                      'project_ref' => $project_ref,
                      'profile_id' => $profile_id,
                      'profile_ref' => $profile_ref ,
                      'profile_name' => $profile_name)) ?>"
                      class="btn btn-danger removeSubjectAssignment">
        <?php echo ei_icon('ei_delete') ?>
    </a>
</div>
<?php endif ; ?>