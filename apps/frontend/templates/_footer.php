<?php
     if (isset($project_ref) && isset($project_id) && isset($profile_ref) && isset($profile_id) && isset($profile_name)) : 
     $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
 
    <?php
    if($project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null && $sf_request->getParameter('action') == 'graphHasChainedList'):
        $refreshProject=array('project_id'=>$project_id, 'project_ref' => $project_ref);
        include_partial("global/executionMenu");
        echo '<div id="usage">';
        echo '<input type="hidden" itemref="'.url_for2("recharger_fonctions",$refreshProject).'" id="reloadProject" />';
        include_component("eicampaigngraph", "playButton");
        include_component("eicampaign", "playerInstanciator");
        echo '</div>';
    else: if ($project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null
        && ($sf_request->getParameter('ei_scenario_id') != null || $sf_request->getParameter('module') == 'eidataset')
    ):
        $refreshProject=array('project_id'=>$project_id, 'project_ref' => $project_ref);
        include_partial("global/executionMenu");
        echo '<div id="usage">';
        echo '<input type="hidden" itemref="'.url_for2("recharger_fonctions",$refreshProject).'" id="reloadProject" />';
        include_component("eiscenario", "playButton");
        include_component("eicampaign", "playerInstanciator");
        echo '</div>';
    else: if ($project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null ): 
        $refreshProject=array('project_id'=>$project_id, 'project_ref' => $project_ref);
        include_partial("global/executionMenu");
        echo '<div id="usage">';
        echo '<input type="hidden" itemref="'.url_for2("recharger_fonctions",$refreshProject).'" id="reloadProject" />';
        include_component("eiscenario", "playButton");
        echo '</div>';
   endif;
   endif;
   endif;
    ?>
<div class="fenetre">

</div>
<?php if ($sf_request->getPathInfoPrefix() != null) : ?>
    <input type="hidden" name="url_prefix" value="<?php echo $sf_request->getPathInfoPrefix(); ?>" class="url_prefix" />
<?php else : ?>
    <input type="hidden" name="url_prefix" value="<?php echo ''; ?>" class="url_prefix" />
<?php endif; ?>


<?php endif; ?>
    
<!--Fenêtre modale d'ajout d'un dossier de fonctions à partir de compose-->
<div id="addKalFolderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addKalFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="addKalFolderModalLabel">Add Folder</h3>
                 <div class="eiLoading" >
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
                <input class="node_id" type="hidden" name="node_id" />
            </div>
            <div class="modal-body addKalFolderModalBody"></div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveKalFolder" type="submit"> 
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!--Fenêtre modale d'ajout d'une fonction à partir de compose-->
<div id="addKalFunctionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addKalFunctionModalLabel" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="addKalFunctionModalLabel">Add Function</h3> 
                <input class="node_id" type="hidden" name="node_id" />
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-body addKalFunctionModalBody">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveKalFunction" type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<div id="addBlockToVersionModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="addBlockToVersionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="addKalFunctionModalLabel">Add Block</h3>
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
                <input class="node_id" type="hidden" name="node_id">
            </div>
            <div class="modal-body addBlockToVersionModalBody" >

            </div>
            <div class="modal-footer">
                <button class="btn btn-small btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-small btn-success pull-right" id="saveBlockToVersion" type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
<div id="nodeDetailsModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="nodeDetailsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="nodeDetailsModalLabel"></h3>
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
                <input class="node_id" type="hidden" name="node_id">
            </div>
            <div class="modal-body " id="nodeDetailsModalBody" >

            </div>
            <div class="modal-footer">
                <button class="btn btn-small btn-danger" data-dismiss="modal" aria-hidden="true">Close</button> 
            </div>
        </div>
    </div>
</div>

<?php if(isset($url_tab)): ?>
<!--
 * Box de recherche d'un jeu de données pour le player
 *
 * TODO: [editDataSetStepBox] Première occurrence 
-->
<div id="editDataSetStepBox" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="editDataSetStepBoxTitle" >Select data set for step</h3>
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
                <input type="hidden" id="editDataSetStepBoxLink" itemref="<?php echo url_for2('getScenarioDataSets', $url_tab) ?>" />
            </div>
            <div class="modal-body" id="editDataSetStepBoxContent">

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">
                    Close
                </a>
                <input id="step_scenario_id" type="hidden" value="" />
            </div>
        </div>
    </div>
</div>

<?php endif; ?> 
<!--
 * Box de modification d'une ligne de migration (function/scenario)
-->
<div id="changeInterventionOnMigrationModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="changeInterventionOnMigrationModalTitle" ><?php echo ei_icon('ei_subject') ?> Choose subject</h3> 
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
                <input  type="hidden" id ="current_script_id" value="" /> 
                <input  type="hidden" id ="current_scenario_version_id" value="" />
            </div>
            <div class="modal-body" id="changeInterventionOnMigrationModalContent">

            </div>
            <div class="modal-footer"> 
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">
                    Close
                </a> 
            </div>
        </div>
    </div>
</div>