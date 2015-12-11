<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name )
?>   
<div class="row">  
    <?php $form_uri=$url_params ;
          $form_uri['ei_context']=$ei_context;
          $form_uri['form']=$form;
          $form_uri['guardUsersForTypeHead']=$guardUsersForTypeHead;
          $form_uri['root_folder']=$root_folder;
          $form_uri['ei_nodes']=$ei_nodes;
          include_partial ('bugContext/form',$form_uri)?>
         
</div> 