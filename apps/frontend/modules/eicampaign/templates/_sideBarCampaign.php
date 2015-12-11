<?php if(isset($ei_project) && $ei_project!=null  && isset($ei_profile) && $ei_profile!=null): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name );
if(isset($ei_delivery) && $ei_delivery!=null)  : //Campagne d'une livraison
    $delivery_id=$ei_delivery->getId(); 
    $url_tab['delivery_id']=$delivery_id;
endif; 
if(isset($ei_subject) && $ei_subject!=null)  : //Campagne d'un sujet
    $subject_id=$ei_subject->getId();
    $url_tab['subject_id']=$subject_id;
endif; 
?> 
<?php $act= $sf_request->getParameter('action') ?>
<!--On est dans le module eicampaign --> 
 
<?php $act= $sf_request->getParameter('action')  ;
    if($act =='index' | $act =='new' || $act =='create'):
        $title = 'Project Campaigns ';
        $originalTitle='Project Campaigns ';
        $data_content='';
    else :
        $title = MyFunction::troncatedText( $ei_campaign,17) ;
        $originalTitle=$ei_campaign;
        $data_content="Coverage  : ". $ei_campaign->getCoverageAsString(); 
    endif;
include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_campaign',
        'originalTitle'=> $originalTitle, 'dataContent' =>$data_content )) ?> 





<!-- Liste des livraisons ouvertes dans la limite de  10 et ordonnées par date-->

<?php include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab)) ?>   
<?php endif; ?> 
  