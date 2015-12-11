<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
        )
?> 
<?php
$partialurl = $url_params;
$partialurl['form'] = $form;
$partialurl['subject_id'] = $subject_id;
?>
<div id="subjectContent" class="row">
<?php include_partial('form', $partialurl) ?> 
</div>

