<!-- Arborescence d'un noeud de l'arbre -->
<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
); 
?> 
<?php if(isset($ei_tree)  && $ei_tree!=null):  ?>
<!-- fonctions du package -->
 
<?php   
if(count($tree_childs)>0):   
    foreach ($tree_childs as $tree_child) :  

    $isOpen = false;
    $openHidden = "";
    $closeHidden ="eisge-hidden-tree";

    if(isset($opened_ei_nodes)):
        foreach($opened_ei_nodes as $i => $node):
            if($node->getEiTreeId() == $tree_child['id']): //echo 'good';
                $isOpen = true;
                $openHidden="eisge-hidden-tree";
                $closeHidden = ""; 
            endif;
            endforeach;
    endif;
$img_node=MyFunction::getImgTreeNode($tree_child);
         

if(!isset($class_action)):  $class_action = 'get_path_function'; endif;
//Si on utilise l'arbre des fonctions dans le menu d'administration des fonctions, on change la classe
if(isset($showFunctionContent) && $showFunctionContent): $class_action = 'showFunctionContent'; endif;
if(isset($is_function_context) && $is_function_context): $class_action = 'addFunctionToSubject';  endif;
if(isset($is_step_context) && $is_step_context): $class_action = 'showFunctionSubjects';  endif;
if(isset($impactContext) && $impactContext): $class_action = 'addFunctionHasImpact';  endif; 

$stats_url = "";
if($tree_child['type']=='Function' && $class_action == "statistics"):
    $statistics_function=$urlParams;
    $statistics_function['function_id']=$tree_child['obj_id'];
    $statistics_function['function_ref']=$tree_child['ref_obj'];
    $stats_url =  url_for2("statistics_function", $statistics_function); 
endif;

