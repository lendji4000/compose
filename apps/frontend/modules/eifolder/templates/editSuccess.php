<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
        )
?>
<ul class="nav ">
    <li class="divider-vertical"></li>
    <li class="pull-right col-lg-6 col-md-6 active" id="toolTips"> 
        <?php if ($sf_user->hasFlash('msg_success')): ?>                  
            <?php echo $sf_user->getFlash('msg_success', ESC_RAW) ?>
        <?php endif; ?>
    </li>
</ul> 
 
<?php  if(isset($ei_node) && isset($ei_profile) && isset($ei_project)): ?>

<?php if (isset($node_childs)): ?>
<?php if(!$ei_node->getIsRoot()): ?>
<div class="row"> 
    <?php $formUri = $url_params ; $formUri['form']= $form; ?>
    <?php include_partial('form',  $formUri); ?> 
</div>
<?php endif; ?>

<div  class="row">
    <div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> 
            <?php echo ei_icon('ei_folder') ?> Childs
        </h2>
        <div class="panel-actions">  
        </div>
    </div>
    <div class="panel-body "  >  
        <div class=" scenario-panel" id="folder_childs">
            <?php foreach($node_childs as $node_child): 
                include_partial('einode/nodeDetails',array('node_parent'=>$ei_node, 'ei_node' =>$node_child,
                                'ei_project'=>$ei_project , 'ei_profile' =>$ei_profile));
            endforeach;  ?>
        </div>
        
    </div> 
    <div class="panel-footer"> 
    </div>
</div> 
    
</div>

<?php endif; ?>

<?php $root_node =$ei_project->getRootFolder() ; ?> 
<div id="modalDiagram" class="modal  fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <input type="hidden" name="current_node_id" value="<?php echo $ei_node->getId() ?>" class="current_node_id" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4><small>Select new parent for > </small><?php echo $ei_node ?></h4>
                </div>
                <div class="modal-body">
                    <ul id="boxCheckingDiagram" >

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
                            <ul class ="node_diagram">
                                <?php $nodeDiagramForChecking=$url_params; 
                                    $nodeDiagramForChecking['ei_node']=$root_node;
                                    $nodeDiagramForChecking['current_node']=$ei_node;
                                    $nodeDiagramForChecking['ei_project']=$ei_project;
                                    $nodeDiagramForChecking['ei_profile']=$ei_profile;
                                    include_partial('einode/nodeDiagramForChecking',$nodeDiagramForChecking);   ?>
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
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-success confirmSelectedNodeParent" itemref="<?php echo url_for2("changeNodeParent",$url_params) ?>">Confirm</button>
                </div>
            </div>
        </div>
        </div>

<?php endif; ?>
 