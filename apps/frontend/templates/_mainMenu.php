<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => (isset($profile_id) ? $profile_id : null),
         'profile_ref' => (isset($profile_ref) ? $profile_ref : null),
         'profile_name' => (isset($profile_name) ? $profile_name : null)
     )?>
<!-- start: Main Menu -->
<div class="sidebar " id="eiSideBar">

    <div class="sidebar-collapse" id="eiSideBar1"> 
        <?php include_partial('global/sideBarHeader',$url_tab) ?>	
       	<?php include_partial('global/sideBarMenu',$url_tab) ?>		
    </div>
    <?php include_partial('global/sideBarFooter',$url_tab) ?>
</div>
<!-- end: Main Menu -->