?>
<li class="lien_survol_arbre">
    <input type="hidden" name="ref_obj" value="<?php echo $tree_child['ref_obj']; ?>" class="ref_obj" />
    <input type="hidden" name="obj_id" value="<?php echo $tree_child['obj_id']; ?>" class="obj_id" />
    <input type="hidden" name="node_id" value="<?php echo $tree_child['id']; ?>" class="node_id" />
    <input type="hidden" name="root_id" value="<?php echo $ei_tree->getId(); ?>" class="root_id" />
    <input type="hidden" name="tree_type" value="<?php echo $tree_child['type']; ?>" class="tree_type" />
    <?php $sendTree=$urlParams;
    $sendTree['tree_type']=$tree_child['type'];
    $sendTree['ref_obj']=$tree_child['ref_obj'];
    $sendTree['obj_id']=$tree_child['obj_id'];
    $sendTree['root_id']=$ei_tree->getId();
    $sendTree['showFunctionContent']=$showFunctionContent;
    $sendTree['is_function_context']=$is_function_context;
    $sendTree['is_step_context']=$is_step_context; 
    ?>
    <?php if(isset($tree_child['nbchilds']) && $tree_child['nbchilds']>0): $is_open=true; else: $is_open=false; endif;  ?>
    <i  class="fa fa-plus-square  show_node_childs <?php echo $openHidden ?> " 
         style="display: <?php echo (($is_open)?'':'none')?>"
         itemref="<?php echo url_for2('sendTree',$sendTree) ?>" title="Show Child Node" >
    </i>
    <?php $ei_tree_close =$urlParams; $ei_tree_close['ei_tree_id']=$tree_child['id']; ?>
    <i class="fa fa-minus-square  hide_node_childs <?php echo $closeHidden ?>   "  
        style="display: <?php echo (($is_open)?'':'none')?>"
       itemref="<?php echo url_for2('ei_tree_close', $ei_tree_close); ?>" title="Hide Child Node">
    </i>
    <i class="fa fa-spinner fa-spin treeLoader"  style="display: none;"></i>
    <?php if(!$is_open): ?>
    <i class="fa fa-square ei-fa-disabled"></i>
    <?php endif; ?>
     <?php echo $img_node ?>  
    
    <span class="lien_survol_arbre lien_survol_tree">
        <?php         switch ($class_action):  
         case 'statistics':
             $url_link=$stats_url; 
         break;
        case 'showFunctionContent':
              $showFunctionContentUri=$urlParams;
            $showFunctionContentUri['function_id']=$tree_child['obj_id'];
            $showFunctionContentUri['function_ref']=$tree_child['ref_obj']; 
            $showFunctionContentUri['action']="show"; 
            $url_link=url_for2('showFunctionContent', $showFunctionContentUri);

         break;
        case 'addFunctionToSubject':
            $addFunctionToSubject=$urlParams;
            $addFunctionToSubject['function_id']=$tree_child['obj_id'];
            $addFunctionToSubject['function_ref']=$tree_child['ref_obj']; 
            $addFunctionToSubject['action']="addFunction"; 
            $url_link=url_for2('subjectFunction',$addFunctionToSubject); 
         break;
     
        case 'showFunctionSubjects':
            $showFunctionSubjects=$urlParams;
            $showFunctionSubjects['function_id']=$tree_child['obj_id'];
            $showFunctionSubjects['function_ref']=$tree_child['ref_obj']; 
            $showFunctionSubjects['action']="showContent"; 
            $url_link=url_for2('showFunctionContent',$showFunctionSubjects); 
         
         break; 

         default:
             $url_link="#functions_menu";
        break;
        endswitch; ?>
         
        <a href="<?php echo (($tree_child['type']=='Function')?$url_link:"#") ?>" data-stats="<?php echo $url_link ?>"  
           class="  <?php if($tree_child['type']=='Function') : echo "pop $class_action"; endif; ?>"   title="<?php echo $tree_child['name'];?>"  >
               <?php echo MyFunction::troncatedText($tree_child['name'],40) ?>
        </a>
        <?php $functionNodeDetailsUri=$urlParams;  $functionNodeDetailsUri['tree_type']=$tree_child['type'];$functionNodeDetailsUri['ei_tree_id']=$tree_child['id'];
             $functionNodeDetailsUri['obj_id']=$tree_child['obj_id']; $functionNodeDetailsUri['ref_obj']=$tree_child['ref_obj'];?>
        <a href="#" itemref="<?php echo url_for2('functionNodeDetails', $functionNodeDetailsUri) ?>" class="nodeMoreInf" style="visibility: hidden;"><i class="fa fa-lg fa-info-circle"></i></a>
        <a class="add_script_folder add_node_child" title="Create a new folder" alt="New Folder"  style="visibility: hidden;"
           href="<?php echo url_for('view/new?project_id='.$project_id.'&project_ref='.$project_ref.'&profile_id='.$profile_id.'&profile_ref='.$profile_ref.'&parent_id='.$tree_child['id']) ?>">
            <i class="cus-folder-add">  </i>
        </a>
        <a class="add_kal_function add_node_child" title="Create a new function" alt="New function"  style="visibility: hidden;"
           href="<?php echo url_for('kalfonction/new?project_id='.$project_id.'&project_ref='.$project_ref.'&profile_id='.$profile_id.'&profile_ref='.$profile_ref.'&parent_id='.$tree_child['id']) ?>">
            <i class="cus-page-white-add"></i>
        </a>
    </span>
        
    <ul class="arbo_tree "> 
        <?php 
            if($isOpen): 
                $arboTree=$urlParams;
                $arboTree['ei_tree']=Doctrine_Core::getTable('EiTree')->findOneById($tree_child['id']);
                $arboTree['tree_childs']=Doctrine_Core::getTable('EiTree')->getNodesWithChildsInf($tree_child['id']);
                $arboTree['opened_ei_nodes']=$opened_ei_nodes;
                $arboTree['class_action'] = $class_action;
                $arboTree['showFunctionContent']=$showFunctionContent;
                $arboTree['is_function_context']=$is_function_context;
                $arboTree['is_step_context']=$is_step_context;
                include_partial('tree/arboTree',$arboTree);
           endif;
        ?>
        
    </ul>
</li>
<?php 
        endforeach;
    endif; 
endif; ?>
