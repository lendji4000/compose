<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
        )
?>  
<?php
$createSubjectCampaign = $url_params; 
$createSubjectCampaign['subject_id'] = $ei_subject->getId();
$createSubjectCampaign['action'] = 'new';
?>
<?php
$campaignLine = $url_params;  
$campaignLine['ei_subject'] = $ei_subject;
?>   
 
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_campaign_form' )) ?> 
<div id="subjectContent" class="row">
    <div class="panel panel-default eiPanel" id="subjectCampaignsList">
        <div class="panel-heading">
            <h2>
                <?php echo ei_icon('ei_campaign') ?>
                <span class="break"></span>  
                Intervention campaigns  (<?php echo (isset($ei_subject_campaigns) &&(count($ei_subject_campaigns)>0)?count($ei_subject_campaigns):0) ?>)
            </h2>
            <div class="panel-actions">  
            </div>
        </div>
        <div class="panel-body table-responsive">   
            <table class="table bootstrap-datatable small-font table-condensed table-striped dataTable" id="EiPaginateList">
                <thead>
                    <tr>
                        <th>   Id </th>
                        <th>   Title </th>
                        <th>  Author </th>
                        <th>Description</th>
                        <th>Updated at</th>
                        <th>Coverage</th> 
                    </tr>  
                </thead>
                <tbody>
                    <?php if (count($ei_subject_campaigns) > 0): ?>
                        <?php foreach ($ei_subject_campaigns as $ei_subject_campaign): ?>
                            <?php $campaignLine['ei_campaign'] = $ei_subject_campaign->getEiCampaign(); ?>
                            <?php  include_partial('eicampaign/campaignLine',$campaignLine) ?> 
                    <?php endforeach; ?> 
                    <?php endif; ?> 
                </tbody>  
            </table>
        </div>
        <div class="panel-footer">
            <a class="btn btn-success btn-sm" 
               href="<?php echo url_for2('createSubjectCampaign', $createSubjectCampaign)  ?>">
                <?php echo ei_icon('ei_add' ) ?> Add 
            </a> 
        </div>
    </div>   
</div> 