<?php if (isset($subjectPriorities)): ?>
    <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name,
        'contextRequest' => (isset($contextRequest) ? $contextRequest : null));
    $changeGroupSubjectAction = $url_tab;
    $changeGroupSubjectAction['act'] = 'Priority';
    ?>
    <form action="<?php echo url_for2('changeGroupSubjectAction', $changeGroupSubjectAction) ?>" id="groupActionPriorityForm">
        <div class=" form-group">
            <label for="subject_priority"><h6>Priority</h6></label> 
            <div class="controls"> 
                <?php if (count($subjectPriorities) > 0): ?>
                    <?php foreach ($subjectPriorities as $key => $priority): ?>
                        <label class="radio">
                            <input type="radio" name="new_priority_for_many"   
                                   value="<?php echo $priority->getId() ?>" checked>
                                   <?php echo $priority ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <input type="submit" class="btn btn-success btn-sm" 
                   id="saveGroupActionPriority" value="Save" />
        </div>
    </form>
<?php endif; ?> 