<!-- Menu principal d'une version de scénario -->
<?php 
if(isset($ei_project) && $ei_project!=null  
   && isset($ei_profile) && $ei_profile!=null
   && isset($ei_scenario) && $ei_scenario!=null): ?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'ei_scenario_id' => $ei_scenario_id  )
        ?>   


<?php endif; ?>
<!--On est dans le module version -->  
 
<?php  
$title = MyFunction::troncatedText( $ei_scenario,17) ;   
$originalTitle=$ei_scenario;
include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_scenario',
        'originalTitle'=> $originalTitle, 'dataContent' =>'' )) ?>  
<!--Livraisons ouvertes-->

<?php   include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab))  ; ?>

  