<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);  
?> 
<?php $act=$sf_request->getParameter('action') ?>
<?php if (isset($ei_campaign)): ?>
    <div class="row" id="eisge-object">
        <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
        <h2>
            <?php echo ei_icon('ei_campaign') ?> 
                <span class="text" title="<?php echo $ei_campaign  ?>" >   
                    <strong><?php echo 'C'.$ei_campaign->getId().'/'  ?></strong>
                     <?php  echo  $ei_campaign  ?> 
                </span> 
        </h2>
    </div>

    <div class="row" id="eisge-object-actions">
        <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
             On vérifie que des actions principales ont été définies pour cet objet
        --> 
        <ul class="nav nav-tabs" role="tablist" id="campaignExecutionNavbar">
            <li class="<?php echo (($act == 'edit' || $act == 'update' || $act == 'show' ) ? 'active' : '') ?>">
                <?php  $campaign_edit=$url_params;  ?>
                <?php  $campaign_edit['campaign_id']=$ei_campaign->getId();  ?>
                <a id="accessCampaignProperties" class="btn btn-sm"
                   href="<?php echo url_for2('campaign_edit',$campaign_edit) ?>">
                    <?php echo ei_icon('ei_edit') ?>
                    <span class="text">  Properties  </span> 
                </a>
            </li>

            <li class="<?php echo ((in_array($act, array('graphHasChainedList','editContent')) ) ? 'active' : '') ?>">
                <?php  $graphHasChainedList=$url_params;  ?>
                <?php  $graphHasChainedList['campaign_id']=$ei_campaign->getId();  ?>

                <a id="accessCampaignSteps" class="btn btn-sm" href="<?php  echo url_for2('graphHasChainedList', $graphHasChainedList);?>">
                    <?php echo ei_icon('ei_campaign') ?>
                    <span class="text">&nbsp;Content</span>
                </a>
            </li>

            <li class="<?php echo (($act == 'campaignReportsIndex' || $act == 'campaignReportsShow' || $act=="statistics" ) ? 'active' : '') ?>">
                <?php  $graphHasChainedList=$url_params;  ?>
                <?php  $graphHasChainedList['campaign_id']=$ei_campaign->getId();  ?>

                <a href="<?php echo url_for2("indexCampaignExecutions", $graphHasChainedList);?>" id="accessCampaignReports" class="btn btn-sm"  >
                    <?php echo ei_icon('ei_testset') ?>
                    <span class="text">&nbsp;Reports</span>
                </a>
            </li>

        </ul> 
    </div>    

    
<?php else: //On est pas dans une campagne courante  ?>
    <div class="row" id="eisge-object"> 
        <h2>  <?php echo ei_icon('ei_campaign') ?> 
            <span class="text" title="Campaigns" >    
                <strong>Campaigns</strong>
            </span>
        </h2>
    </div>
    <div class="row" id="eisge-object-actions">  
        <ul class="nav nav-tabs" role="tablist"  >  

            <li class="<?php echo (($act == 'index') ? 'active' : '') ?>">    
                <a title="Project Campaigns" class="btn btn-sm" id="accessProjectCampaigns"
                   href="<?php echo url_for2('campaign_list', $url_params) ?>#">
                     <?php echo ei_icon('ei_list') ?> <span class="text">  List  </span>  
                </a>
            </li> 
            <li class="<?php echo (($act == 'new' || $act == 'create' ) ? 'active' : '') ?>">  
                <a id="createCampaign" class="btn btn-sm" href="<?php echo url_for2('campaign_new', $url_params) ?>">
                    <?php echo ei_icon('ei_list') ?> <span class="text">  New  campaign </span>    
                </a>
            </li> 
        </ul>

    </div>    
<?php endif; ?>
 