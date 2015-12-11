<?php if(isset($kal_function)): ?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         "function_id" => $kal_function->getFunctionId(),
         "function_ref" => $kal_function->getFunctionRef());

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

$tab_params = $url_tab; $tab_stats['action']='index';
$tab_params_uri= url_for2('functionParamsActions',$tab_stats  );

$tab_notices = $url_tab; $tab_stats['action']='index';
$tab_notices_uri= url_for2('functionNoticesActions',$tab_stats  );
?> 


<div class="row" id="eisge-object">
    <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
    <h2> 
        <?php echo ei_icon('ei_function') ?> 
                <span class="text" title="<?php echo $kal_function   ?>" >   
                    <strong><?php echo 'F'.$kal_function->getConcatId().'/'  ?></strong>
                     <?php  echo $kal_function  ?> 
                </span> 
    </h2> 
</div>
<div class="row" id="eisge-object-actions">
    <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
         On vérifie que des actions principales ont été définies pour cet objet
    --> 
     
    <ul class="nav nav-tabs" role="tablist"> 
        <li class="<?php if(isset($activeItem) && ($activeItem=='show')): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $url_properties ?>" id="accessFunctionProperties" title="Function properties"> 
                <i class='fa fa-wrench '></i> &nbsp;   Properties 
            </a> 
        </li> 
        <li class="<?php if(isset($activeItem) && ($activeItem=='functionCampaigns')): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $url_campaigns ?>"  id="accessFunctionCampaigns" title="Function campaigns"> 
                <?php echo ei_icon('ei_campaign') ?> Campaigns 
            </a>
        </li> 
        <li class="<?php if(isset($activeItem) && ($activeItem=='functionSubjects' )): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $url_subjects ?>"  id="accessFunctionSubjects" title="Function interventions">
                <?php echo ei_icon('ei_subject') ?> Interventions
            </a>
        </li>
        <li class="<?php if(isset($activeItem) && ($activeItem=='scenariosFunction' )): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $url_scenarios ?>"  id="accessScenariosFunction" title="Scenarios">
                <?php echo ei_icon('ei_scenario') ?> Scenarios
            </a>
        </li>
        <li class="<?php if(isset($activeItem) && ($activeItem=='statisticsFunction' )): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $tab_stats_uri ?>"  id="accessStatisticsFunction" title="Function reports"> 
                <?php echo ei_icon('ei_report') ?>  Reports
            </a>
        </li>
        <li class="<?php if(isset($activeItem) && ($activeItem=='functionParameters' )): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $tab_params_uri ?>"  id="accessFunctionParameters" title="Function parameters"> 
                <?php echo ei_icon('ei_parameter') ?>  Parameters
            </a>
        </li>
        <li class="<?php if(isset($activeItem) && ($activeItem=='functionNotices' )): echo 'active' ; endif; ?>">
            <a class="btn btn-sm"  href="<?php echo $tab_notices_uri ?>"  id="accessFunctionNotices" title="Function notices"> 
                <?php echo ei_icon('ei_notice') ?>  Notices
            </a>
        </li>
<!--        <li> 
            <a class="btn btn-sm" href="<?php 
//            echo 'http://'.sfConfig::get('project_prefix_path').
//                                'en/fonctions/show/'.$project_id.'/'.
//                                $project_ref.'/'.$kal_function->getFunctionId().
//                                '/'.$kal_function->getFunctionRef().'/0'
                        ?>" target="_blank" title="External link"> 
                <i class="fa fa-external-link"></i> External
                
            </a>-->
        <!--</li>-->
    </ul>
</div>
 
<?php endif; ?>
