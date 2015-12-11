<?php if (isset($ei_project) && isset($ei_user_profile_param)): ?>

    <td class="userProfileParamCase">
        <a href="<?php
        echo url_for2('userProfileParam', array('project_id' => $ei_project->getProjectId(),
            'project_ref' => $ei_project->getRefId(), 
            'id' => $ei_user_profile_param['id'],
            'action' => 'edit'))
        ?>" class="editUserProfileParam"> 
    <?php echo $ei_user_profile_param['value']; ?> <i class="fa fa-pencil-square userProfileParamCaseIcon"> </i>
        </a>
    </td>

<?php endif; ?>