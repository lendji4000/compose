<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name   );
   
        $title = MyFunction::troncatedText($ei_project, 17);
        $originalTitle = $ei_project;
        include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_project',
        'originalTitle'=> $originalTitle, 'dataContent' =>'' )) ?> 


<!--Livraisons ouvertes-->

<?php   include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab))  ; ?>
 
 