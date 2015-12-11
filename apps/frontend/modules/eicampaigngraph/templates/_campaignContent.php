<!-- Contenu d'une campagne (bloc principal) lors de l'édition du contenu d'une campagne -->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'campaign_id' =>$ei_campaign->getId()
     )?> 
<div id="campaignContent">
    <div class="row" id="campaignContentGlobalActions">
        <div class=" " >
            <div class="btn-group pull-right" >
                <button class="btn  btn-small active" id="collapseSteps">
                    <i class="fa fa-compress"></i>
                </button> 
                <button class="btn btn-small " id="extendSteps">
                    <i class="fa fa-expand"></i>
                </button> 
            </div>  
        </div>
        
        <input id="current_campaign_id" value="<?php echo $ei_campaign->getId() ?>" type="hidden" />
        <input id="majStepInBase" type="hidden" 
               itemref="<?php echo url_for2('campaign_graph_order_step',$url_tab) ?>"/>
    </div>
    
    <?php $last_campaign_graph = null; ?>
    <?php if (($k=count($ei_campaign_graphs)) > 0): ?> 
    <div id="stepLineInContentBoxFirst" class=""> 
        <div class="checked_place_to_add_in_camp_content " >
            <a class="add_step_link" href="#">
                <input class="lighter_in_camp_content_value" type='hidden' value="0" />
            </a>
        </div>
    </div> 
    <div id="campaignContentList">
        <?php foreach ($ei_campaign_graphs as $ei_campaign_graph): ?>
            <?php $stepLineInContent=$url_tab ;
            $stepLineInContent['ei_campaign_graph']=$ei_campaign_graph ;
            $stepLineInContent['is_lighter']=($k==1 ? true: false ); 
            include_partial('eicampaigngraph/stepLineInContent', $stepLineInContent);
            $last_campaign_graph = $ei_campaign_graph ; $k-- ; ?> 
    <?php endforeach; ?> 
    </div>    
    <?php else:  ?> 
    <div id="stepLineInContentBoxFirst" class=""> 
        <div class="checked_place_to_add_in_camp_content " id="lighter_in_camp_content" >
            <a class="add_step_link" href="#">
                <input class="lighter_in_camp_content_value" type='hidden' value="0" />
            </a>
        </div>
    </div> 
    <div id="campaignContentList"> 
    </div>
<?php endif; ?>  

<!-- Box de recherche d'une livraison-->
  
<div id="editDataSetStepBox" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="editDataSetStepBoxTitle">Select data set for step</h4> 
                <input type="hidden" id="editDataSetStepBoxLink"
                       itemref="<?php echo url_for2('getScenarioDataSets', $url_tab) ?>" />
            </div>
            <div class="modal-body" id="editDataSetStepBoxContent"></div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"> 
                    Close
                </a>
                <input id="step_scenario_id" type="hidden" value="" />
            </div>
        </div>
    </div>
</div>
</div> 
 