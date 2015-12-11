<!-- Noeud d'un arbre de jeux de données -->  
<?php 
$urlParameters = array('project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'ei_scenario_id'=>$ei_scenario_id,
    'is_step_context'=> (isset($is_step_context) && $is_step_context==1)? 1:0,
    'is_edit_step_case'=> (isset($is_edit_step_case) && $is_edit_step_case)? true:false,
    'is_select_data_set'=> (isset($is_select_data_set) && $is_select_data_set)? true:false,
    'current_step_id'=> (isset($current_step_id) && $current_step_id)? $current_step_id:0);
    $aux = $urlParameters;
        
    $urlParameters['parent_id'] = $ei_node->getId();
    $urlParameters['ei_node_id'] = $ei_node->getId();
    switch ($ei_node->getType()) {
        case 'EiDataSetFolder':
            $img_node =     ei_icon('ei_dataset_folder')  ;
            $params = $urlParameters;
            $params['ei_node_type'] = $ei_node->getType();
            $eidataset_folder_edit=$urlParameters;
            $eidataset_folder_edit['action']='editEiDataSetFolder';
            $path_to_node = url_for2('eidataset_folder_edit', $eidataset_folder_edit);
            $linkcls = "data_set_folder";
            $folder = true;
            $class="";
            break;

        case 'EiDataSet':
            $img_node =  ei_icon('ei_dataset')  ;
            $aux['ei_data_set_id'] = Doctrine_Core::getTable('EiDataSet')->findOneByEiNodeId($ei_node->getId())->getId();
            $aux['action']='edit';
            $linkcls = "data_set";
            $path_to_node = url_for2('eidataset_edit', $aux);
            $folder = false;
            $class="padding-left";
            break;

        default:
            break;
    }

    ?>
<li class="item_tree"> 
        <ul>
            <li class="lien_survol_node">     
                <?php if($folder): ?>
                <input type="hidden" name="obj_id" value="<?php echo $ei_node->getObjId(); ?>" class="obj_id" />
                <input type="hidden" name="node_id" value="<?php echo $ei_node->getId(); ?>" class="node_id" />
                <input type="hidden" name="root_id" value="<?php echo $ei_node->getRootId(); ?>" class="root_id" />
                <input type="hidden" name="node_type" value="<?php echo $ei_node->getType(); ?>" class="node_type" />
                <i title="Show Child Node"  class="fa fa-plus-square show_node_diagram_data_set" data-href="<?php echo url_for2('einode_children', $params) ?>"></i>
                <i title="Hide Child Node"  class="fa fa-minus-square hide_node_diagram_data_set" ></i> 
                <?php endif ?>

                <?php if( isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'] && $ei_node->getType()=='EiDataSet' ): ?>
                <?php $majStepDataSet= $urlParameters ; $majStepDataSet['data_set_id']=$ei_node->getObjId(); ?>    
                <a href="<?php echo url_for2('majStepDataSet',$majStepDataSet); ?>" class="majStepDataSet">
                        <?php echo $img_node . ' ' . MyFunction::troncatedText($ei_node->getName(),17); ?> 
                    </a>

                <?php elseif( $urlParameters['is_select_data_set'] == true && $ei_node->getType()=='EiDataSet' ): ?>
                    <a href="#<?php echo $ei_node->getObjId(); ?>" class="linkSelectDataSetApplet"
                       data-parent="<?php echo $aux['ei_scenario_id'] ?>" data-id="<?php echo $ei_node->getObjId(); ?>"
                       data-name="<?php echo $ei_node->getName(); ?>">
                        <?php echo $img_node . ' ' .MyFunction::troncatedText($ei_node->getName(),17) ; ?> 
                    </a>

                <?php else: ?>
                <?php $addStepInContent= $urlParameters ; $addStepInContent['campaign_id']=0; 
                      $addStepInContent['id']=0; $addStepInContent['data_set_id']=$ei_node->getObjId();   ?>   
                    <a href="<?php if($ei_node->getType()=='EiDataSet' && isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']==1):
                                       echo url_for2("addStepInContent",$addStepInContent) ;
                                    else:
                                      echo  $path_to_node;;  
                                    endif;
                                ?>"
                       class="<?php if(isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']): $linkcls=""; echo 'addStepInContent'; endif; ?>
                              <?php echo ((isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'])? 'majStepDataSet': $linkcls)   ; ?>">
                        <?php echo $img_node . ' ' . MyFunction::troncatedText($ei_node->getName(),17); ?> 
                        <?php if($ei_node->getType()=='EiDataSet'): ?>
                        <input type="hidden" name="data_set_id" value="<?php echo $ei_node->getObjId(); ?>" class="data_set_id" /> 
                        <?php endif; ?>
                        <input type="hidden" name="obj_id" value="<?php echo $aux['ei_scenario_id'] ?>" class="obj_id" />
                    </a>
                <?php endif; ?>
                  
                <?php if(isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']==1 ):   ?>
                    <?php if($ei_node->getType()=='EiDataSet'): ?>
                    <a class="addStepInContent " href="<?php echo $path_to_node; ?>">
                          <i class='fa fa-external-link'></i>  
                      </a>
                    <?php endif; ?>
                <?php else:  ?>
                    <?php if(isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'] ): ; ?>
                        <?php else: ?>
                    <?php
                     if($folder):
                        if(isset($urlParameters['ei_node_id'])):
                            unset($urlParameters['ei_node_id']);
                        endif;
                    ?>
                     <?php if( $urlParameters['is_select_data_set'] == false ): ?>
                 
                    <?php $eidataset_folder_create =$urlParameters; $eidataset_folder_create['action']='newEiDataSetFolder'  ?>
                    <a href="<?php echo url_for2("eidataset_folder_create", $eidataset_folder_create) ?>" class="add_ei_data_set_folder add_node_child" alt="New Folder" title="Create a new folder">
                       <?php echo ei_icon('ei_dataset_folder') .' '. ei_icon('ei_add') ?>
                    </a>
                    <?php $eidataset_create =$urlParameters; $eidataset_create['action']='new'  ?>
                    <a href="<?php echo url_for2("eidataset_create", $eidataset_create) ?>" class="add_ei_data_set add_node_child" alt="New data set" title="Create a new data set">
                       <?php echo ei_icon('ei_dataset') .' '. ei_icon('ei_add') ?>
                    </a>
                     <?php endif; ?>
                    <?php endif; ?>
                   <?php endif; ?>
                
                <?php endif; ?>
            </li>

            <li>
                <ul class="node_diagram"></ul>
            </li>
        </ul> 
    </li>
 