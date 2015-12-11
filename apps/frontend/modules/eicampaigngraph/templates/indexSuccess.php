<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ); 
        ?>
<div class="row"> 
        
        
        <?php if(count($ei_campaign_graphs) ==0) : ?>
        <?php $campaign_graph_new=$url_tab ?>
        <?php $campaign_graph_new['campaign_id']=$campaign_id; ?>
        <?php $campaign_graph_new['parent_id']=0; ?>
        <a href="<?php echo url_for2('campaign_graph_new',$campaign_graph_new) ?>">
            Create graph root
          </a>   
        <?php endif; ?>
        <?php if(isset($ei_campaign_root)): ?>
        <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="well well-sm">
                  <div>
                      <ul class="nav nav-list">
                      <?php $graphLine=$url_tab; ?>
                      <?php $graphLine['graph_node']=$ei_campaign_root; ?>
                      <?php include_partial('eicampaigngraph/graphLine',$graphLine) ?> 
                      </ul>
                  </div>
              </div>
            </div>
          </div>
        <?php endif; ?> 
</div> 
 

  
