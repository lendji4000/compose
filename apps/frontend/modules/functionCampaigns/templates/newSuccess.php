<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name)
?>  
<div class="row"  id="administrateFunctions"> 
            <?php include_partial('form', array(
                'form' => $form,
                'ei_project' => $ei_project,
                'ei_profile' => $ei_profile,
                'kal_function' => $kal_function,)) ?>  
</div>