<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
 
<div class=" row"> 
    <div class=" form row">
        <div id="informations" class=" row"> 
            <input type="hidden" name="project_id" value="<?php echo $project_id ?>" class="project_id" />
            <input type="hidden" name="project_ref" value="<?php echo $project_ref ?>" class="project_ref" />
            <input type="hidden" name="ei_scenario_id" value="<?php echo $ei_scenario->id ?>" id="ei_scenario_id" /> 
                <?php
                if (isset($form)):
                    $url_form=$url_tab;
                    $url_form['ei_scenario']=$ei_scenario;
                    $url_form['form']=$form;
                    include_partial('form', $url_form);
                endif;
                ?>
                <?php if (isset($list_notices)) : ?>
                <?php $show_notice=$url_tab;
                    $show_notice['list_notices']=$list_notices;
                    $show_notice['ei_scenario']=$ei_scenario;
                    $show_notice['ei_profile']=$ei_profile;
                    $show_notice['ei_project']=$ei_project;
                    $show_notice['ei_version']=$root;
                    include_partial('show_notice',$show_notice)
                    ?>
                <?php endif; ?> 
        </div> 
    </div>
</div>
<?php
if (!$form->getObject()->isNew()):
    $root_node = $ei_project->getRootFolder();
    $ei_node = $ei_scenario->getNode();
    ?>  
 <?php if(isset($ei_scenario)) :?> 
<!-- Chemin du scénario -->
<!--            <ul class="breadcrumb"> 
                <li class="pull-right">
                    <a href=" <?php //$projet_eiscenario_action_uri= $url_tab; $projet_eiscenario_action_uri['ei_scenario_id']=$ei_scenario->getId();
                          //$projet_eiscenario_action_uri['action']="createClone"; echo url_for2("projet_eiscenario_action",$projet_eiscenario_action_uri);
                    ?>" id="create_scenario_clone"> &nbsp;&nbsp;<i class="fa fa-tags"></i> Copy 
                    </a>
                </li>
                <li class="divider-vertical"></li> 
                <li class="pull-right">
                    <a href="#delete_eiscenario_modal" data-toggle="modal">
                        &nbsp;&nbsp;<i class="fa fa-times-circle-o"></i>  Delete
                    </a>
                </li>
            </ul> -->
<div id="delete_eiscenario_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete_eiscenario_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete <?php echo $ei_scenario->getNomScenario(); ?></h3>
            </div>
            <div class="modal-body"> 
                    <?php echo "You are about to delete scenario <strong>" . $ei_scenario->getNomScenario() . "</strong>. All its versions will be deleted as well as data sets.<br/> Do you really want to delete " . $ei_scenario->getNomScenario() . " ?"; ?>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</a> 
                    <?php  $projet_eiscenario_delete_uri=$url_tab; $projet_eiscenario_delete_uri['ei_scenario_id']=$ei_scenario->getId();
                            $projet_eiscenario_delete_uri['action']='delete'; ?>
                    <a  id="delete_scenario"class="btn btn-danger btn-sm"  href="<?php  echo url_for2("projet_eiscenario_action", $projet_eiscenario_delete_uri);  ?>" > Delete
                    </a>
                </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div id="modalDiagram" class="modal  fade" tabindex="-1" role="dialog"
aria-labelledby="myModalLabel" aria-hidden="true">
    <input type="hidden" name="current_node_id" value="<?php echo $ei_node->getId() ?>" class="current_node_id" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3>Diagram</h3>
            </div>
            <div class="modal-body">
                <ul id="boxCheckingDiagram">
                    <li class="lien_survol_node">
                        <input type="hidden" name="project_ref" value="<?php echo $project_ref; ?>" id="project_ref" />
                        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id" />
                        <input type="hidden" name="root_id" value="<?php echo $root_node->getId(); ?>" class="root_id" />
                        <input type="hidden" name="node_id" value="<?php echo $root_node->getId(); ?>" class="node_id" />

                        <a href="#"  class="checkNode">
                            <i class="cus-house"></i> <?php echo $root_node->getName() ?>
                        </a>  
                    </li>
                    <li>
                        <ul class="node_diagram">
                            <?php  $nodeDiagramForChecking=$url_tab;
                            $nodeDiagramForChecking['ei_node']=$root_node; 
                            $nodeDiagramForChecking['current_node']=$ei_node; 
                            include_partial('einode/nodeDiagramForChecking',$nodeDiagramForChecking);
                            ?>
                        </ul>
                    </li>
                </ul>
                <div id="selectedNode">
                    <input type="hidden" name="new_parent_id" value="" class="new_parent_id" />
                    <div class="folder_detail bordered">
                         <h6 class=" new_parent_node_name"></h6>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-success btn-sm confirmSelectedNodeParent" itemref="<?php echo url_for2("changeNodeParent",$url_tab) ?>">Confirm</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>