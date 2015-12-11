<!-- Barre de navigation supérieure d'un scénario--> 
 
<?php
if (!isset($profile_id) || !isset($profile_ref)) {
    $profile_id = 0;
    $profile_ref = 0;
}

if (!isset($profile_name) || $profile_name == '')
    $profile_name = "profil";
if (isset($ei_scenario))
    $projet = $ei_scenario->getEiProjet();
?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     ) ;
$navMenuparams=$url_tab;
$navMenuparams['ei_block_root']=$ei_block_root_form->getObject();
//INCLUSION DU MENU DE GAUCHE
    include_component ('eiscenario', 'navMenu',$navMenuparams); 
?>



<div class="col-lg-9 col-md-9 col-sm-10"> 
    <div class="form"> 
        <div id="block">
            <?php if (isset($projet)): ?>
            <input type="hidden" name="project_id" value="<?php echo $projet->project_id ?>" class="project_id" />
            <input type="hidden" name="project_ref" value="<?php echo $projet->ref_id ?>" class="project_ref" />
            <input type="hidden" name="ei_scenario_id" value="<?php echo $ei_scenario->id ?>" id="ei_scenario_id" />
 
            <?php endif; ?>
            <?php include_partial('block/edit', array(
                'ei_block_root_form' => $ei_block_root_form,
                'ei_block_parameters' => $ei_block_parameters,
                'ei_block_children' => $ei_block_children,
                'ei_scenario' => $ei_scenario,
                'profile_name' => $profile_name,
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'project_id' => $projet->project_id,
                'project_ref' => $projet->ref_id)); ?>
            
        </div>
    </div>
</div>
<?php
if (!$form->getObject()->isNew()):
    $root_node = $ei_project->getRootFolder();
    $ei_node = $ei_scenario->getNode();
    ?> 
    <div id="modalDiagram" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <input type="hidden" name="current_node_id" value="<?php echo $ei_node->getId() ?>" class="current_node_id" />
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Diagram</h3>
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
                        <?php
                        include_partial('einode/nodeDiagramForChecking', array('ei_node' => $root_node, 'ei_project' => $ei_project, 'ei_profile' => $ei_profile));
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
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-success confirmSelectedNodeParent" itemref="<?php echo url_for2("changeNodeParent",$url_tab) ?>">Confirm</button>
        </div>
    </div>

<?php endif; ?>