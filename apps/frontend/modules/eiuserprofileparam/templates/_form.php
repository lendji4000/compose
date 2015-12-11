<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?> 
<form class="form-horizontal userProfileParamForm" action="<?php echo url_for2('userProfileParam',array(
    'action' => 'update',
    'project_id' => $ei_project->getProjectId(),
    'project_ref' => $ei_project->getRefId(),
    'id' => $ei_user_profile_param->getId()
)) ?>"> 
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?> 
    <div class="form-group"> 
        <div class="controls">
            <div class="input-group input-group-xs">
                <!--<input type="text" placeholder="set value" class="appendedInputButtons form-control"  >-->
                <?php echo $form['value']->renderError() ?> 
                <?php echo $form['profile_param_id']->renderError() ?> 
                <?php echo $form['value'] ?>
                <span class="input-group-btn"> 
                    <button title="Save user environment param"    class="saveUserProfileParam btn btn-xs btn-success" type="submit">
                        <i class="fa fa-check"></i>                                    </button>
                    <button title="Reset user environment parameter" type="button" class="resetUserProfileParam btn btn-xs btn-default"
                            itemref="<?php echo url_for2('userProfileParam', array(
                                'action' => 'resetUserProfileParam',
                                'project_id' => $ei_project->getProjectId(),
                                'project_ref' => $ei_project->getRefId(),
                                'id' => $ei_user_profile_param->getId()
                            )) ?>">
                        <i class="fa fa-refresh "></i>
                    </button>
                </span>
            </div>
        </div>
    </div>   
</form>