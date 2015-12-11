<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref  ); 
$form_uri=$url_tab;
$form_uri['subject_id']=$subject_id;
$form_uri['form']=$form;?>
<div class="row" id="subjectContent"> 
 <?php include_partial('form', $form_uri) ?>  
</div> 

