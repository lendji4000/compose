<?php if(!isset($is_version)) $is_version=false; ?>
<?php $with_play = !isset($with_play) ? true:$with_play; ?>
<?php $is_data_set_structure = !isset($is_data_set_structure) ? false:$is_data_set_structure ?>

<?php
    // TODO: VERIFIER QUE LES MODIFICATIONS DANS LA BRANCHE SOIENT OPPORTUNS. WITH PLAY ommis.
    if(isset($jddScenarioToPlay) && $jddScenarioToPlay != null && isset($jddScenarioToPlay["id"])){
        $idJdd = $jddScenarioToPlay["id"];
        $nomJdd = $jddScenarioToPlay["name"];
    }
    else{
        $idJdd = null;
        $nomJdd = "No Data Set Selected";
    }

    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'ei_scenario_id' => isset($ei_scenario_id) ? $ei_scenario_id:null,
    );
?>

<div id="ei_scenario_menu_left" class="  col-lg-3 col-md-3 col-sm-3 cols-xs-3" > 
        <div class="panel panel-default eiPanel " id="menu_blocks"  >
        <div class="panel-heading">
            <h2> 
                <?php echo ei_icon('ei_scenario','lg','','Structure') ?>
                </i><strong class="panelHeaderTitle">Structure</strong></h2>

            <div class="panel-actions"> 
            </div>
        </div>

        <div class="panel-body clearfix">

            <?php  
            $menuStructureBlockParams=$url_tab;
            $menuStructureBlockParams['ei_blocks']=$ei_blocks;
            $menuStructureBlockParams['is_editable']=(isset($is_editable) && $is_editable ? true: false);
            $menuStructureBlockParams['block_redirect_class']=$block_redirect_class;
            $menuStructureBlockParams['is_version']=$is_version;
            $menuStructureBlockParams['ei_block_root']=$ei_block_root;
            $menuStructureBlockParams['active_ei_block']=isset($ei_current_block) ? $ei_current_block:null;
            // Inclusion du menu de navigation dans la structure du scénario.
            include_partial("eiscenario/menuStructureBlock", $menuStructureBlockParams);
            ?> 
        </div>
    </div>
    
</div>