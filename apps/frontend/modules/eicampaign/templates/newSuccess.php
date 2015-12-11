<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name )
?>
<?php
$url_form=$url_params;
$url_form['form']=$form ?>
<div class="row">  
    <?php include_partial('form', $url_form) ?> 
</div>