
<?php if (isset($ei_version) && $ei_version !=null && isset($ei_scenario) && $ei_scenario!=null && isset($ei_profiles) && count($ei_profiles)>0 ): ?>

<!--On prépare le tableau des liaisons version(scenario)-profil --> 
<?php 
$ei_version_id=$ei_version->getId();
$ei_scenario_id=$ei_scenario->getId();
$project_id=$ei_scenario->project_id;
$project_ref=$ei_scenario->project_ref;
if(isset($actifs_version_profiles) && count($actifs_version_profiles)>0):
    foreach($actifs_version_profiles as $key => $actProf):
    $tab_profiles[$actProf->getEiVersionId().'_'.$actProf->getProfileId().'_'.$actProf->getProfileRef()]=$key;
    endforeach;    
    else:
    $tab_profiles=array();
endif; 
?>
<div class="pagination-centered">
   
<ul class="btn-toolbar"> 
        <li class="btn-group">
            <?php foreach($ei_profiles as $projProf): ?>
            <?php $class_name= "btn btn-sm  add_profil_scenario " ?>
            <?php $title= 'Activate '.$projProf->getName().'for this version and desactivate for others' ?>
            <?php $itemref= url_for2('newProfScen', array(
                            'project_id'=> $project_id,
                            'project_ref'=> $project_ref,
                            'profile_id'=> $projProf->getProfileId(),
                            'profile_ref'=> $projProf->getProfileRef(),
                            'ei_scenario_id'=>$ei_scenario_id,
                            'ei_version_id'=> $ei_version_id,
                            'action' => 'newProfScen'
                        )) ?>
            <?php if(count($tab_profiles)>0 && array_key_exists($ei_version_id.'_'.$projProf->getProfileId().'_'.$projProf->getProfileRef(), $tab_profiles)):
                $class_name= "btn btn-sm  btn-success  selected_profile " ;
                $title=$projProf->getName().' '.' is activated';
                $itemref="#";
            endif;
            ?>
            <button class="<?php echo $class_name ?> "  itemref="<?php echo $itemref ?>" title="<?php echo $title ?>"> 
                        <?php echo ei_icon('ei_profile') ?> 
                            <?php echo $projProf->getName(); ?>
            </button>
            <?php endforeach; ?>
      
        </li> 
</ul>
</div>
<?php endif; ?>