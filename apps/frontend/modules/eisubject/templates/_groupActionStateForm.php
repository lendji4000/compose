
<?php if (isset($subjectStates)): ?>
    <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name,
        'contextRequest' => (isset($contextRequest) ? $contextRequest : null));
    $changeGroupSubjectAction = $url_tab;
    $changeGroupSubjectAction['act'] = 'State';
    ?>
    <form action="<?php echo url_for2('changeGroupSubjectAction', $changeGroupSubjectAction) ?>" id="groupActionStateForm"> 
        <div class="controls">  
            <label for="search_subject_by_state"><h6>State</h6></label> 
            <div class="controls"> 
                <?php if (count($subjectStates) > 0): ?>
                    <?php foreach ($subjectStates as $key => $state): ?>
                        <label class="radio">
                            <input type="radio" name="new_state_for_many"   
                                   value="<?php echo $state->getId() ?>" checked>
                                   <?php echo $state ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <input type="submit" class="btn btn-success btn-xs" 
               id="saveGroupActionState" value="Save" />
    </form>   
<?php endif; ?>
