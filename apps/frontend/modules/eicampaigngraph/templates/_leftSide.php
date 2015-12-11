<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);  ?>

<div class="accordion" id="campaignAccordion">

    <div class="accordion-group"   >
        <div class="accordion-heading"> 
            <a href="<?php echo url_for('eicampaign/edit?id=' . $ei_campaign->getId()) ?>">
                Campaign properties <i class="fa-chevron-right pull-right"></i>
            </a>
        </div> 
    </div> 
    <div class="accordion-group"   >
        <div class="accordion-heading"> 
            <?php $campaign_graph=$url_tab;
            $campaign_graph['campaign_id']= $ei_campaign->getId();   ?>
            <a href="<?php  echo url_for2('campaign_graph',$campaign_graph)  ?>">
                Graph <i class="fa-chevron-right pull-right"></i>
            </a>
        </div> 
    </div> 
</div>
