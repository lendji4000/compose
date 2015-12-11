<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'form' => $form
     )?>
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_version_form' )) ?>
<div class="row"> 
        <?php include_partial('form', $url_tab) ?> 
</div>