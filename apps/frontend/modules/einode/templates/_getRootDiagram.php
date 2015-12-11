<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name)?> 
<div  class="panel panel-default eiPanel"> 
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_scenario') ?> Tree</h2>
        <div class="panel-actions"> 
        </div>
    </div> 
    <div class="panel-body clearfix">
        <ul id="root_diagram" >

    <li class="lien_survol_node">
        <input type="hidden" name="project_ref" value="<?php echo $project_ref; ?>" id="project_ref" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id" />
        <input type="hidden" name="node_id" value="<?php echo $root_node->getId(); ?>" class="node_id" />
        <input type="hidden" name="obj_id" value="<?php echo $root_node->getObjId(); ?>" class="obj_id" />
        <i class="cus-house"></i>
        
        <?php
        $path_folder=$url_tab;
        $path_folder['folder_id']=$root_node->getObjId();
        $path_folder['node_id']=$root_node->getId();
        $path_folder['action']='edit';
        echo link_to2($root_node->getName(), 'path_folder',$path_folder,array('class' => 'node_name folder'))
        ?>
        <?php if(isset($is_step_context) && $is_step_context==1): ?> 
        <?php else : ?>
        <?php $create_folder=$url_tab;
                      $create_folder['root_id']=$root_node->getId();
                      $create_folder['action']='new';  ?> 
        <a href="<?php echo url_for2('create_folder',$create_folder) ?>"
           class="add_folder add_node_child" alt="New Folder" title="Create a new folder"> 
             <?php echo ei_icon('ei_folder_open',null,null,null,'ei-folder', ei_icon('ei_add',null,null,null,'ei-add-scenario-top')) ?>
            
        </a>
        <a href="#" class="add_scenario add_node_child" alt="New" title="Create a new test suit">
             <?php echo ei_icon('ei_scenario',null,null,null,'ei-scenario', ei_icon('ei_add',null,null,null,'ei-add-scenario-top')) ?>
        </a>
        <?php  endif; ?>
    </li>
    <li>
        <ul class ="node_diagram">
            <?php $nodeDiagram=$url_tab ;
            $nodeDiagram['opened_ei_nodes']=$opened_ei_nodes ;
            $nodeDiagram['ei_node']=$root_node ;
            $nodeDiagram['is_step_context']=((isset($is_step_context) && $is_step_context==1)? 1:0);  ?>
            <?php include_partial('einode/nodeDiagram', $nodeDiagram); ?>
        </ul>
    </li>
</ul>
    </div>	 
</div>

