<?php
$mod=$sf_request->getParameter('module');
$act=$sf_request->getParameter('action');
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name); ?>

<?php if(isset($ei_scenario)): ?>
<?php  $obj_url_tab=$url_tab; 
$obj_url_tab['ei_scenario_id']=$ei_scenario->getId(); ?>
<div class="row" id="eisge-object">
    <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
    <h2>
         <?php echo ei_icon('ei_scenario') ?> 
                <span class="text"  title="<?php echo $ei_scenario ?>" >   
                    <strong><?php echo 'Sc'.$ei_scenario->getId().'/'  ?></strong>
                     <?php  echo  $ei_scenario ?> 
                </span> 
    </h2> 
</div>

<div class="row" id="eisge-object-actions">
    <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
         On vérifie que des actions principales ont été définies pour cet objet
    -->  
    <ul class="nav nav-tabs"  > 
        <li class="<?php echo (($mod=='eiscenario' && $act!='getScenarioCampaigns' && $act!='statistics')?'active':'') ?>"> 
            <?php  $projet_eiscenario_action=$url_tab;
                    $projet_eiscenario_action['ei_scenario_id']=$ei_scenario->getId();
                    $projet_eiscenario_action['action']='edit'; ?>
                <a class="btn btn-sm"
                    href=" <?php echo url_for2("projet_eiscenario_action", $projet_eiscenario_action);?>" 
                    title="Go to edition panel"> <i class="fa fa-wrench"></i> 
                    <span class="text">  Properties    </span>  
                </a>
        </li>
        
        <li class="dropdown  <?php echo ($mod=='eiversion'?'active':'') ?>">
            <a class="dropdown-toggle btn btn-sm" data-toggle="dropdown" href="#"  >
                <?php echo ei_icon('ei_version') ?>  <span class="text">    Versions  </span>  
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <?php 
                    $projet_edit_eiversion=$url_tab;
                    $projet_edit_eiversion['ei_scenario_id']=$ei_scenario->getId();
                    $projet_edit_eiversion['action']='edit';
                    $projet_edit_eiversion['ei_version_id']=$defaultVersion->getId();
                    ?>
                    <a href="<?php  echo url_for2('projet_edit_eiversion', $projet_edit_eiversion); ?>" 
                       title="<?php echo $defaultVersion->getLibelle().'(This version is the one associate to the current Environment)'; ?>" >
                        <?php echo ei_icon('ei_version') ?> <?php echo $defaultVersion .'( Current )' ?>
                    </a>
                </li>
                <li>
                    <?php 
                        $projet_list_eiversion = $url_tab;
                        $projet_list_eiversion['ei_scenario_id'] = $ei_scenario->getId();
                        $projet_list_eiversion['action'] = 'index';
                    ?>
                    <a href="<?php  echo url_for2('projet_new_eiversion', $projet_list_eiversion); ?>" 
                       title="Scenario versions " >
                        <?php echo ei_icon('ei_list') ?>  List 
                    </a>
                </li>
                 
                 <?php if(isset($defPack) && $defPack!=null): ?>
                <li> 
                    <?php 
                    $projet_new_eiversion=$url_tab;
                    $projet_new_eiversion['ei_scenario_id']=$ei_scenario->getId();
                    $projet_new_eiversion['action']='new'; 
                    ?>
                    <a id="" href="<?php echo url_for2("projet_new_eiversion", $projet_new_eiversion); ?>"  title="Create version">
                         <?php echo ei_icon('ei_add') ?> Add
                    </a>
                </li>  
                <?php endif; ?>
            </ul>
        </li>
        <li class="<?php echo ($mod=='eidatasetstructure'?'active':'') ?>">
            <?php $eidatasetstructure_edit=$url_tab; $eidatasetstructure_edit['ei_scenario_id']=$ei_scenario->getId(); ?>
            <a class="btn btn-sm" href="<?php echo url_for2("eidatasetstructure_edit", $eidatasetstructure_edit); ?>" title="Scenario data set structure"  >
                <i class="fa fa-wrench"></i>&nbsp;<span class="text">Data set Structure</span>
            </a>
        </li>
        <li class="  <?php echo ($mod=='eitestset'?'active':'') ?>">
            <?php $ei_test_set_index=$url_tab; $ei_test_set_index['ei_scenario_id']=$ei_scenario->getId(); ?>
                    <a class="btn btn-sm" href=" <?php    echo url_for2("ei_test_set_index", $ei_test_set_index); ?>"> 
                        <?php echo ei_icon('ei_testset') ?> <span class="text">   Reports   </span>
                    </a> 
        </li>
        <li class="<?php echo (($mod=='eiscenario' && $act=='getScenarioCampaigns') ?'active':'') ?>"> 
            <?php  $getScenarioCampaigns=$url_tab;
                    $getScenarioCampaigns['ei_scenario_id']=$ei_scenario->getId();
                    $getScenarioCampaigns['action']='getScenarioCampaigns';?>
                <a class="btn btn-sm" id="accessTestSuiteCampaign"
                    href=" <?php echo url_for2("projet_eiscenario_action", $getScenarioCampaigns);?>" 
                    title="Scenario camapaigns"> <?php echo ei_icon('ei_campaign') ?> <span class="text"> Campaigns </span>
                </a> 
        </li>
        <li class="<?php echo (($mod=='eiscenario' && $act=='statistics') ?'active':'') ?>"> 
            <?php  $statistics=$url_tab;
                    $statistics['ei_scenario_id']=$ei_scenario->getId();
                    $statistics['action']='statistics';?>
                <a class="btn btn-sm" id="accessTestSuiteStats"
                    href=" <?php echo url_for2("projet_eiscenario_action", $statistics);?>" 
                    title="Scenario stats"> <?php echo ei_icon('ei_stats') ?><span class="text">   Statictics   </span> 
                </a> 
        </li>
    </ul>
</div>
  


 
 
<?php endif; ?>