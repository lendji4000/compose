<!-- Menu principal d'un user -->
<?php 
if(isset($ei_project) && $ei_project!=null  
   && isset($ei_profile) && $ei_profile!=null): 

$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ); 
?>
 
<!--On est dans le module eiuser -->  
<?php include_partial('global/sideBarCurrentObject',array(
    'title' =>'User Settings', 'icon' => 'fa-user',
    'originalTitle' =>'User Settings', 'dataContent' => ''
    )) ?>    
    <ul class="nav nav-sidebar">
        <li>
            
            <?php echo link_to2("<i class='fa fa-gear'></i><span class='text'> Define firefox path</span> ", "default", 
                    array("module" => "eiuser", "action" => "index"),
                    array("title" => "Settings")); ?>
        </li>
        <li> 
            <a href="<?php echo url_for2('setDefaultPackage',array(
                'action' =>'setDefaultPackage',
                'project_id' => $ei_project->getProjectId(),
                'project_ref' => $ei_project->getRefId())) ?>">
                 <i class="fa fa-apple"></i>
                 <span class='text'> Default package</span>
             </a>
        </li>  
    </ul> 
<hr/>
<?php include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab)) ?>  

<?php endif;  ?>

  