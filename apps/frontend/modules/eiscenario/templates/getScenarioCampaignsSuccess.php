<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref); 
?>
<div class="panel panel-default eiPanel" id="testSuiteCampaigns">
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_campaign') ?> Scenario campaigns </h2>
                <div class="panel-actions"> 
                </div>
            </div>

            <div class="panel-body table-responsive" >
                <?php 
                    $scenarioCampaignsUri=$url_tab;  
                    $scenarioCampaignsUri['scenario_id']=$ei_scenario->getId();
                    $scenarioCampaignsUri['ei_campaigns']=$scenarioCampaigns; ?> 
                 <?php include_partial('eicampaign/list',$scenarioCampaignsUri) ?>
            </div>
        <div class="panel-footer"> 
        </div>
    </div>

