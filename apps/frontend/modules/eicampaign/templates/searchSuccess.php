<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name)
?>
<?php
$searchBox = $url_params; 
$searchBox['campaignSearchForm'] = $campaignSearchForm;
$searchBox['campaignAuthors'] = $campaignAuthors;
?>
<?php include_partial('searchBox',$searchBox)    ?>    

<div id="campaignList" class="row">
    <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_list') ?> List </h2>
                <div class="panel-actions"> 
                </div>
            </div>

            <div class="panel-body table-responsive" >
                <table class="table table-bordered table-condensed table-striped dataTable">
                    <thead>
                        <tr>
                            <th> Id </th>
                            <th>  Title </th>
                            <th> Author </th>
                            <th>Description</th> 
                            <th>Updated at</th>
                            <th>Coverage</th> 
                        </tr> 
                    </thead>
                    <tbody>
                        <?php if (count($ei_campaigns)>0): ?>
                        <?php foreach ($ei_campaigns as $ei_campaign): ?>
                        <?php  $campaignLine = $url_params; 
                                $campaignLine['ei_campaign'] = $ei_campaign;  ?>
                        <?php include_partial('eicampaign/campaignLine',$campaignLine) ?> 
                        <?php endforeach; ?> 
                        <?php endif; ?> 
                    </tbody> 
                </table>
            </div>
        <div class="panel-footer">
            <a class="pull-right" 
                href="<?php echo url_for2('campaign_new', $url_params) ?>">
                <?php echo ei_icon('ei_add') ?> Add
            </a>
            <?php $pagerMenu = $url_params; 
            $pagerMenu['current_page'] = $current_page;
            $pagerMenu['nb_pages'] = $nb_pages;
            $pagerMenu['nbEnr'] = $nbEnr;
            $pagerMenu['max_campaign_per_page'] = $max_campaign_per_page;

            include_partial('eicampaign/pagerMenu',$pagerMenu); ?> 
        </div>
    </div> 
    
    

</div> 
 
