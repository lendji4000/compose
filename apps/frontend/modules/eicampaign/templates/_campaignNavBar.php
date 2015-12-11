<div id="campaignHeader"> 
    <div class="navbar" > 
        <div class="navbar-inner" >  
            <ul class="nav" >
                <li >
                    <a href="#" class="navbar-brand">
                        <small>
                            <?php echo ei_icon('ei_campaign') ?> &nbsp; 
                            <?php 
                            echo (isset($ei_campaign) && $ei_campaign!=null)?
                            $ei_campaign->getId().'/'.MyFunction::troncatedText($ei_campaign, 17):'Campaigns' 
                                    ?>
                        </small> 
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <li class="<?php if(isset($activeItem) && $activeItem=='Properties'): echo 'active' ; endif; ?>">
                     
                    <a id="accessCampaignProperties" href="<?php echo url_for2('campaign_edit',array(
                        'id'=> $ei_campaign->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref)) ?>">
                        <i class="fa fa-wrench "></i> Properties 
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <li class="<?php if(isset($activeItem) && $activeItem=='EditSteps'): echo 'active' ; endif; ?>">
                     
                    <a id="editCampaignSteps" href="<?php echo url_for2('editCampaignContent',array(
                        'campaign_id'=> $ei_campaign->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref)) ?>">
                        <?php echo ei_icon('ei_edit') ?> Edit steps 
                    </a>
                </li> 
                <li class="divider-vertical"></li> 
                <li class="<?php if(isset($activeItem) && $activeItem=='TestSuites'): echo 'active' ; endif; ?>">
                    <a id="accessCampaignSteps" href="<?php
                        echo url_for2('graphHasChainedList', array(
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'campaign_id' => $ei_campaign->getId()))
                        ?>">
                            <?php echo ei_icon('ei_scenario') ?> Scenarios
                    </a>
                </li>
                <li class="divider-vertical"></li>
            </ul> 
        </div>
    </div>
</div>
