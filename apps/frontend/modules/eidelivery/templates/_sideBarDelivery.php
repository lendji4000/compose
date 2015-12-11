<?php if(isset($ei_project) && $ei_project!=null  && isset($ei_profile) && $ei_profile!=null): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<!--On est dans le module eidelivery -->  
<?php $act= $sf_request->getParameter('action')  ;
    if($act =='index' || $act =='new' || $act=='create' || $act =='searchDeliveries'):
        $title = 'Project Deliveries ';
        $originalTitle='Project Deliveries ';
        $data_content='';
    else :
        $title = MyFunction::troncatedText( $ei_delivery,17) ;
        $originalTitle=$ei_delivery;
        $data_content="Delivery date : ". $ei_delivery->getDeliveryDate(); 
    endif;
include_partial('global/sideBarCurrentObject',array(
       'title' =>$title, 'icon' => 'ei_delivery',
        'originalTitle'=> $originalTitle, 'dataContent' =>$data_content )) ?>   



<!--<ul class="nav nav-sidebar">
    <?php //$action =$sf_request->getParameter('action') ; ?> 
    <?php //include_partial('eisubject/basicSideBarBug',$url_tab) ?> 
</ul>
<hr/>--> 
 
<?php include_partial('global/openDeliveries',array(
    'openDeliveries' => $openDeliveries,
    'delivery_show_uri' => $url_tab)) ?>  

<?php endif;  ?> 
  