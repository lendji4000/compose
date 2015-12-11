<?php $firefoxPath = null ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<?php if ($user_settings == null or ($user_settings != null and $user_settings->getFirefoxPath() == '')) : ?>
    <?php $lienConfigureFF = link_to2("here", "default", array("module" => "eiuser", "action" => "index"), array("target" => "_blank")); ?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Firefox Path Missing</strong>: You need to configure it (<?php echo $lienConfigureFF ?>) to use the play button.
    </div>
<?php else : ?>
    <?php $firefoxPath = $user_settings->getFirefoxPath(); ?>
<?php endif; ?>

<div id="subjectContent" class="row">
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <?php echo ei_icon('ei_campaign') ?>
                <span class="text">&nbsp;Campaign Content</span>
            </h2>
            <div class="panel-actions">
                <?php
                $editCampaignContent = $url_tab;
                $editCampaignContent['campaign_id']=$ei_campaign->getId();

                $campaign_graph_refresh=$url_tab;
                $campaign_graph_refresh['campaign_id']=$ei_campaign->getId();
                $campaign_graph_refresh['execution_id'] = $selectedCampaignExecution != null && $selectedCampaignExecution->getId() != "" ? $selectedCampaignExecution->getId(): 0;
                ?>

                <a id="btnRefreshCampaignGraphStates" class="btn-default" href="#" data-url="<?php echo url_for2("campaign_graph_refresh", $campaign_graph_refresh)  ?>" title="Refresh campaign status">
                    <?php echo ei_icon('ei_refresh') ?>&nbsp;Refresh
                </a>

                <a id="editCampaignSteps" class=" btn-default" href="<?php  echo url_for2('editCampaignContent', $editCampaignContent)  ?>">
                    <?php echo ei_icon('ei_edit') ?>&nbsp;Edit Steps
                </a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div id="campaignGraphList" class="row table-responsive">
                    <?php if ($sf_user->hasFlash('delete_step_success')): ?>
                        <div  class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Well done! </strong> <?php echo $sf_user->getFlash('delete_step_success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($sf_user->hasFlash('no_oracle')): ?>
                        <div  class="alert ">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Warning. </strong> <?php echo $sf_user->getFlash('no_oracle') ?>
                        </div>
                    <?php endif; ?>

                        <input type="hidden" name="player_href_create_campaign_execution"
                               value="<?php
                               echo url_for2("createCampaignGraph", array(
                            "project_ref" => $project_ref,
                            "project_id" => $project_id,
                            "profile_ref" => $profile_ref,
                            "profile_id" => $profile_id,
                            "profile_name" => $profile_name,
                            "campaign_id" => $ei_campaign->getId() ))
                                       ?>"
                            id="player_href_create_campaign_execution" />


                        <table id="sortCampaignSteps" class="grid table bootstrap-datatable small-font dataTable" title="Campaign steps" >
                            <thead>
                                <tr>
                                    <th class="radioStart"> Start</th>
                                    <th class="campaignEnd"> End</th>
                                    <th class="index">  No. </th>
                                    <th> Details </th>
                                    <th> State </th>
                                    <th> Description </th>
                                    <th> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $last_campaign_graph=null; ?>
                                <?php if (count($ei_campaign_graphs)>0): ?>
                                <?php foreach ($ei_campaign_graphs as $ei_campaign_graph): ?>

                                <?php  $campaignGraphLine=$url_tab ;
                                $campaignGraphLine['ei_campaign_graph'] =$ei_campaign_graph ;
                                $campaignGraphLine['campaignExecutionGraphs'] =$campaignExecutionGraphs;
                                $campaignGraphLine['project_name'] =$project_name ;
                                $campaignGraphLine['firefox_path'] =$firefoxPath ;
                                $campaignGraphLine['campaignGraphBlockType'] =$campaignGraphBlockType ;
                                $campaignGraphLine['campaignExecutionGraphsKeys'] =$campaignExecutionGraphsKeys;
                                //Inclusion du partiel
                                include_partial('eicampaigngraph/campaignGraphLine',$campaignGraphLine) ?>
                                <?php $last_campaign_graph=$ei_campaign_graph ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php $campaign_graph_order_step=$url_tab ;
                                $campaign_graph_order_step['campaign_id'] =$campaign_id;?>
                        <input id="isStepAutomatizable" type="hidden"
                               itemref="<?php echo url_for2('isStepAutomatizable',$url_tab) ?>"/>
                        <input id="majStepInBase" type="hidden"
                               itemref="<?php echo url_for2('campaign_graph_order_step',$campaign_graph_order_step) ?>"/>
                    </div>
                </div>
        </div>
    </div>

<!--</div>-->

<div id="campaignStep" class="modal " tabindex="-1" role="dialog"
aria-labelledby="newCampaignStepLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="newCampaignStepLabel">Add Step</h3>
            </div>
            <div class="modal-body campaignStepBody"></div>
            <div class="modal-footer">
                <button class="btn btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveCampaignStep"
                type="submit"> <i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<div id="contextModal" class="modal " tabindex="-1" role="dialog"
aria-labelledby="contextModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="contextModalTitle"><i class="fa fa-ellipsis-h"></i> Create intervention context</h4>
            </div>
            <div class="modal-body bugContextBody"></div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveBugContextInStep"
                type="submit"> <i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div id="dialog-confirm">

</div>
 


