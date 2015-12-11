<?php if(isset($ei_project) && isset($ei_profile) && isset($ei_profiles) && isset($scenarioToMigrate)):   ?>
<?php
$project_id = $ei_project->getProjectId();
    $project_ref = $ei_project->getRefId();
    $profile_id = $ei_profile->getProfileId();
    $profile_ref = $ei_profile->getProfileRef();
    $profile_name = $ei_profile->getName();
    $url_tab = array(
    'package_id' => $scenarioToMigrate['s_package_id'],
    'package_ref' => $scenarioToMigrate['s_package_ref'],
    'project_id' => $ei_project->getProjectId(),
    'project_ref' => $ei_project->getRefId(),
    'ei_scenario_id' => $scenarioToMigrate['sc_id'], 
    );
if(isset($ei_delivery) && $ei_delivery!=null):
      $url_tab['delivery_id']=$ei_delivery->getId();
  endif;
  $resolved_conflicts=isset($resolved_conflicts)?$resolved_conflicts->getRawValue():array();
?>
<div class=" row scenario_line">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 scenario_line_checkbox" >
        <input  type="checkbox" class ="check_scenario_for_migration" />
        <input  type="hidden" class ="ei_scenario_id" value="<?php echo $url_tab['ei_scenario_id']   ?>" /> 
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 scenario_line_name" >
        <?php echo ei_icon('ei_scenario') ?>
        <?php $alert_conflict_class=""; ?>
        <?php if($scenarioToMigrate['nb_occurences']>1): $is_conflict=true; ?>
        <input type="hidden" class="conflictOnScenario" value="1" />
        <?php $alert_conflict_class="alert alert-warning alertConflictClass" ?>
        <?php endif; ?>
        <a href="<?php echo url_for2('editVersionWithPackage', array(
                'project_id' => $project_id,
                'project_ref' => $project_ref,
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'package_id' => $scenarioToMigrate['s_package_id'],
                'package_ref' => $scenarioToMigrate['s_package_ref'],
                'ei_scenario_id' => $scenarioToMigrate['sc_id']
                )) ?>" target="_blank">
            <span class="<?php echo  $alert_conflict_class ?> "> 
                <?php  echo   MyFunction::troncatedText($scenarioToMigrate['sc_name'], 30)  ?> 
            </span>  
        </a>  
        
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 scenario_line_package" >
         <?php if(isset($is_conflict) && $is_conflict): ?>
        <!--On vérifie si le conflit  a déjà été résolu -->
        <?php if(count($resolved_conflicts)>0 && array_key_exists($url_tab['ei_scenario_id'].'_'.$url_tab['delivery_id'], $resolved_conflicts)):  
            $resolved_conflicts_item=$resolved_conflicts[$url_tab['ei_scenario_id'].'_'.$url_tab['delivery_id']]['profile'];
            $resolved_package_id=$resolved_conflicts[$url_tab['ei_scenario_id'].'_'.$url_tab['delivery_id']]['package_id'];
            $resolved_package_ref=$resolved_conflicts[$url_tab['ei_scenario_id'].'_'.$url_tab['delivery_id']]['package_ref'];  
            
        endif; ?>
        <?php if(isset($scenariosToMigrateWithoutCount) && count($scenariosToMigrateWithoutCount)>0): //var_dump($scenariosToMigrateWithoutCount)  ?>
        <select class="form-control choosePackageForMigrationWhenConflictScenario">
            <option>Choose correct package</option> 
            <?php $t_id_val=0; $t_ref_val=0 ?> 
          <?php  foreach($scenariosToMigrateWithoutCount as $mfwc):
            if($mfwc['sc_id']==$scenarioToMigrate['sc_id']): $uriItem=$url_tab; 
            $uriItem['package_id']=$mfwc['s_package_id'];
            $uriItem['package_ref']=$mfwc['s_package_ref']?>
            <option itemref="<?php echo url_for2('getScenarioProfilesForPackage',$uriItem) ?>" 
                    <?php if(isset($resolved_package_id) && $mfwc['s_package_id']==$resolved_package_id && $mfwc['s_package_ref']==$resolved_package_ref ):
                        echo "selected=selected"; $t_id_val=$resolved_package_id;$t_ref_val=$resolved_package_ref;
                    endif; ?>
                    itemid="<?php echo $mfwc['s_package_id']?>" itemtype="<?php echo $mfwc['s_package_ref']?>">
                <?php echo $mfwc['et_name']; ?>
            </option>
            <?php endif;
           endforeach;?>
        
            <input  type="hidden" class ="package_id" value="<?php echo $t_id_val ?>" />
            <input  type="hidden" class ="package_ref" value="<?php echo $t_ref_val ?>" />
        </select>
        <?php endif; ?>
        <?php else :  ?>
        <a href="#">
            <?php echo $scenarioToMigrate['et_name'] ?> 
            <input  type="hidden" class ="package_id" value="<?php echo $url_tab['package_id']   ?>" />
            <input  type="hidden" class ="package_ref" value="<?php echo $url_tab['package_ref']   ?>"/>
        </a>
         <?php endif; ?>
        <?php $subjects_list_uri=$url_tab;  unset($subjects_list_uri['package_id']) ; unset($subjects_list_uri['package_ref']);    unset($subjects_list_uri['ei_scenario_id']); 
        $subjects_list_uri['contextRequest']="interventionLink";$subjects_list_uri['profile_id']=$profile_id; $subjects_list_uri['is_ajax_request']=true; 
        $subjects_list_uri['profile_ref']=$profile_ref; $subjects_list_uri['profile_name']=$profile_name;        ?>
        <a class="btn  btn-link loadInterventionModal" itemref="<?php echo url_for2('subjects_list',$subjects_list_uri) ?>" href="#changeInterventionOnMigrationModal"  data-toggle="modal">
            <?php echo ei_icon('ei_edit') ?>
            <input  type="hidden" class ="scenario_version_id" value="<?php echo $scenarioToMigrate['sp_ei_version_id'] ?>" />
        </a>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 " >
        <?php if(isset($is_conflict) && $is_conflict && !isset($resolved_package_id)): ?>
        <div class="scenario_line_profiles">
            Conflicts detected on scenario: <a href="#"> Solve them?</a>
        </div> 
        <?php else: ?> 
        <?php if(isset($resolved_package_id)): 
        $url_tab['package_id']=$resolved_package_id;  
            $url_tab['package_ref']=$resolved_package_ref; endif; ?>
        <?php $profileForTicketUri=$url_tab; 
        $profileForTicketUri['ei_profiles']=$ei_profiles;
        $profileForTicketUri['versionsProfiles']=$versionsProfiles;
        $profileForTicketUri['resolved_conflicts_item']=(isset($resolved_conflicts_item)?$resolved_conflicts_item:array());
       
        include_partial('eiprofilscenario/profilesForScenarioVersion',$profileForTicketUri  )  ?> 
        <?php endif; ?>
    </div>
     
</div> 
<?php endif; ?> 