<?php if(isset($ei_subjects) && count($ei_subjects)>0): ?>
<?php  $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref);
    ?>
<table class="table table-striped ">
    <thead>
        <tr>
            <th>N°</th>
            <th>Type</th>
            <th>Title</th>
            <th>Assignments</th>
            <th>State</th>
            <th>Priority</th>  
        </tr>
    </thead>
    <tbody>
        <?php if (isset($ei_subjects) && count($ei_subjects) > 0): ?> 
            <?php foreach ($ei_subjects as $ei_subject): ?>
                <?php $miniTrVars=$url_tab;
                      $miniTrVars['ei_subject']=array(
                          "subject_id" =>$ei_subject['id'],
                          "subject_name" => $ei_subject['name'],
                          "subject_state_name"=> $ei_subject['ss_name'],
                          "subject_state_color"=> $ei_subject['ss_color_code'],
                          "subject_priority_name" => $ei_subject['sp_name'],
                          "subject_type_name" => $ei_subject['type_name'],
                          "assignments" => $ei_subject['assignments'])  ?>
        <?php include_partial("eisubject/miniTr",$miniTrVars) ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<?php endif; ?>