<!--arbre_projet-->
<?php 
if ($reloadProjet == true): $reload = "reloading";
else : $reload = "notReloading"; endif;
    ?>  
<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<div class="panel panel-default eiPanel " id="functions_menu">
    <input type="hidden" name="project_ref" value="<?php echo $project_ref; ?>" id="project_ref" />
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id" />
    <div class="panel-heading">
        <h2>
            <?php echo ei_icon('ei_function', 'lg','','Functions') ?>
            <strong class="panelHeaderTitle">Functions</strong></h2> 
        <div class="panel-actions">
            <?php $refreshProject=$url_tab;
            $refreshProject['showFunctionContent']=((isset($showFunctionContent)&& $showFunctionContent) ? true:false);
            $refreshProject['is_function_context']=((isset($is_function_context) && $is_function_context)? true: false);
            $refreshProject['is_step_context']=((isset($is_step_context) && $is_step_context)? true: false);  ?>
            
            <?php $getRootTree=$refreshProject; $getRootTree['reload']=1; ?>
            <a class="btn-default" id="refreshProject" title="Refresh tree" itemref="<?php echo  url_for2('getRootTree',$getRootTree)?>" > 
                <i class="fa fa-refresh fa-lg"></i>
            </a> 
        </div>
    </div> 
    <?php if ($root_tree != null): ?>
    <div class="panel-body clearfix">
        <div class="reloading_img_tree" >
            <i class="fa fa-spinner fa-spin fa-4x" ></i>   
        </div>
        <div id="<?php //echo $reload; ?>" class="arbre_projet">
            
        </div>
        <ul  class="nav nav-list">
                 

            <li id="arbo_default" class="no-padding">
                <ul class="arbo_pack ">
                    <li class="no-padding lien_survol_tree">
                        <input type="hidden" name="node_id" value="<?php echo $root_tree->getId(); ?>" class="node_id" /> 
                        <input type="hidden" name="tree_type" value="<?php echo $root_tree->getType(); ?>" class="tree_type" />
                        <div id="version_loader_div"> 
                            <i class="fa fa-spinner fa-spin" id="version_loader"></i> 
                        </div>
                        
                        <i class="cus-house img_arbo_pack" title="Vues de l arborescence"> </i> 
                            <?php echo $root_tree->getName() ?> 
                        <a class="add_script_folder add_node_child" title="Create a new folder" alt="New Folder"  style="visibility: hidden;"
                           href="<?php echo url_for('view/new?project_id='.$project_id.'&project_ref='.$project_ref.'&profile_id='.$profile_id.'&profile_ref='.$profile_ref.'&parent_id='.$root_tree->getId()) ?>">
                            <i class="cus-folder-add">  </i>
                        </a>
                        <a class="add_kal_function add_node_child" title="Create a new function" alt="New function"  style="visibility: hidden;"
                           href="<?php echo url_for('kalfonction/new?project_id='.$project_id.'&project_ref='.$project_ref.'&profile_id='.$profile_id.'&profile_ref='.$profile_ref.'&parent_id='.$root_tree->getId()) ?>">
                            <i class="cus-page-white-add"></i>
                        </a>
                    </li>
        <?php $arboTree=$url_tab;
            $arboTree['ei_tree'] = $root_tree;
            $arboTree['tree_childs']=(isset($tree_childs)?$tree_childs : array());
            $arboTree['opened_ei_nodes'] = $opened_ei_nodes;
            $arboTree['class_action'] = $class_action;
            $arboTree['showFunctionContent'] = ((isset($showFunctionContent) && $showFunctionContent) ? true : false);
            $arboTree['is_function_context'] = ((isset($is_function_context) && $is_function_context) ? true : false);
            $arboTree['is_step_context'] = ((isset($is_step_context) && $is_step_context) ? true : false); 
            include_partial('tree/arboTree', $arboTree) ?>
                </ul>
            </li>
        </ul>

    </div>	
    <?php endif; ?>
</div>