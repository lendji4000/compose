<?php if(isset($ei_function) && $ei_tree): ?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         "function_id" => $ei_function->getFunctionId(),
         "function_ref" => $ei_function->getFunctionRef());

//Urls de gestion des propriétes de la fonction
$tab_properties = $url_tab; 
$tab_properties['action']='show';
$url_properties= url_for2('showFunctionContent',$tab_properties  );
//Url de gestion des statistiques de la fonction
$stat_tab= $url_tab; $stat_tab['action']='statistics';
$url_stat= url_for2('showFunctionContent',$stat_tab  );
//Url de gestion des campagnes de la fonction
$tab_campaigns = $url_tab; $tab_campaigns['action']='index';
$url_campaigns= url_for2('showFunctionCampaigns',$tab_campaigns  );
//Url de gestion des sujets de la fonction
$tab_subjects = $url_tab; $tab_subjects['action']='getFunctionSubjects';
$url_subjects= url_for2('subjectFunction',$tab_subjects  );
//Url de gestion des scénarios de la fonction
$tab_scenarios = $url_tab; $tab_scenarios['action']='scenariosFunction';
$url_scenarios= url_for2('scenariosFunction',$tab_scenarios  );
//Url de gestions des statistiques de fonction
$tab_stats = $url_tab; $tab_stats['action']='statistics';
$tab_stats_uri= url_for2('functionActions',$tab_stats  );
?> 

<h3 id="nodeDetailsModalLabel">
    <ul class="nav nav-tabs">
        <li role="functionName" class="active">
            <a  href="#" title="<?php echo $ei_tree ?>"><?php  echo ei_icon('ei_function')." ".MyFunction::troncatedText($ei_tree, 40)?></a>
        </li>
        <li role="Properties"><a title="Function properties" target="_blank" href="<?php echo $url_properties ?>"><?php echo ei_icon("ei_properties") ?></a></li>
        <li role="Campaigns"><a title="Function campaigns"  target="_blank" href="<?php echo $url_campaigns ?>"><?php echo ei_icon("ei_campaign") ?></a></li>
        <li role="Bugs"><a title="Function bugs"  target="_blank" href="<?php echo $url_subjects ?>"><?php echo ei_icon("ei_subject") ?></a></li>
        <li role="Scenarios"><a title="Function scenarios"  target="_blank"  href="<?php echo $url_scenarios ?>"><?php echo ei_icon("ei_scenario") ?></a></li>
        <li role="Reports"><a title="Function reports"  target="_blank" href="<?php echo $tab_stats_uri ?>"><?php echo ei_icon("ei_report") ?></a></li>
    </ul>
</h3>
<?php endif;?>