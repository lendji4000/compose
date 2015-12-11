<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref); 
?>
<table class="table bootstrap-datatable small-font table-condensed table-striped dataTable" id="campaignsList">
    <thead>
        <tr>
            <th> Id </th>
            <th> Title </th>
            <th> Author </th>
            <th> Description</th> 
            <th>Updated at</th>
            <th>Coverage    </th> 
        </tr> 
    </thead>
    <tbody>
        <?php if (count($ei_campaigns) > 0): ?>
            <?php foreach ($ei_campaigns as $ei_campaign): ?>
                <?php $campaignLine = $url_tab;   ?>
                <?php $campaignLine['ei_campaign'] = $ei_campaign; ?>
                <?php include_partial('eicampaign/campaignLine', $campaignLine) ?>  
            <?php endforeach; ?> 
        <?php endif; ?> 
    </tbody> 
</table> 