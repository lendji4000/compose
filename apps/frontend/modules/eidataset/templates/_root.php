<?php 
$urlParameters = $urlParameters->getRawValue();
$urlParameters['parent_id'] = $ei_data_set_root_folder->getId();

$specialClass = isset($is_edit_step_case) && $is_edit_step_case ? 'col-lg-12 col-md-12' : 'col-lg-3 col-md-3';

$fullDisplay = isset($fullDisplay) ? $fullDisplay:true;

?>
<!-- si on edite le jeu de données pour une step de campagne , on stocke l'id du step -->
<?php if(isset($is_edit_step_case) && $is_edit_step_case): ?>
<input type="hidden" name="current_step_id" value="<?php echo $urlParameters['step_id'] ?>" id="current_step_id" />
<?php endif; ?>

<?php if( (!isset($is_edit_step_case) || !$is_edit_step_case) && (isset($is_select_data_set) && !$is_select_data_set) ): ?>
<div id="ei_scenario_menu_left" class=" no-margin col-lg-3 col-md-3" >  
    <div id="ei_data_set_menu" class="well well-small pull-left ">
<?php elseif( isset($is_select_data_set) && $is_select_data_set && $fullDisplay ): ?>
        <div class="row">
<?php endif; ?>
            <ul class="ul_menu_version nav nav-list ul_menu_dataset <?php echo $specialClass; ?>">
                <li>
                    <ul id="ei_data_set_tree" class="node_diagram">
                        <li class="lien_survol_node">
                            <input type="hidden" name="project_ref" value="<?php echo $urlParameters['project_ref'] ; ?>" id="project_ref" />
                            <input type="hidden" name="node_id" value="<?php echo $ei_data_set_root_folder->getId(); ?>" class="node_id" />
                            <input type="hidden" name="project_id" value="<?php echo $urlParameters['project_id']; ?>" id="project_id" />
                            <input type="hidden" name="root_id" value="<?php echo $ei_data_set_root_folder->getId(); ?>" class="root_id" />
                            <i class="cus-house"></i>
                            <?php echo $ei_data_set_root_folder->getName(); ?>&nbsp;&nbsp;
                            <?php if(isset($is_edit_step_case) && $is_edit_step_case ): ; ?>

                            <?php //elseif( isset($is_edit_step_case) && isset($is_select_data_set) && $is_select_data_set ): ?>

                            <?php else: ?>
                            <?php $eidataset_folder_create =$urlParameters; $eidataset_folder_create['action']='newEiDataSetFolder'  ?>
                            <a href="<?php echo url_for2("eidataset_folder_create", $eidataset_folder_create) ?>" class="add_ei_data_set_folder add_node_child" alt="New Folder" title="Create a new folder">
                               <?php echo ei_icon('ei_dataset_folder') .' '.ei_icon('ei_add') ?>
                            </a>
                            <?php $eidataset_create =$urlParameters; $eidataset_create['action']='new'  ?>
                            <a href="<?php echo url_for2("eidataset_create", $eidataset_create) ?>" class="add_ei_data_set add_node_child" alt="New data set" title="Create a new data set">
                                 <?php echo ei_icon('ei_dataset').' '. ei_icon('ei_add') ?>
                            </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <ul class="node_diagram">
                                <?php $eidataset_tree=$urlParameters;
                                $eidataset_tree['ei_scenario']=$ei_scenario;
                                $eidataset_tree['ei_data_set_children']=$ei_data_set_children;
                                $eidataset_tree['is_select_data_set']=(isset($is_select_data_set) && $is_select_data_set )? true : false;
                                $eidataset_tree['is_edit_step_case']=(isset($is_edit_step_case) && $is_edit_step_case )? true : false;
                                $eidataset_tree['current_step_id']=(isset($is_edit_step_case) && $is_edit_step_case )? $urlParameters['step_id'] : 0;

                                include_partial('eidataset/tree',array('urlParameters'=>$eidataset_tree) ); ?>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

<?php if( (!isset($is_edit_step_case) || !$is_edit_step_case) && (isset($is_select_data_set) && !$is_select_data_set) ): ?>
    </div>
</div>
<?php elseif( (isset($is_select_data_set) && $is_select_data_set) && $fullDisplay ): ?>
        <div class="col-md-1 col-lg-1 separator or-spacer-vertical left select2-display-none">
            <div class="mask">

            </div>
            <span>
                <i class="fa fa-3x fa-chevron-circle-right"></i>
            </span>
        </div>

        <p align="center" class="loaderEditDataSet hide">
            <i class="fa fa-3x fa-spinner fa-spin"></i><br />
            Loading...
        </p>

        <div class="col-md-10 col-lg-10" id="ei_data_set_content"></div>
    </div>
<?php endif; ?>






