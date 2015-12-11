<?php if(isset($ei_delivery) && isset($project_id) && isset($project_ref) ):?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 


 <div class="panel panel-default eiPanel" id="campaignList">
    <div class="panel-heading">
        <h2> 
            <?php echo ei_icon('ei_campaign') ?>
            <span class="break"></span> 
            Delivery campaigns (<?php echo (isset($ei_campaigns) &&(count($ei_campaigns)>0)?count($ei_campaigns):0) ?>) 
        </a>
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    <div class="panel-body table-responsive">  
            <table class="table small-font bootstrap-datatable table-condensed table-striped dataTable" id="EiPaginateList">
        <thead>
            <tr>
                <th> Id </th>
                <th>Title </th>
                <th>Author  </th>
                <th>Description</th> 
                <th>Updated at</th>
                <th>Coverage</th> 
            </tr> 
        </thead>
        <tbody>
            <?php if (isset($ei_campaigns) && count($ei_campaigns)>0): ?>
            <?php foreach ($ei_campaigns as $ei_campaign):   ?>
            <?php $campaignLine =$url_tab;
                  $campaignLine['ei_delivery']=$ei_delivery;
                  $campaignLine['ei_campaign']=$ei_campaign->getEiCampaign();
                  ?>
            <?php include_partial('eicampaign/campaignLine',$campaignLine) ?>
            <?php endforeach; ?> 
            <?php endif; ?> 
        </tbody> 
    </table> 
    </div>
     <div class="panel-footer">  
            <?php $addNewDeliveryCampaign=$url_tab;
                  $addNewDeliveryCampaign['delivery_id']=$ei_delivery->getId(); 
                  ?>
            <a class="btn btn-sm btn-success eiBtnAdd" href="<?php echo url_for2('addNewDeliveryCampaign', $addNewDeliveryCampaign) ?>">
                <?php echo ei_icon('ei_add') ?> Add
            </a>  
    </div>
</div> 
 

<?php endif; ?>

