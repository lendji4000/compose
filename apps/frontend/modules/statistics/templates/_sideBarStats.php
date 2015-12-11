<?php if(isset($ei_project) && $ei_project!=null  && isset($ei_profile) && $ei_profile!=null): ?>
<!--On est dans le module scénario --> 
<?php 
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name  )
?>
    <?php
    if ($sf_request->getParameter('action') == 'stats'):
        $title = 'General statistics';
        $originalTitle='General statistics';
        endif;
        if($sf_request->getParameter('action') == 'functionsStats'): 
        $title = 'Functions statistics';
        $originalTitle='Functions statistics';
    endif;
//  Menu objet
    include_partial('global/sideBarCurrentObject',array(
    'title' =>$title, 'icon' => 'ei_stats' ,
    'originalTitle'=> $originalTitle, 'dataContent' => '')) ;  
    
//Livraisons ouvertes
  include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab))  ;
  
  endif;  ?> 
  