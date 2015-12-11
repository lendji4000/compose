<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<div id="ul_menu">
    <?php if ($sf_user->isAuthenticated()): ?>
    
    <input type="hidden" name="project_id" value="<?php echo $ei_project->getProjectId() ?>" id="project_id" />
    <input type="hidden" name="project_ref" value="<?php echo $ei_project->getRefId() ?>" id="project_ref" />
 
        <?php  
        $arbreProjet=$url_tab; 
        $arbreProjet['ei_project']=$ei_project;
        $arbreProjet['ei_version']=(isset($ei_version)? $ei_version : null);
        $arbreProjet['showFunctionContent']=((isset($showFunctionContent) && $showFunctionContent)? true: false);
        $arbreProjet['is_function_context']=((isset($is_function_context) && $is_function_context)? true: false);
        $arbreProjet['is_step_context']=((isset($is_step_context) && $is_step_context)? true: false); 
            include_component("eiprojet", "arbreProjet",$arbreProjet  ); 
        ?> 
    <?php endif; ?>
</div>

