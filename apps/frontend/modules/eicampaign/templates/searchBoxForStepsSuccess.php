<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name )
?>   
<div id="campaignList" class="row">
    <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_list') ?> List </h2>
                <div class="panel-actions"> 
                </div>
            </div>

            <div class="panel-body table-responsive" >
                <table class="table small-font bootstrap-datatable table-condensed table-striped dataTable" id="campaignListTableBox">
                    <thead>
                        <tr>
                            <th> Id </th>
                            <th> Title </th>
                            <th> Author </th>
                            <th> Description</th>
                            <th>Updated at</th>
                            <th>Coverage</th> 
                        </tr> 
                    </thead>
                    <tbody>
                        <?php if (count($ei_campaigns)>0): ?>

                        <?php foreach ($ei_campaigns as $ei_campaign): ?>
                            <?php $campaignLine=$url_tab ?>
                                <?php $campaignLine['ei_campaign']=$ei_campaign; ?>
                                <?php $campaignLine['is_ajax_request']=true; ?>
                             <?php include_partial('eicampaign/campaignLine',$campaignLine) ?>  
                        <?php endforeach; ?> 
                        <?php endif; ?> 
                    </tbody> 
                </table>
            </div>
        <div class="panel-footer">
            <?php $pagerMenu = $url_tab;
            $pagerMenu['current_page'] = $current_page;
            $pagerMenu['nb_pages'] = $nb_pages;
            $pagerMenu['nbEnr'] = $nbEnr;
            $pagerMenu['max_campaign_per_page'] = $max_campaign_per_page;
            $pagerMenu['is_ajax_request'] = true;
            include_partial('eicampaign/pagerMenu', $pagerMenu);  ?> 
        </div>
    </div> 
</div> 
 

