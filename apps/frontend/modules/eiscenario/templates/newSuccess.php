 
<?php

include_partial('form', array('form' => $form,
    'defPack' => (isset($defPack) ? $defPack : null),
    'root_id' => $root_id,
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name))
?>


