<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);   ?>
<div  id="rightSideCampaignsBloc" class="row">
    <div id="rightSideLonelyCampaignsBloc" class=" ">
        <!-- Campaign Header -->
        <div id="rightSideDeliveryHeader" class="row">
            <!--<h6>Lonely Campaigns</h6>--> 
        </div>
        <!-- Lonely Campaigns -->
        <div id="rightSideCampaignsContent" class=" ">
            <?php if(isset($ei_campaigns) && count($ei_campaigns)>0):  ?>
            <?php foreach ($ei_campaigns as $ei_campaign): ?>
            <?php if($ei_campaign->getId()!=$ei_current_campaign->getId()): ?>
            <?php $rightSideStepsListOfCampaign=$url_tab;
            $rightSideStepsListOfCampaign['ei_campaign']=$ei_campaign;
            $rightSideStepsListOfCampaign['ei_current_campaign']=$ei_current_campaign;
            include_partial('eicampaigngraph/rightSideStepsListOfCampaign',$rightSideStepsListOfCampaign) ?> 
            <?php endif; ?>
                <?php endforeach ; ?>
            <?php endif; ?>
        </div>
    </div> 
</div> 
