<!-- Ligne d'une campagne de livraison -->
<?php if(isset($ei_campaign) && isset($project_id) && isset($project_ref)): ?>
<div class='row'>
    <div class='row'>
        <div class='col-lg-3 col-md-3'>
            <a href="#"><?php echo $ei_campaign->getEiCampaign()->getName() ?></a>
        </div>
        <div class="col-lg-7 col-md-7">
            <div class="btn-group pull-right showCampaignGraphForDelivery">
                <a href="<?php echo url_for2('getCampaignGraphForDelivery',array(
                     'campaign_id' => $ei_campaign->getEiCampaign()->getId(),
                     'delivery_id' => $ei_campaign->getDeliveryId(),
                     'project_id' => $project_id,
                     'project_ref' => $project_ref
                    )) ?>"> <i class="icon icon-chevron-down"></i> Graph
                </a>
            </div>
        </div>
    </div>
    <div class='row deliveryCampaignGraph'>
        <div class='col-lg-1 col-md-1'>
            
        </div>
        <div class='col-lg-11 col-md-11'>
            <?php 
//            include_partial('campaignGraph/getGraph',
//                    array('ei_campaign' => $ei_campaign->getEiCampaign())) 
                    ?>
        </div>
    </div>
</div>
<?php endif; ?>

