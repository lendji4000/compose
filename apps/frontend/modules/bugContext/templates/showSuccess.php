<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_bug_context_form' )) ?> 
<div class="row">  
        <?php include_partial ('bugContext/show',array(
            'project_id'=> $project_id,
            'project_ref'=> $project_ref,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'profile_name' => $profile_name,
            'ei_context'=> $ei_context
        ))?> 
</div> 

