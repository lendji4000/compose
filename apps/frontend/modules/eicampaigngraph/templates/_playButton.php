<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
);

$urlToCreateExecutionStack = url_for2("api_add_campaign_to_execution_stack_with_positions", array(
    "token" => $sf_user->getEiUser()->getTokenApi(),
    "project_ref" => $project_ref,
    "project_id" => $project_id,
    "profile_ref" => $profile_ref,
    "profile_id" => $profile_id,
    "profile_name" => $profile_name,
    "ei_campaign_id" => "campId",
    "start" => "startPos",
    "end" => "endPos",
    "device_id" => "deviceId",
    "driver_id" => "driverId",
    "browser_id" => "browserId",
    "date" => "date"
));
?>
<div class="row">
    <!--<div class="col-lg-3"></div>-->
    <ul class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
        <li id="liOnError" class="subusage col-lg-3 col-md-3 col-sm-3 col-xs-3" title="Choose what to do on error ">

            <div class="form-group">
                <label class="col-md-1"><i class="fa fa-ambulance" ></i></label>
                <div class=" col-lg-10 col-md-10">
                    <?php $bloc_type = $ei_campaign->getEiBlockType(); ?>
                    <?php if (isset($campaignGraphBlockType)): ?>
                        <select class="CampaignBlockType form-control" name="CampaignBlockType">
                            <?php foreach ($campaignGraphBlockType as $blockType): ?>
                                <option value="<?php echo $blockType->getId() ?>"
                                    <?php if ($bloc_type != null && $bloc_type->getId() == $blockType->getId()): ?>
                                        selected="selected"
                                    <?php endif; ?>
                                    <?php $changeBlocTypeId = $url_params;
                                    $changeBlocTypeId['campaign_id']=$ei_campaign->getId();
                                    $changeBlocTypeId['block_type_id']=$blockType->getId();
                                    ?>
                                        itemref="<?php
                                        echo url_for2('changeBlocTypeId', $changeBlocTypeId)
                                        ?>">
                                    <?php echo $blockType->getName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <li id="liPlayAllCampaign" class="subusage col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="text dropup" id="blockDeviceManager" data-url="<?php echo $urlToCreateExecutionStack; ?>">
                <a href="#" title="Play scenario in web drivers robot" class="dropdown-toggle" id="btnPlayScenarioInWebDrivers"
                   data-player-id="<?php echo $ei_campaign->getId(); ?>" data-toggle="dropdown">
                    <span id="deviceImgWebDrivers">
                    </span>
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu open" id="webDriverDropdown">
                    <?php
                    /*foreach( EiResourceUserDeviceTable::getMyDevices($sf_user->getEiUser()->getUserId()) as $device ){
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
            
            <div class="text"> Play
                <a href="#" title="Play campagne" id="btnPlayCampagneInIde" data-player-id="<?php echo $ei_campaign->getId(); ?>"
                   data-player-start="-1" data-player-end="-1">
                    <i class="fa fa-play fa-lg"></i>
                </a>

                <?php echo sfConfig::get("app_loader_play_button"); ?>
            </div>

            <div class="text pull-right" id="btnSwitchExecutionStackPaneContainer">
                <a href="#" title="Open/Close execution stack panel" id="btnSwitchExecutionStackPane">
                    <i class="fa fa-2x fa-tasks"></i>
                </a>
            </div>
        </li>
    </ul>
</div>