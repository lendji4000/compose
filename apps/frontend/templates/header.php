<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => (isset($profile_id) ? $profile_id : null),
         'profile_ref' => (isset($profile_ref) ? $profile_ref : null),
         'profile_name' => (isset($profile_name) ? $profile_name : null)
     )?>
<!-- start: Header -->
<div class="navbar" role="navigation"> 
    <div class="container-fluid">
       <?php include_partial('global/headerPart1',$url_tab); ?>    
       <?php include_partial('global/headerPart2',$url_tab); ?>   
    </div>
</div>
<!-- end: Header -->