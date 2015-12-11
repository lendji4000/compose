<?php if(isset($ei_project) && $ei_project!=null  && isset($ei_profile) && $ei_profile!=null): ?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name 
     )?> 
<!--On est dans le module scénario --> 
<?php $act= $sf_request->getParameter('action')  ;
    if($act=='index' || $act=='create' || $act=='new'): 
        $title = 'Project Scenarios ';
        $originalTitle='Project Scenarios ';
    else :
        $title = MyFunction::troncatedText($ei_scenario, 17);
        $originalTitle=$ei_scenario;
    endif;
   include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_scenario',
        'originalTitle'=> $originalTitle, 'dataContent' => '' )) ?>  
  
<ul class="nav nav-sidebar">
    <?php if($act=='index' || $act=='create' || $act=='new'): ?>
    <li class="active">   
        <a href="#new-open" data-toggle="tab" class="" id="OpenOrCreateScenario" title="Open or create scenario Campaigns" >
             <?php echo ei_icon('ei_scenario') ?>
            <span class="text"><small>New / Open</small>  </span>
        </a> 
    </li> 
    <li>   
        <a title="Recents scenarios"  id="viewRecentsScenarios"
           href="#recent" data-toggle="tab">
             <?php echo ei_icon('ei_scenario') ?>
            <span class="text">  <small>Recents</small> </span>
        </a>
    </li> 
    <?php else : ?>  
 
    <?php endif; ?>
</ul> 
<!--Livraisons ouvertes-->
<?php   include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab))  ; ?>

<?php endif;  ?>