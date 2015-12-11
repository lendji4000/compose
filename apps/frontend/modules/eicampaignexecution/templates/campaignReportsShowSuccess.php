<?php

// On génère la requête permettant de faire une demande d'ouverture d'Excel.
$urlExcelRequest = url_for2("api_generate_excel_request_api", array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref
));

/** @var EiCampaignExecution $campaign_execution */
?>
<?php
$nbSuccess = 0;
$nbFailed = 0;
$nbAborted = 0;
$nbEtapes = 0;

foreach($graphs as $graph){
    $tsId = $graph->getEiTestSet()->getId();
    $isTs = $tsId != "" && isset($graphsResults[$tsId]) ? true:false;
    $statut = ( $isTs && isset($graphsResults[$tsId]) && isset($graphsResults[$tsId]["status_nom"]) ) ? strtolower($graphsResults[$tsId]["status_nom"]):false;

    if( $statut == StatusConst::STATUS_OK ){
        $nbSuccess++;
    }
    elseif( $statut == StatusConst::STATUS_KO ){
        $nbFailed++;
    }
    elseif( $statut == StatusConst::STATUS_NA ){
        $nbAborted++;
    }

    $nbEtapes++;
}

// Récupération des statuts.
$statutSuccess = null;
$statutFailed = null;
$statutAborted = null;

/** @var EiTestSetState $state */
foreach( $states as $state ){
    $stateCode = strtolower($state->getStateCode());

    if( $stateCode == StatusConst::STATUS_OK_DB ){
        $statutSuccess = $state;
    }
    elseif( $stateCode == StatusConst::STATUS_KO_DB ){
        $statutFailed = $state;
    }
    elseif( $stateCode == StatusConst::STATUS_ABORTED_DB ){
        $statutAborted = $state;
    }
}
?>

<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_testset') ?>&nbsp;Execution Informations</h2>
        <div class="panel-actions">
        </div>
    </div>

    <div class="panel-body table-responsive" >
        <table class="table table-striped bootstrap-datatable dataTable">
            <thead>
            <tr>
                <th>N°</th>
                <th>On Error</th>
                <th>Profil</th>
                <th>Execution Date</th>
                <th>By</th>
                <th>Time</th>
                <th>Executed Step Count</th>
                <th>Selected Step Count</th>
                <th>Campaign Step Count</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $profileSlug = $campaign_execution->getProfileId() . "_" . $campaign_execution->getProfileRef(); ?>
            <?php $profileUsed = isset($ProjectProfilesArray[$profileSlug]) ? $ProjectProfilesArray[$profileSlug]:""; ?>
            <tr>
                <td><?php echo $campaign_execution->getId() ?></td>
                <td><?php echo $campaign_execution->getOnError() != null ? $campaign_execution->getEiBlockType()->getName():"Continue"; ?></td>
                <td><?php echo $profileUsed ?></td>
                <td><?php echo $campaign_execution->getCreatedAt() ?></td>
                <td><?php echo $campaign_execution->getAuthorUsername() ?></td>
                <td><?php echo gmdate("H:i:s", $campaign_execution->getDuree()/1000); ?></td>
                <td>
                    <?php echo $campaign_execution->getNbEtapesExecutees() . ' (' . number_format(($campaign_execution->getNbEtapesExecutees()/$campaign_execution->getNbEtapesExecution()) * 100, 2) . '%)'; ?>
                </td>
                <td>
                    <?php echo $nbEtapes ?>
                </td>
                <td>
                    <?php echo $campaign_execution->getNbEtapesCamp(); ?>
                </td>
                <td>
                    <span style="background-color:<?php echo $campaign_execution->getStatusColor() ?> " class="label">
                        <?php echo $campaign_execution->getStatusName(); ?>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer"></div>
</div>

<br />
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" data-type="state-success">
        <div class="info-box success" style="background-color: <?php echo $statutSuccess->getColorCode() ?>; border-color: <?php echo $statutSuccess->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbSuccess*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbSuccess." total success " ?></div>
            <div class="title"><?php echo $nbEtapes." total selected " ?></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" data-type="state-failed">
        <div class="info-box danger" style="background-color: <?php echo $statutFailed->getColorCode() ?>; border-color: <?php echo $statutFailed->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbFailed*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbFailed." total failed " ?></div>
            <div class="title"><?php echo $nbEtapes." total selected " ?></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" data-type="state-aborted">
        <div class="info-box warning" style="background-color: <?php echo $statutAborted->getColorCode() ?>; border-color: <?php echo $statutAborted->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbAborted*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbAborted." total aborted " ?></div>
            <div class="title"><?php echo $nbEtapes." total selected " ?></div>
        </div>
    </div>
