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
    if ($sf_request->getParameter('action') == 'index'):
        $title = 'Project Functions';
        $originalTitle='Project Functions';
    else :
        $title = MyFunction::troncatedText($kal_function, 17);
        $originalTitle=$kal_function;
    endif;
//  Menu objet
    include_partial('global/sideBarCurrentObject',array(
    'title' =>$title, 'icon' => 'ei_function' ,
    'originalTitle'=> $originalTitle, 'dataContent' => '')) ;  
    
//Livraisons ouvertes
  include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab))  ;
  
  endif;  ?> 
  