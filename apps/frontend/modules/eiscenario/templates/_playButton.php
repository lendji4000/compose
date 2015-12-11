<?php

// Si nous sommes bien sur la page d'un scénario, on affiche le player button.
if( isset($ei_scenario) && $ei_scenario != null && $ei_scenario->getId() != "" )
{
    // Récupération des informations concernant le JDD en cookie.
    if(isset($jddScenarioToPlay) && $jddScenarioToPlay != null && isset($jddScenarioToPlay["id"])){
        $idJdd = $jddScenarioToPlay["id"];
        $nomJdd = $jddScenarioToPlay["name"];
        $deleteJdd = "&nbsp;<a href='#' id='btnEmptySelectedDataSet' title='Remove selected data set ?' data-parent='".$ei_scenario->getId()."'><i class='fa fa-remove'></i></a>";
    }
    else{
        $idJdd = null;
        $nomJdd = "No Data Set";
        $deleteJdd = "";
    }

    $urlToChooseDataSet = url_for2('getScenarioDataSets', array(
        "project_ref" => $project_ref,
        "project_id" => $project_id,
        "profile_ref" => $profile_ref,
        "profile_id" => $profile_id,
        "profile_name" => $profile_name,
        "ei_scenario_id" => $ei_scenario->getId(),
        "choose_dataset" => true
    ));

    $urlExcelRequest = url_for2("api_generate_excel_request_api", array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref
    ));

    $urlToCreateExecutionStack = url_for2("api_add_scenario_to_execution_stack", array(
        "token" => $sf_user->getEiUser()->getTokenApi(),
        "project_ref" => $project_ref,
        "project_id" => $project_id,
        "profile_ref" => $profile_ref,
        "profile_id" => $profile_id,
        "profile_name" => $profile_name,
        "ei_scenario_id" => $ei_scenario->getId(),
        "jdd_id" => "jddId",
        "device_id" => "deviceId",
        "driver_id" => "driverId",
        "browser_id" => "browserId",
        "date" => "date"
    ));

      
?>
<div class="row">
    <!--<div class="col-lg-3"></div>-->
    <ul class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <li class="usage col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="title"></div>
            <div class="bar selectedDataSetToPlay">
                <?php echo ei_icon('ei_dataset','lg') ?>
                &nbsp;<?php echo $nomJdd . $deleteJdd; ?>
            </div>
            <div class="desc">
                <a href="<?php echo $urlToChooseDataSet ?>" title="Choose data set to play scenario" class="editDataSetStepBox">
                    <?php echo ei_icon('ei_search','lg') ?>
                </a>

                <?php if( $idJdd != null ): ?>

                    <a href="<?php echo $urlExcelRequest; ?>" class="excel-open-jdd excelIcon" data-id="<?php echo $idJdd; ?>" data-toggle="popover" data-trigger="hover" data-placement="top"
                       title="Excel" data-content="Open data set into Excel">
                        <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="" width="20" title="Open data set in Excel" class="excel-icon-img disabledOracle" />
                        <?php echo sfConfig::get("app_loader_excel_button"); ?>
                    </a>
                <?php else: ?>
                    <a href="#" class="excel-open-jdd excelIcon" data-href="<?php echo $urlExcelRequest; ?>">
                        <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="" width="20" title="Open data set in Excel" class="excel-icon-img disabledOracle" />
                        <?php echo sfConfig::get("app_loader_excel_button"); ?>
                    </a>
                <?php endif; ?>
            </div>
        </li>
        <li class="subusage dropup col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <ul class="nav navbar-nav dropdown col-lg-12 col-md-12">
                <?php
                if ($sf_user->isAuthenticated() && $project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null){
                    $sf_request->setParameter('profile_id', $profile_id);
                    $sf_request->setParameter('profile_ref', $profile_ref);
                    $sf_request->setParameter('profile_name', $profile_name);
                    include_component('eiprojet', 'getProfils', array(
                        "fullMode" => true
                    ));
                }
                ?>
            </ul>
        </li>
        <li class="subusage col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="text dropup" id="blockDeviceManager" data-url="<?php echo $urlToCreateExecutionStack; ?>">
                <a href="#" title="Play scenario in web drivers robot" class="dropdown-toggle" id="btnPlayScenarioInWebDrivers" data-player-jdd="<?php echo $idJdd ?>"
                   data-player-id="<?php echo $ei_scenario->getId(); ?>" data-player-nom="<?php echo $ei_scenario->getNomScenario(); ?>" data-toggle="dropdown">
