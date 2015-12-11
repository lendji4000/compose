<?php if ($sf_user->isAuthenticated()): ?>

<div class="sidebar-menu" id="eiSideBar12">  
     <?php  
     if ($project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null) : 
     $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name )?>    
    <div class=" " id="eiSideBar121MainObjects"> 
        <a title="Project dashboard" class="btn btn-link mainButtonAccess dashboardIcon" id="eiProjectDashboardMainAccess"
           href="<?php echo url_for2('projet_show', $url_tab) ?>#" class="btn btn-link">
             
            <?php echo ei_icon('ei_dashboard', 'lg') ?>
        </a> 
        <a title="Project Campaigns" class="btn btn-link mainButtonAccess campaignIcon" id="eiProjectCampaignsMainAccess"
           href="<?php  echo url_for2('campaign_list',$url_tab) ?>#" class="btn btn-link"> 
            <?php echo ei_icon('ei_campaign', 'lg') ?>
        </a> 
        <?php $projet_eiscenario=$url_tab  ;
        $projet_eiscenario['action']='index' ?>
        <a title="Scenarios" class="btn btn-link mainButtonAccess scenarioIcon" id="eiProjectScenariosMainAccess"
           href="<?php  echo url_for2('projet_eiscenario',$projet_eiscenario) ?>#">
             <?php echo ei_icon('ei_scenario','lg') ?>
        </a>
        <?php $functionList=$url_tab  ;
        $functionList['action']='index' ?>
        <a title="Functions" class="btn btn-link mainButtonAccess functionIcon" id="eiProjectFunctionsMainAccess"
           href="<?php  echo url_for2('functionList',$functionList) ?>#"> 
            <?php echo ei_icon('ei_function', 'lg') ?>
        </a> 
        <?php $stats=$url_tab  ;
        $stats['action']='stats' ?>
        <a title="General statistics" class="btn btn-link mainButtonAccess generalStatsIcon" id="eiProjectStatsMainAccess"
           href="<?php  echo url_for2('generalStats',$stats) ?>#"> 
            <?php echo ei_icon('ei_stats', 'lg') ?>
        </a> 
    </div>  
    <!--<hr/>-->
    <?php include_partial('global/sideBarObject', $url_tab) ?>
    <?php endif; ?>
</div>
<?php endif; ?>

