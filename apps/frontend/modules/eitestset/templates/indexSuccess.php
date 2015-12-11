<?php

    // On détermine le nombre de reporting qu'il y a.
    $nbReports = isset($EiScenarioTestsSet) ? count($EiScenarioTestsSet):0;
    $nbSuccess = 0;
    $nbFailed = 0;
    $nbAborted = 0;
    $nbEtapes = 0;

    /** @var EiTestSet $tests_set */
    foreach ($EiScenarioTestsSet as $tests_set):
        $statut = strtolower($tests_set->getStatusName());

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

    // On génère la requête permettant de faire une demande d'ouverture d'Excel.
    $urlExcelRequest = url_for2("api_generate_excel_request_api", array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref
    ));

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
        <table  class="table table-striped  bootstrap-datatable small-font dataTable " id="EiPaginateReportsList">
            <thead>
              <tr>
                  <th>N°</th>
                  <th>Scenario's Version</th>
                  <th>Mode</th>
                  <th>Profil</th>
                  <th>Data Set Version ID</th>
                  <th>Data Set Name</th>
                  <th>Execution Date</th>
                  <th>By</th>
                  <th>Time</th>
                  <th>% Executed</th>
                  <th>Device/Browser</th>
                  <th>Status</th>
                  <th>Oracle</th>
              </tr>
            </thead>
            <tbody>
              <?php /** @var EiTestSet $tests_set */ ?>
              <?php foreach ($EiScenarioTestsSet as $tests_set): ?>
                  <?php $profileSlug = $tests_set->getProfileId() . "_" . $tests_set->getProfileRef(); ?>
                  <?php $profileUsed = isset($ProjectProfilesArray[$profileSlug]) ? $ProjectProfilesArray[$profileSlug]:""; ?>
              <tr class="state-<?php echo strtolower($tests_set->getStatusName()); ?>">
                  <td>
                      <a href="#">
                          <?php echo $tests_set->getId() ?>
                      </a>
                  </td>
                  <td>
                      <?php
                      echo link_to2($tests_set->getEiVersion()->getLibelle(), "projet_edit_eiversion", array(
                          'project_id' => $project_id,
                          'project_ref' => $project_ref,
                          'profile_name' => EiProfil::slugifyProfileName($profile_name),
                          'profile_id' => $profile_id,
                          'profile_ref' => $profile_ref,
                          'ei_scenario_id' => $tests_set->getEiScenarioId(),
                          'ei_version_id' => $tests_set->getEiVersionId(),
                          'action' => "edit"
                      ));
                      ?>
                  </td>
                  <td>
                      <?php echo $tests_set->getRealMode(); ?>
                  </td>
                  <td>
                      <?php echo $profileUsed ?>
                  </td>
                  <td>
                      <?php if( $tests_set->getEiDataSetId() != "" ):?>
                          <?php echo $tests_set->getEiDataSetId() ?>
                      <?php endif; ?>
                  </td>
                  <td>
                      <?php if( $tests_set->getEiDataSetId() != "" ):?>
                          <?php echo $tests_set->getEiDataSet()->getEiDataSetTemplate()->getName() ?>
                      <?php endif; ?>
                  </td>
                  <td><?php echo $tests_set->getCreatedAt() ?></td>
                  <td><?php echo $tests_set->getSfGuardUser()->getUsername() ?></td>
                  <td>
                      <?php echo gmdate("H:i:s", $tests_set->getDuree()/1000); ?>
                  </td>
                  <td>
                      <?php
                      if( $tests_set->getNbFct() > 0 ):
                          echo number_format(($tests_set->getNbFctExecutees()/$tests_set->getNbFct()) * 100, 2) . '%';
                      else:
                          echo '-';
                      endif;
                      ?>
                  </td>
                  <td>
                      <?php $tests_set->displayDeviceBrowserData(); ?>
                  </td>
                  <td>
                      <span style="background-color:<?php echo $tests_set->getStatusColor() ?> " class="label">
                          <?php echo $tests_set->getStatusName(); ?>
                      </span>
                  </td>
                  <td>
                    <?php

                      echo link_to2(    ei_icon('ei_show'), 'eitestset_oracle', array(
                          'project_id' => $project_id,
                          'project_ref' => $project_ref,
                          'ei_scenario_id' => $ei_scenario_id,
                          'ei_test_set_id' => $tests_set->getId(),
                          'profile_name' => EiProfil::slugifyProfileName($profile_name),
                          'profile_id' => $profile_id,
                          'profile_ref' => $profile_ref
                      ), array(
                          'target'=> '_blank',
                          'title' => 'Oracle'
                      ))
                    ?> 
                    &nbsp;&nbsp;
                    <?php
                      echo link_to2('<i class="fa fa-code fa-lg"></i>', "eitestset_oracle_download", array(
                          'project_id' => $project_id,
                          'project_ref' => $project_ref,
                          'ei_scenario_id' => $ei_scenario_id,
                          'ei_test_set_id' => $tests_set->getId(),
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
                    <a href="<?php echo $urlExcelRequest; ?>" data-id="<?php echo $tests_set->getId() ?>" title="Excel" class="excel-open-logs excelIcon">
                        <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="" width="20" title="Open logs in Excel" class="excel-icon-img disabledOracle" />
                        <?php echo sfConfig::get("app_loader_excel_button"); ?>
                    </a>
                  </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table> 
    </div>      
</div>
 