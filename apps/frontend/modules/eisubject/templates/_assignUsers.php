<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);  
?>
<?php if(isset($ei_subject) ): ?> 
       

<!-- Utilisateurs déjà assignés au sujet --> 
<div class="btn-group" id="alreadyAssignUsers">
    <?php if(isset($alreadyAssignUsers) && count($alreadyAssignUsers) >0): ?>
    <?php foreach ($alreadyAssignUsers as $alreadyAssignUser): ?>
    <?php $remove_user_assign_for_subject=$url_params;
    $remove_user_assign_for_subject['subject_id']=$ei_subject->getId();
    $remove_user_assign_for_subject['guard_id']=$alreadyAssignUser->getId();  ?>
    <div class="btn-group subjectAssignment">
        <a class="btn btn-success subjectAssignmentUserName">
            <i class="fa fa-user"></i> 
            <?php echo $alreadyAssignUser->getUserName() ?>
        </a> 
        <a href="<?php echo url_for2('remove_user_assign_for_subject',$remove_user_assign_for_subject) ?>" class="btn btn-danger removeSubjectAssignment">
            <?php echo ei_icon('ei_delete') ?>
        </a> 
    </div>
    <?php endforeach; ?> 
    <?php endif; ?>  
</div>
<div id="usersToAssignToSubject">
    <?php $assign_subject_to_user=$url_params;
    $assign_subject_to_user['subject_id']=$ei_subject->getId();   ?>
    <a class="addSubjectAssignment"   href="<?php echo url_for2('assign_subject_to_user',$assign_subject_to_user) ?>">   </a>
    <select class="comboboxForAssignUserToSubject ">
        <option></option>
    <?php if(isset($projectUsers) && count($projectUsers)>0): ?>
    <?php foreach ($projectUsers as $projectUser): ?>
        <option value="<?php echo $projectUser->getId() ?>">
            <?php echo $projectUser->getUsername() ?>
        </option> 
    <?php endforeach; ?> 
        <?php else : ?>
        <option><a href="#"> No user found </a></option>
    <?php endif; ?> 
    </select>
</div>
 <?php endif; ?> 