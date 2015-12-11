<?php if(isset($project_id) && isset($project_ref) && isset($ticket_id) && isset($ticket_ref)): ?>
<?php if(isset($resolved_conflicts_item) && count($resolved_conflicts_item) > 0): 
    foreach($resolved_conflicts_item->getRawValue() as $key => $item):
    $resolved_profiles[$function_id.'_'.$function_ref.'_'.$item['profile_id'].'_'.$item['profile_ref']]=$key;
    endforeach;
endif; ?>
<div class="btn-group btn-group-xs function_line_profiles"> 
    <?php if(isset($ei_profiles) && count($ei_profiles)>0) ?>
    <?php foreach ($ei_profiles as $ei_profile) : ?> 
        <?php
        $profile_id = $ei_profile->getProfileId();
        $profile_ref = $ei_profile->getProfileRef();
        $profile_name = $ei_profile->getName();
        ?>
        <?php
        $class_profile = '';
        if (!empty($scriptProfiles)):
            $key = $function_id . '_' . $function_ref . '_' .
                    $profile_id . '_' . $profile_ref;

            if (array_key_exists($key, $scriptProfiles->getRawValue())) :
                if (isset($resolved_profiles) && count($resolved_profiles) > 0):
                    if (array_key_exists($key, $resolved_profiles)):
                        $class_profile = 'btn-success';
                    else:
                        $class_profile = 'migrateBugFunction';
                    endif;
                else:
                    $class_profile = 'btn-success';
                endif;

            else:
                $class_profile = 'migrateBugFunction';
            endif;
        endif;
        ?>  
        <a  href="<?php
        echo ($class_profile == 'migrateBugFunction') ?
                url_for2('migrateBugFunction', array('ticket_id' => $ticket_id,
                    'ticket_ref' => $ticket_ref,
                    'project_id' => $project_id,
                    'project_ref' => $project_ref,
                    'function_id' => $function_id,
                    'function_ref' => $function_ref,
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