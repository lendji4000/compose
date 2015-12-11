<?php if(isset($project_id) && isset($project_ref) && isset($package_id) && isset($package_ref)):  ?>
<?php if(isset($resolved_conflicts_item) && count($resolved_conflicts_item) > 0): 
    foreach($resolved_conflicts_item->getRawValue() as $key => $item): 
    $resolved_profiles[$ei_scenario_id.'_'.$item['profile_id'].'_'.$item['profile_ref']]=$key;
    endforeach; 
endif; ?>
<div class="btn-group btn-group-xs scenario_line_profiles"> 
    <?php if(isset($ei_profiles) && count($ei_profiles)>0) ?>
    <?php foreach ($ei_profiles as $ei_profile) : ?> 
        <?php
        $profile_id = $ei_profile->getProfileId();
        $profile_ref = $ei_profile->getProfileRef();
        $profile_name = $ei_profile->getName();
        ?>
        <?php
        $class_profile = '';
        if (!empty($versionsProfiles)): 
            $key = $ei_scenario_id . '_' .$profile_id . '_' . $profile_ref;
            
            if (array_key_exists($key, $versionsProfiles->getRawValue()) ) :  
                if (isset($resolved_profiles) && count($resolved_profiles) > 0): 
                    if (array_key_exists($key, $resolved_profiles)):
                        $class_profile = 'btn-success';
                    else:
                        $class_profile = 'migrateBugScenario';
                    endif;
                else:
                    $class_profile = 'btn-success';
                endif; 
            else:
                if (isset($resolved_profiles) && count($resolved_profiles) > 0): 
                    if (array_key_exists($key, $resolved_profiles)):
                        $class_profile = 'btn-success';
                    else:
                        $class_profile = 'migrateBugScenario';
                    endif;
                else: 
                $class_profile = 'migrateBugScenario';
                endif;
            endif;   
        endif; 
        ?>  
        <a  href="<?php
        echo ($class_profile == 'migrateBugScenario') ?
                url_for2('migrateBugScenario', array('package_id' => $package_id,
                    'package_ref' => $package_ref,
                    'project_id' => $project_id,
                    'project_ref' => $project_ref,
                    'ei_scenario_id' => $ei_scenario_id, 
                    'profile_id' => $profile_id,
                    'profile_ref' => $profile_ref,
                    'profile_name' => $profile_name)) : '#'
        ?>"
            class=" btn btn-default btn-sm <?php echo $class_profile ?>">
                <?php echo ei_icon('ei_profile') ?>
                <?php echo $ei_profile->getName() ?> 
            <img src="/images/loader.gif" class="loaderProfile" />
        </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>