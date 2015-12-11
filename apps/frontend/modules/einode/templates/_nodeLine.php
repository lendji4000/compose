<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name)?> 

<?php
$tab=MyFunction::getPathToTreeNode($ei_node,$url_tab);
$img_node=$tab['img_node'];
$path_to_node=$tab['path_to_node'];
$isOpen = false;

$openHidden= "";
$closeHidden ="eisge-hidden";
if(isset($opened_ei_nodes)):
    foreach($opened_ei_nodes as $i => $node):
        if($node->getEiNodeId() == $ei_node->getId()):
            $isOpen = true;
            $openHidden="eisge-hidden";
            $closeHidden = "";
            break;
        endif;
    endforeach;
endif;
?>

<li class="item_tree">
    <ul>
        <li class="lien_survol_node">     
            <input type="hidden" name="obj_id" value="<?php echo $ei_node->getObjId(); ?>" class="obj_id" />
            <input type="hidden" name="node_id" value="<?php echo $ei_node->getId(); ?>" class="node_id" />
            <input type="hidden" name="node_type" value="<?php echo $ei_node->getType(); ?>" class="node_type" />
              
            <?php $einode_children=$url_tab ;
             $einode_children['ei_node_id']=$ei_node->getId() ;
//             $einode_children['ei_scenario_id']=$ei_node->getObjId() ;
             $einode_children['ei_node_type']=$ei_node->getType();
             $einode_children['is_step_context']=((isset($is_step_context) && $is_step_context==1)? 1:0) ?>
            <i class="fa fa-plus-square  show_node_diagram <?php echo $openHidden ?>" title="Show Child Node" 
               data-href="<?php echo url_for2('einode_children', $einode_children) ?>">
            </i>
            <?php $ei_node_close=$url_tab ;
             $ei_node_close['ei_node_id']=$ei_node->getId() ;?>
            <i class="fa fa-minus-square  hide_node_diagram <?php echo $closeHidden ?> " 
               data-href="<?php echo url_for2('ei_node_close',$ei_node_close) ?>" title="Hide Child Node">
            </i>
                <?php if(isset($is_step_context) && $is_step_context==1): ?>
                <a href="<?php if($ei_node->getType()=='EiScenario') :
                      $addStepInContent=$url_tab ;
                      $addStepInContent['campaign_id']=0;
                      $addStepInContent['id']=0 ;
                      $addStepInContent['ei_scenario_id']=$ei_node->getObjId() ;
                      $addStepInContent['data_set_id']=0;  
                    echo url_for2("addStepInContent",$addStepInContent); else: echo "#"; endif; ?> "
                   class="<?php if($ei_node->getType() == "EiFolder") echo "folder"; ?>
                          <?php if($ei_node->getType() == "EiScenario") echo "addStepInContent"; ?>">
                    <?php echo $img_node.' '. $ei_node->getName(); ?>
                </a>
                <?php //if($ei_node->getType()== 'EiScenario'): ?>
                <a class=" " href="<?php echo url_for($path_to_node); ?>" target="_blank">
                      <i class='fa fa-external-link'></i>
                  </a>
                <?php //endif; ?>
            <!-- On est dans l'édition des steps d'une campagne-->
                <?php else :  ?>  
                <a href="<?php echo url_for($path_to_node); ?> " 
                   class="<?php if($ei_node->getType() == "EiFolder") echo "folder"; ?>">
                       <?php echo $img_node.' '. $ei_node->getName(); ?>
                </a>
                <?php $create_folder=$url_tab;
                      $create_folder['root_id']=$ei_node->getId();
                      $create_folder['action']='new';  ?>
                <a href="<?php echo url_for2('create_folder',$create_folder) ?>" 
                   class="add_folder add_node_child" alt="New Folder" title="Create a new folder"> 
                     
                    <?php echo ei_icon('ei_folder_open','lg',null,null,'ei-folder',
                              ei_icon('ei_add',null,null,null,'ei-add-scenario-top' )) ?>
                    
                </a>
                 <a href="#" class="add_scenario add_node_child" alt="New" title="Create a new test suit"> 
                      
                     <?php echo ei_icon('ei_scenario','lg',null,null,'ei-scenario',
                              ei_icon('ei_add',null,null,null,'ei-add-scenario-top' )) ?>
                     
                 </a>
                 <?php endif; ?>
        </li>
        <li>
            <ul class="node_diagram">  </ul>
        </li>
        
        
    </ul> 
    
</li>