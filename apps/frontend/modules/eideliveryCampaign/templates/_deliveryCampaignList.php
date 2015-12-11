<!-- Liste des campagnes de tests d'une livraison-->
<?php if(isset($ei_delivery) && isset($project_id) && isset($project_ref)): ?>
<h4>Delivery Campaigns </h4>
    <hr/>
<div id='deliveryCampaignsList'>
    <?php if(isset($ei_delivery_campaigns) && count($ei_delivery_campaigns)>0): ?>
    <?php foreach ($ei_delivery_campaigns as $ei_delivery_campaign): ?>
    <?php include_partial('eideliveryCampaign/deliveryCampaign',array(
        'ei_campaign' => $ei_delivery_campaign,
        'ei_delivery' => $ei_delivery,
        'project_id' => $project_id,
        'project_ref' => $project_ref
    )) ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php else: ?>
<?php endif; ?>

