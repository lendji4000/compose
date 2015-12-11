<?php 
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name)?> 
<?php if(isset($ei_node)  && $ei_node!=null ):  ?>  

<?php  $node_childs=$ei_node->getNodes(); ?>

<?php if($node_childs->getFirst()):  ?> 
<?php foreach ($node_childs as $node_child) : ?>

<?php
$tab=MyFunction::getPathToTreeNode($node_child,$url_tab);
$img_node=$tab['img_node'];
$path_to_node=$tab['path_to_node'];
$isOpen = false;

$openHidden= "";
$closeHidden ="eisge-hidden";
if(isset($opened_ei_nodes)):
    foreach($opened_ei_nodes as $i => $node):
        if($node->getEiNodeId() == $node_child->getId()):
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
            <input type="hidden" name="obj_id" value="<?php echo $node_child->getObjId(); ?>" class="obj_id" />
            <input type="hidden" name="node_id" value="<?php echo $node_child->getId(); ?>" class="node_id" />
            <input type="hidden" name="node_type" value="<?php echo $node_child->getType(); ?>" class="node_type" />
              
            <?php $einode_children=$url_tab ;
             $einode_children['ei_node_id']=$node_child->getId() ;
             $einode_children['ei_scenario_id']=$ei_node->getObjId() ;
             $einode_children['ei_node_type']=$node_child->getType();
             $einode_children['is_step_context']=((isset($is_step_context) && $is_step_context==1)? 1:0) ?>
            <i class="fa fa-plus-square  show_node_diagram <?php echo $openHidden ?>" title="Show Child Node" 
               data-href="<?php echo url_for2('einode_children', $einode_children) ?>">
            </i>
            <?php $ei_node_close=$url_tab ;
             $ei_node_close['ei_node_id']=$node_child->getId() ;?>
            <i class="fa fa-minus-square  hide_node_diagram <?php echo $closeHidden ?> " 
               data-href="<?php echo url_for2('ei_node_close',$ei_node_close) ?>" title="Hide Child Node">
            </i>
                <?php if(isset($is_step_context) && $is_step_context==1): ?>
                <a href="<?php if($node_child->getType()=='EiScenario') :
                      $addStepInContent=$url_tab ;
                      $addStepInContent['campaign_id']=0;
                      $addStepInContent['id']=0 ;
                      $addStepInContent['ei_scenario_id']=$node_child->getObjId() ;
                      $addStepInContent['data_set_id']=0;  
                    echo url_for2("addStepInContent",$addStepInContent); else: echo "#"; endif; ?> "
                   class="<?php if($node_child->getType() == "EiFolder") echo "folder"; ?>
                          <?php if($node_child->getType() == "EiScenario") echo "addStepInContent"; ?>">
                    <?php echo $img_node.' '. $node_child->getName(); ?>
                </a>
                <?php //if($node_child->getType()== 'EiScenario'): ?>
                <a class=" " href="<?php echo url_for($path_to_node); ?>" target="_blank">
                      <i class='fa fa-external-link'></i>
                  </a>
                <?php //endif; ?>
            <!-- On est dans l'édition des steps d'une campagne-->
                <?php else :  ?>  
                <a href="<?php echo url_for($path_to_node); ?> " 
                   class="<?php if($node_child->getType() == "EiFolder") echo "folder"; ?>">
                       <?php echo $img_node.' '. $node_child->getName(); ?>
                </a> 
                    <?php $create_folder=$url_tab;
                      $create_folder['root_id']=$node_child->getId();
                      $create_folder['action']='new';  ?>  
                <a href="<?php echo url_for2('create_folder',$create_folder) ?>"
                   class="add_folder add_node_child" alt="New Folder" title="Create a new folder"> 
                     
                    <?php echo ei_icon('ei_folder_open',null,null,null,'ei-folder',
                            ei_icon('ei_add',null,null,null,'ei-add-scenario-top') ) ?>
                    
                </a>
                 <a href="#" class="add_scenario add_node_child" alt="New" title="Create a new test suit"> 
                      
                     <?php echo ei_icon('ei_scenario',null,null,null,'ei-scenario',ei_icon('ei_add',null,null,null,'ei-add-scenario-top')) ?>
                     
                 </a>
                 <?php endif; ?>
        </li>
        <li>
            <ul class="node_diagram">
                
                <?php 
                    if($isOpen):
                      $nodeDiagram=$url_tab ;
                      $nodeDiagram['ei_node']=$node_child; 
                      $nodeDiagram['is_step_context']=((isset($is_step_context) && $is_step_context==1)? 1:0) ;
                      $nodeDiagram['opened_ei_nodes']=$opened_ei_nodes;  
                      include_partial('einode/nodeDiagram',$nodeDiagram);
                    endif;
                ?>
            </ul>
        </li>
        
        
    </ul> 
    
</li>

<?php endforeach; ?> 
<?php endif; ?>

<?php if(isset($is_step_context) && $is_step_context==1 && $ei_node->getType()=='EiScenario'): ?>
        
         <?php $ei_scenario = Doctrine_Core::getTable('EiScenario')->find($ei_node->getObjId());   
        $ei_data_set_root_folder = Doctrine_Core::getTable('EiNode')
                ->findOneByRootIdAndType($ei_node->getId(), 'EiDataSetFolder'); 
        
        $ei_data_set_children = Doctrine_Core::getTable('EiNode')
                ->findByRootId($ei_data_set_root_folder->getId());
        ?>
<?php $urlParameters=$url_tab ;
$urlParameters['ei_scenario_id']=$ei_scenario->getId() ; 
$urlParameters['is_step_context']=((isset($is_step_context) && $is_step_context==1)? 1:0); 
$urlParameters['ei_scenario']=$ei_scenario; 
$urlParameters['ei_data_set_children']=$ei_data_set_children ; ?>
        <?php include_partial('eidataset/tree',
                array('urlParameters' => $urlParameters, 
                    'is_step_context' => ((isset($is_step_context) && $is_step_context==1)? 1:0))); ?>
        <?php endif; ?>
<?php endif; ?> 