<!--                    <img src="/images/icones/playWebDriver.gif" alt="" width="22" height="22" />-->
                    <span id="deviceImgWebDrivers">
                    </span>
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu open" id="webDriverDropdown">
                    <?php
                    /*foreach( EiDeviceUserTable::getMyDevices($sf_user->getEiUser()->getUserId()) as $device ){
                        include_partial("eiscenario/deviceChoice", array("device" => $device));
                    }*/
                        EiDevice::displayDevicesList($sf_user->getEiUser()->getUserId());
                    ?>
                </ul>
            </div>

            <div class="text" id="btnSwitchExecutionMenuContainer">
                <a href="#" title="Open/Close execution menu" id="btnSwitchExecutionMenu">
                    <?php echo ei_icon('ei_user_settings', '', 'fa-2x') ?>
                </a>
            </div>
            
            <div class="text">
                <span class="hidden-sm hidden-xs hidden-md hidden-lg">Play</span>
                <a href="#" title="Play scenario" class="disabledOracle" id="btnPlayScenarioInIde" data-player-jdd="<?php echo $idJdd ?>"
                   data-player-id="<?php echo $ei_scenario->getId(); ?>" data-player-nom="<?php echo $ei_scenario->getNomScenario(); ?>">
                    <i class="fa fa-play fa-lg fa-2x"></i>
<!--                    <img src="/images/icones/playSelIde.gif" alt="" width="18" height="18" />-->
                </a>

                <?php echo sfConfig::get("app_loader_play_button"); ?>
            </div>
            
            <div class="text"> <span class="hidden-sm hidden-xs hidden-md hidden-lg">Step by Step</span>
                <a href="#" title="Debug scenario" class="disabledOracle" id="btnDebugScenarioInIde" data-player-jdd="<?php echo $idJdd ?>"
                   data-player-id="<?php echo $ei_scenario->getId(); ?>" data-player-nom="<?php echo $ei_scenario->getNomScenario(); ?>">
                    <img src="/images/boutons/btn-play-stepbystep.png" alt="Jouer en mode pas à pas" width="30" height="17" />
                </a>
            </div>

            <div class="text"> <span class="hidden-sm hidden-xs hidden-md hidden-lg">Record</span>
                <a href="#" title="Record scenario" class="disabledOracle" id="btnRecordScenarioInIde" data-player-jdd="<?php echo $idJdd ?>"
                   data-player-id="<?php echo $ei_scenario->getId(); ?>" data-player-nom="<?php echo $ei_scenario->getNomScenario(); ?>">
                    <img src="/images/boutons/btn-record.png" alt="Enregistrer son scénario" width="22" height="22" />
                </a>
            </div>
            
            <div class="text" id="btnSwitchExecutionStackPaneContainer">
                <a href="#" title="Open/Close execution stack panel" id="btnSwitchExecutionStackPane">
                    <i class="fa fa-2x fa-tasks"></i>
                </a>
            </div>
        </li>
    </ul>
</div>
<?php
}
elseif ($sf_user->isAuthenticated() && $project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null)
{
?>
    <div class="row">
        <div class="col-lg-3"></div>
        <ul class="col-lg-9">
            <li class="subusage"></li>
            <li class="subusage dropup">
                <ul class="nav navbar-nav dropdown col-lg-12 col-md-12">
                <?php
                    $sf_request->setParameter('profile_id', $profile_id);
                    $sf_request->setParameter('profile_ref', $profile_ref);
                    $sf_request->setParameter('profile_name', $profile_name);
                    include_component('eiprojet', 'getProfils', array(
                        "fullMode" => true
                    ));
                ?>
                </ul>
            </li>
            <li class="subusage">
                <div class="text pull-right" id="btnSwitchExecutionStackPaneContainer">
                    <a href="#" title="Open/Close execution stack panel" id="btnSwitchExecutionStackPane">
                        <i class="fa fa-2x fa-tasks"></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>
<?php } ?>