</div>
<br />


<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_list') ?>&nbsp;Steps Details</h2>
        <div class="panel-actions">
        </div>
    </div>

    <div class="panel-body table-responsive" >
        <table class="table table-striped bootstrap-datatable dataTable">
            <thead>
            <tr>
                <td>N°Step</td>
                <td>Scenario</td>
                <td>Scenario's Version</td>
                <td>Data Set Version ID</td>
                <td>Data Set Name</td>
                <td>Time</td>
                <td>Status</td>
                <td>Actions</td>
            </tr>
            </thead>
            <tbody>
            <?php /** @var EiCampaignExecutionGraph $graph */ ?>
            <?php foreach( $graphs as $graph ): ?>
                <?php
                $tsId = $graph->getEiTestSet()->getId();
                $isTs = $tsId != "" && isset($graphsResults[$tsId]) ? true:false;
                $stateName = ( $isTs && isset($graphsResults[$tsId]) && isset($graphsResults[$tsId]["status_color"]) ) ? $graphsResults[$tsId]["status_nom"]:"";
                ?>
            <tr class="state-<?php echo strtolower($stateName); ?>">
                <td><?php echo $graph->getPosition(); ?></td>
                <td>
                <?php
                if( $graph->getEiScenario() != null && $graph->getEiScenario()->getId() != "" )
                {
                    echo $graph->getEiScenario()->getNomScenario();
                }
                ?>
                </td>
                <td>
                    <?php
                    if( $graph->getEiVersion() != null && $graph->getEiVersion()->getId() != "" )
                    {
                        echo link_to2($graph->getEiVersion()->getLibelle(), "projet_edit_eiversion", array(
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'profile_name' => EiProfil::slugifyProfileName($profile_name),
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'ei_scenario_id' => $graph->getEiScenario()->getId(),
                            'ei_version_id' => $graph->getEiVersion()->getId(),
                            'action' => "edit"
                        ));
                    }
                    ?>
                </td>
                <td>
                    <?php if( $graph->getEiDataSet() != null ):?>
                        <?php echo $graph->getEiDataSet()->getId() ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if( $graph->getEiDataSet() != null ):?>
                        <?php echo $graph->getEiDataSet()->getEiDataSetTemplate()->getName() ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    if( $isTs && isset($graphsResults[$tsId]) && isset($graphsResults[$tsId]["duree"]) )
                    {
                        $duree = $graphsResults[$tsId]["duree"];
                        $duree = intval($duree);

                        echo gmdate("H:i:s", $duree/1000);
                    }
                    ?>
                </td>
                <td>
                    <?php if( $isTs && isset($graphsResults[$tsId]) && isset($graphsResults[$tsId]["status_color"]) ): ?>
                    <span style="background-color:<?php echo $graphsResults[$tsId]["status_color"] ?> " class="label">
                        <?php echo $graphsResults[$tsId]["status_nom"]; ?>
                    </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if( $isTs ): ?>

                    <?php echo link_to2(    ei_icon('ei_show'), 'eitestset_oracle', array(
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'ei_scenario_id' => $graph->getEiScenario()->getId(),
                            'ei_test_set_id' => $graph->getEiTestSet()->getId(),
                            'profile_name' => EiProfil::slugifyProfileName($profile_name),
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref
                        ), array(
                            'target'=> '_blank',
                            'title' => 'Oracle'
                        ));
                    ?>

                    &nbsp;&nbsp;
                    <?php
                    echo link_to2('<i class="fa fa-code fa-lg"></i>', "eitestset_oracle_download", array(
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'ei_scenario_id' => $graph->getEiScenario()->getId(),
                        'ei_test_set_id' => $graph->getEiTestSet()->getId(),
                        'sf_format' => "xml",
                        'profile_name' => EiProfil::slugifyProfileName($profile_name),
                        'profile_id' => $profile_id,
                        'profile_ref' => $profile_ref
                    ), array(
                        'target'=> '_blank',
                        'title' => 'XML logs'
                    ))
                    ?>
                    &nbsp;&nbsp;
                    <a href="<?php echo $urlExcelRequest; ?>" data-id="<?php echo $graph->getEiTestSet()->getId() ?>" title="Excel" class="excel-open-logs excelIcon">
                        <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="" width="20" title="Open logs in Excel" class="excel-icon-img disabledOracle" />
                        <?php echo sfConfig::get("app_loader_excel_button"); ?>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer"></div>
</div>

<?php include_component("eicampaign", "playerInstanciator"); ?>