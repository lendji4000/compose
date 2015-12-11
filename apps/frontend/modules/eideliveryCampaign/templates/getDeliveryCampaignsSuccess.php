<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_campaign_form' )) ?>
<div class="row"> 
    <?php 
    $campaignList=$url_tab;
    $campaignList['ei_delivery']=$ei_delivery;
    $campaignList['ei_campaigns']=$ei_delivery_campaigns;
    ?>
        <?php include_partial('eideliveryCampaign/campaignList',$campaignList) ?>  
</div>