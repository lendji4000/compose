<!-- Arborescence d'un noeud de l'arbre -->  
<?php $urlParameters = $urlParameters->getRawValue();
            $aux = $urlParameters; 
if(isset($aux['parent_id'])):
    unset($aux['parent_id']);
endif;
if(isset($aux['ei_node_id'])):
    unset($aux['ei_node_id']);
endif;  ?>

<?php foreach ($urlParameters['ei_data_set_children'] as $node_child) : 
    
    $urlParameters['is_step_context']= ((isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']==1)? 1:0);
    $urlParameters['is_edit_step_case']= ((isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'])? true:false);
    $urlParameters['is_select_data_set']= ((isset($urlParameters['is_select_data_set']) && $urlParameters['is_select_data_set'])? true:false);
    $urlParameters['current_step_id']= ((isset($urlParameters['current_step_id']) && $urlParameters['current_step_id'])? $urlParameters['current_step_id']:0);
    
    $urlParameters['parent_id'] = $node_child->getId();
    $urlParameters['ei_node_id'] = $node_child->getId();

    switch ($node_child->getType()) {
        case 'EiDataSetFolder':
            $img_node =     ei_icon('ei_dataset_folder') ;
            $params = $urlParameters;
            $params['ei_node_type'] = $node_child->getType();
            $eidataset_folder_edit=$urlParameters;
            $eidataset_folder_edit['action']='editEiDataSetFolder';
            $path_to_node = url_for2('eidataset_folder_edit', $eidataset_folder_edit);
            $linkcls = "data_set_folder";
            $folder = true;
            $class="";
            break;

        // TODO: Modification réalisée pour le passage aux templtes.
        case 'EiDataSetTemplate':
            /** @var EiDataSetTemplate $template */
            $template = Doctrine_Core::getTable('EiDataSetTemplate')->findOneByEiNodeId($node_child->getId());
            $img_node =     ei_icon('ei_dataset');
            $aux['ei_data_set_id'] = $template->getId();
            $aux['action']='edit';
            $linkcls = "data_set";
            $path_to_node = url_for2('eidataset_edit', $aux);
            $path_to_scenario = url_for2("projet_new_eiversion", array(
                "ei_scenario_id" => $aux['ei_scenario_id'],
                "action" => 'editVersionWithoutId',
                "project_ref" => $aux["project_ref"],
                "project_id" => $aux["project_id"],
                "profile_ref" => $aux["profile_ref"],
                "profile_id" => $aux["profile_id"],
                "profile_name" => $aux["profile_name"]
            ));
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
            <input type="hidden" name="obj_id" value="<?php echo $node_child->getObjId(); ?>" class="obj_id" />
            <input type="hidden" name="node_id" value="<?php echo $node_child->getId(); ?>" class="node_id" />
            <input type="hidden" name="root_id" value="<?php echo $node_child->getRootId(); ?>" class="root_id" />
            <input type="hidden" name="node_type" value="<?php echo $node_child->getType(); ?>" class="node_type" />
            <i title="Show Child Node"  class="fa fa-plus-square show_node_diagram_data_set" data-href="<?php echo url_for2('einode_children', $params) ?>"></i>
            <i title="Hide Child Node"  class="fa fa-minus-square hide_node_diagram_data_set" ></i>
            <?php endif ?>

            <?php if( isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'] && $node_child->getType()=='EiDataSetTemplate' ): ?>
                <a href="<?php echo url_for2('majStepDataSet',array(
                               'project_id' => $urlParameters['project_id'],
                               "project_ref" => $urlParameters['project_ref'],
                               'profile_id' => $urlParameters['profile_id'],
                               "profile_ref" => $urlParameters['profile_ref'],
                               "profile_name" => $urlParameters['profile_name'],
                               'data_set_id' => $template->getEiDataSetRefId(),
                               'id' => $urlParameters['current_step_id'])); ?>" class="majStepDataSet">
                    <?php echo $img_node . ' ' . MyFunction::troncatedText($node_child->getName(),17); ?>
                </a>

            <?php elseif( $urlParameters['is_select_data_set'] == true && $node_child->getType()=='EiDataSetTemplate' ): ?>
                <a href="#<?php echo $template->getEiDataSetRefId(); ?>" class="linkSelectDataSetApplet"
                   data-parent="<?php echo $aux['ei_scenario_id'] ?>" data-id="<?php echo $template->getEiDataSetRefId(); ?>"
                   data-name="<?php echo $node_child->getName(); ?>">
                    <?php echo $img_node . ' ' .MyFunction::troncatedText($node_child->getName(),25) ; ?>
                </a>

                <a href="<?php echo $path_to_node; ?>" class="edit_node_child"
                   data-parent="<?php echo $aux['ei_scenario_id'] ?>" data-id="<?php echo $node_child->getObjId(); ?>"
                   data-name="<?php echo $node_child->getName(); ?>" alt="Edit data set" title="Edit data set">
                    <?php echo ei_icon('ei_edit') ?>
                </a>



            <?php else:  ?>

                <a href="<?php if($node_child->getType()=='EiDataSetTemplate' && isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']==1):
                                   echo url_for2("addStepInContent",array(
                                          "project_id" => $urlParameters['project_id'],
                                          "project_ref" => $urlParameters['project_ref'],
                                          'profile_id' => $urlParameters['profile_id'],
                                          "profile_ref" => $urlParameters['profile_ref'],
                                          "profile_name" => $urlParameters['profile_name'],
                                          "campaign_id" => 0,
                                          "id" => 0,
                                          "data_set_id" => ($template->getEiDataSetRefId() != "" ? $template->getEiDataSetRefId():false),
                                          "ei_scenario_id" => $aux['ei_scenario_id'])) ;
                                else:
                                  echo  $path_to_node;
                                endif;
                            ?>"
                   class="<?php if(isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']): $linkcls=""; echo 'addStepInContent'; endif; ?>
                          <?php echo ((isset($urlParameters['is_edit_step_case']) && $urlParameters['is_edit_step_case'])? 'majStepDataSet': $linkcls)   ; ?>">
                    <?php echo $img_node . ' ' . MyFunction::troncatedText($node_child->getName(),17); ?>
                    <?php if($node_child->getType()=='EiDataSetTemplate'): ?>
                    <input type="hidden" name="data_set_id" value="<?php echo $node_child->getObjId(); ?>" class="data_set_id" />
                    <?php endif; ?>
                    <input type="hidden" name="obj_id" value="<?php echo $aux['ei_scenario_id'] ?>" class="obj_id" />
                </a>
            <?php endif; ?>

            <?php if(isset($urlParameters['is_step_context']) && $urlParameters['is_step_context']==1 ):   ?>
                <?php if($node_child->getType()=='EiDataSetTemplate'): ?>
                  <a href="<?php echo $path_to_scenario; ?>" class="externalDataSetAccessLink" target="_blank" data-id="<?php echo $node_child->getObjId(); ?>"
                     data-parent="<?php echo $aux['ei_scenario_id'] ?>">
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
                 <?php //if( $urlParameters['is_select_data_set'] == false ): ?>

                <?php $eidataset_folder_create =$urlParameters; $eidataset_folder_create['action']='newEiDataSetFolder'  ?>
                <a href="<?php echo url_for2("eidataset_folder_create", $eidataset_folder_create) ?>" class="add_ei_data_set_folder add_node_child" alt="New Folder" title="Create a new folder">
                    <?php echo  ei_icon('ei_dataset_folder') .' '. ei_icon('ei_add') ?>
                </a>
                <?php $eidataset_create =$urlParameters; $eidataset_create['action']='new'  ?>
                <a href="<?php echo url_for2("eidataset_create", $eidataset_create) ?>" class="add_ei_data_set add_node_child" alt="New data set" title="Create a new data set">
                   <?php echo ei_icon('ei_dataset') .' '. ei_icon('ei_add') ?>
                </a>

                 <?php if( $urlParameters['is_select_data_set'] == true ): ?>
                 <a href="<?php echo $path_to_node; ?>" class="edit_node_folder"
                    data-parent="<?php echo $aux['ei_scenario_id'] ?>" data-id="<?php echo $node_child->getObjId(); ?>"
                    data-name="<?php echo $node_child->getName(); ?>" alt="Edit data set folder" title="Edit data set folder">
                     <?php echo ei_icon('ei_edit') ?>
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

<?php endforeach; ?>