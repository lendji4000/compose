<?php if(isset($ei_project) && $ei_project!=null  && isset($ei_profile) && $ei_profile!=null): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<!--On est dans le module scénario --> 

<?php $act= $sf_request->getParameter('action')  ;
    if($act =='index' || $act =='new' || $act =='create' || $act =='searchSubjects'):
        $title = 'Interventions ';
        $originalTitle='Interventions management ';
        $data_content='';
    else :
        $title = MyFunction::troncatedText( $ei_subject,17) ;
        $originalTitle=$ei_subject;
        $data_content="Description  : ". $ei_subject->getDescription(); 
    endif;
include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_subject',
        'originalTitle'=> $originalTitle, 'dataContent' =>$data_content )) ?> 

<!--<hr/>
<ul class="nav nav-sidebar"> 
    <?php //include_partial('eisubject/basicSideBarBug',$url_tab) ?>  
</ul>-->
<!-- Liste des livraisons ouvertes dans la limite de  10 et ordonnées par date-->
<!--<hr/>--> 
<?php include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab)) ?> 
<?php endif;  ?> 
  