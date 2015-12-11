<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<div id="menu" class="col-lg-3 col-md-3 marge-none">
     
    <?php $menu=$urlParams; 
                        $menu['ei_project']=$ei_project;   
                        include_partial('global/menu', $menu); ?>  
</div>

<div id="corps" class="col-lg-9 col-md-9">
    
</div>