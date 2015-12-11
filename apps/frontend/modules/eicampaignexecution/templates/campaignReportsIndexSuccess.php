<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);  
?> 

<?php

// On détermine le nombre de reporting qu'il y a.
$nbReports = isset($campaignExecutions) ? count($campaignExecutions):0;
$nbSuccess = 0;
$nbFailed = 0;
$nbAborted = 0;
$nbEtapes = 0;

/** @var EiCampaignExecution $execution */
foreach ($campaignExecutions as $execution):
    $statut = strtolower($execution->getStatusName());

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
endforeach;

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

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="info-box success" style="background-color: <?php echo $statutSuccess->getColorCode() ?>; border-color: <?php echo $statutSuccess->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbSuccess*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbSuccess." total success " ?></div>
            <div class="title"><?php echo $nbEtapes." total executed " ?></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="info-box danger" style="background-color: <?php echo $statutFailed->getColorCode() ?>; border-color: <?php echo $statutFailed->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbFailed*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbFailed." total failed " ?></div>
            <div class="title"><?php echo $nbEtapes." total executed " ?></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="info-box warning" style="background-color: <?php echo $statutAborted->getColorCode() ?>; border-color: <?php echo $statutAborted->getColorCode() ?>">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($nbAborted*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $nbAborted." total aborted " ?></div>
            <div class="title"><?php echo $nbEtapes." total executed " ?></div>
        </div>
    </div>
</div>

<br />

<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2> <?php echo ei_icon('ei_testset') ?>
            Reports (<?php echo $nbReports; ?>)
        </h2>
        <div class="panel-actions"></div>
    </div>
    <div class="panel-body table-responsive">
        <table  class="table table-striped  bootstrap-datatable small-font   dataTable " id="EiPaginateReportsList">
            <thead>
            <tr>
                <th>N°</th>
                <th>On Error</th>
                <th>Profil</th>
                <th>Execution Date</th>
                <th>By</th>
                <th>Time</th>
                <th>Step Count Selected</th>
                <th>Status</th>
                <th >Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var EiCampaignExecution $execution */ ?>
            <?php foreach ($campaignExecutions as $execution): ?>
                <?php $profileSlug = $execution->getProfileId() . "_" . $execution->getProfileRef(); ?>
                <?php $profileUsed = isset($ProjectProfilesArray[$profileSlug]) ? $ProjectProfilesArray[$profileSlug]:""; ?>
                <tr>
                    <td><?php echo $execution->getId(); ?></td>
                    <td>
                        <?php echo $execution->getOnError() != null ? $execution->getEiBlockType()->getName():"Continue"; ?>
                    </td>
                    <td><?php echo $profileUsed; ?></td>
                    <td><?php echo $execution->getCreatedAt(); ?></td>
                    <td><?php echo $execution->getAuthorUsername(); ?></td>
                    <td>
                        <?php echo gmdate("H:i:s", floor($execution->getDuree()/1000)); ?>
                    </td>
                    <td>
                        <?php
                        if( $execution->getNbEtapesCamp() > 0 ):
                            echo $execution->getNbEtapesExecution() . '/' . $execution->getNbEtapesCamp() . ' (' .
                                number_format(($execution->getNbEtapesExecution()/$execution->getNbEtapesCamp()) * 100, 2) . '%)';
                        else:
                            echo '-';
                        endif;
                        ?>
                    </td>
                    <td>
                      <span style="background-color:<?php echo $execution->getStatusColor() ?> " class="label">
                          <?php echo $execution->getStatusName(); ?>
                      </span>
                    </td>
                    <td>
                        <?php

                        echo link_to2(    ei_icon('ei_show'), 'showCampaignExecutions', array(
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'profile_name' => EiProfil::slugifyProfileName($profile_name),
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'campaign_id' => $ei_campaign->getId(),
                            'campaign_execution_id' => $execution->getId()
                        ), array(
                            'target'=> '_blank',
                            'title' => 'Show details'
                        ))
                        ?>
                    &nbsp; &nbsp; 
                    <?php  $statistics=$url_params;  ?>
                    <?php  $statistics['campaign_id']=$ei_campaign->getId();  ?>
                    <?php  $statistics['campaign_execution_id']=$execution->getId(); $statistics['action']="statistics";  ?> 
                        <a href="<?php echo url_for2("execution_stats", $statistics);?>" class="accessExecutionStats" title="Execution statistics" target="_blank">
                            <?php echo ei_icon('ei_stats') ?> 
                        </a> 
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>