<?php if (isset($subjectTypes)): ?>

    <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name,
        'contextRequest' => (isset($contextRequest) ? $contextRequest : null));
    $changeGroupSubjectAction = $url_tab;
    $changeGroupSubjectAction['act'] = 'Type';
    ?>
    <form action="<?php echo url_for2('changeGroupSubjectAction', $changeGroupSubjectAction) ?>" id="groupActionTypeForm">
        <div class=" form-group">
            <label for="search_subject_by_type"><h6>Type</h6></label>
            <div class="controls"> 
                <?php if (count($subjectTypes) > 0): ?>
                    <?php foreach ($subjectTypes as $key => $type): ?>
                        <label class="radio">
                            <input type="radio" name="new_type_for_many"  
                                   value="<?php echo $type->getId() ?>" checked>
                                   <?php echo $type ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <input type="submit" class="btn btn-success btn-sm" 
                   id="saveGroupActionType" value="Save" />
        </div> 
    </form>
<?php endif; ?>