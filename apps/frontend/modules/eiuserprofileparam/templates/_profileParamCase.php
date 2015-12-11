<?php if (isset($ei_project) && isset($param)): ?>

    <td class="userProfileParamCase">
        <a href="<?php
        echo url_for2('userProfileParamAction', array('project_id' => $ei_project->getProjectId(),
            'project_ref' => $ei_project->getRefId(),
            'profile_id' => $param['profile_id'],
            'profile_ref' => $param['profile_ref'],
            'id' => $param['id'],
            'action' => 'createAndEdit'))
        ?>" class="editUserProfileParam"> 
    <?php echo $param['value']; ?> <i class="fa fa-pencil-square userProfileParamCaseIcon"> </i>
        </a>
    </td>

<?php endif; ?>