<div id="campaignSubHeader">

    <div class="navbar">
        <div class="navbar-inner">
            <ul class="nav">
                <li id="liPlayAllCampaign">
                    <div class="text"> Play
                        <a href="#" title="Play campagne" id="btnPlayCampagneInIde" data-player-id="<?php echo $ei_campaign->getId(); ?>"
                           data-player-start="-1" data-player-end="-1">
                            <i class="fa fa-play fa-lg"></i>
                        </a>
                    </div>
                </li>

                <li class="divider-vertical"></li>

                <li id="liOnError">
                    <?php  $bloc_type=$ei_campaign->getEiBlockType();  ?>
                    <?php if(isset($campaignGraphBlockType)): ?>

                        <label class="col-lg-5 col-md-5">On Error : </label>

                        <select class="CampaignBlockType col-lg-7 col-md-7" name="CampaignBlockType">
                            <?php foreach($campaignGraphBlockType as $blockType): ?>
                                <option value="<?php echo $blockType->getId() ?>"
                                    <?php if ($bloc_type!=null && $bloc_type->getId()==$blockType->getId()): ?>
                                        selected="selected"
                                    <?php endif; ?>
                                        itemref="<?php echo url_for2('changeBlocTypeId', array(
                                            'project_id' => $project_id,
                                            'project_ref' => $project_ref,
                                            'id' => $ei_campaign->getId(),
                                            'block_type_id' => $blockType->getId()))  ?>">
                                    <?php echo $blockType->getName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </li>

                <li class="divider-vertical"></li>

                <li id="liCampaignExecutions">
                    <?php if(isset($campaignExecutions)): ?>

                        <?php
                        if( $selectedCampaignExecution!= null ){
                            $selectedItemExecution = $selectedCampaignExecution->getId();
                        }
                        else{
                            $selectedItemExecution = "";
                        }
                        ?>

                        <label class="col-lg-4 col-md-4">Execution : </label>

                        <select class="campaignExecution col-lg-8 col-md-8" name="CampaignExecution">
                            <option value="" <?php if( "" == $selectedItemExecution ){ ?>selected="selected"<?php } ?>
                                itemref="<?php echo url_for2("graphHasChainedList", array(
                                    'project_id' => $project_id,
                                    'project_ref' => $project_ref,
                                    'campaign_id' => $ei_campaign->getId()
                                )) ?>">
                                New Execution
                            </option>

                            <?php foreach($campaignExecutions as $execution): ?>
                                <option value="<?php echo $execution->getId() ?>" <?php if( $execution->getId() == $selectedItemExecution ){ ?>selected="selected"<?php } ?>
                                    itemref="<?php echo url_for2('executionGraphHasChainedList', array(
                                        'project_id' => $project_id,
                                        'project_ref' => $project_ref,
                                        'campaign_id' => $ei_campaign->getId(),
                                        'execution_id' => $execution->getId()
                                    )) ?>">
                                    <?php echo $execution; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </li>

                <li class="divider-vertical"></li>

                <li>
                    <a href="#" id="btnResetCampaignGraphStates" data-url="<?php echo url_for2("campaign_graph_resetting", array(
                        'campaign_id'=> $ei_campaign->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'execution_id' => $selectedItemExecution != "" ? $selectedItemExecution:0
                    )) ?>" title="Reset campaign status" id="btnCampaignResetStatus">
                        <?php echo ei_icon('ei_delete') ?> Reset Status
                    </a>
                </li>

                <li class="divider-vertical"></li>

                <li>
                    <a href="#" id="btnRefreshCampaignGraphStates" data-url="<?php echo url_for2("campaign_graph_refresh", array(
                        'campaign_id'=> $ei_campaign->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'execution_id' => $selectedItemExecution != "" ? $selectedItemExecution:0
                    )) ?>" title="Refresh campaign status">
                        <i class="fa fa-refresh"></i> Refresh Status
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>