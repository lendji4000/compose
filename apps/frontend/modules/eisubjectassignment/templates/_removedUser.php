<?php if (isset($ei_subject) && isset( $guard_user)) : ?>
<div class="btn-group">
    <a href="<?php
    echo url_for2('assign_subject_to_user', array('subject_id' => $ei_subject->getId(),
        'guard_id' => $guard_user->getId(),
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref ,
        'profile_name' => $profile_name))
    ?>" 
       class="btn btn-success btn-sm addSubjectAssignment">
        <i class="fa fa-user"></i>
<?php echo $guard_user->getUserName() ?> 
        <?php echo ei_icon('ei_add' ) ?>
    </a> 
</div> 
<?php endif ; ?>