<?php

/** @var EiDataSetTemplate $ei_data_set */
include_component('eidataset','sideBarHeaderObject', array(
    "ei_data_set" => $ei_data_set->getEiDataSet() != null && $ei_data_set->getEiDataSet()->getId() != "" ?
            $ei_data_set->getEiDataSet():$ei_data_set
));

?>
<div class="tab-content form">
    <div class="tab-pane active" id="datasetProperties">
        <?php include_partial("form", array(
            "form" => $form,
            "urlParameters" => $urlParameters,
            "ei_scenario" => $ei_scenario,
            "is_select_data_set" => $is_select_data_set
        )); ?>
    </div>

    <div id="datasetSource" class="tab-pane">
        <?php include_partial("sources", array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'profile_name' => $profile_name,
            'treeDisplay' => $treeDisplay
        ));
        ?>
    </div>

    <div id="datasetVersions" class="tab-pane">
        <?php include_partial("versions", array(
            "versions" => $form->getObject()->getEiDataSetsReverse(),
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'profile_name' => $profile_name
        ));
        ?>
    </div>

<!--    <div id="datasetReports" class="tab-pane">-->
<!--        Mes jeux de tests-->
<!--    </div>-->
</div>