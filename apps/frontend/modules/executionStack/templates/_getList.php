<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name)
;

    if( count($stackList) == 0 ):
?>
    <p>Nothing to execute.</p>
<?php else: ?>
    <?php /** @var EiExecutionStack $elt */ ?>
    <?php foreach( $stackList as $elt ):
            if( $elt->isCampaign() ){
                $type = "Campaign";
                $objectUrlAccess = $url_params;
                $objectUrlAccess['campaign_id'] = $elt->getEiCampaignId();

                $objectUrl = url_for2("graphHasChainedList", $objectUrlAccess);
                $objectTitle = ei_icon("ei_campaign") . "&nbsp;C" . $elt->getEiCampaignId() . "/" . $elt->getEiCampaign()->getName();
            }
            else{
                $type = "Scenario";
                $objectUrlAccess = $url_params;
                $objectUrlAccess['ei_scenario_id'] = $elt->getEiScenarioId();
                $objectUrlAccess['action'] = 'editVersionWithoutId';

                $objectUrl = url_for2("projet_new_eiversion", $objectUrlAccess);
                $objectTitle = ei_icon("ei_scenario") . "&nbsp;SC" . $elt->getEiScenarioId(). "/" . $elt->getEiScenario()->getNomScenario();

                if( $elt->getEiDataSetId() != "" ){
                    $objectTitle .= " / " . ei_icon("ei_dataset") . " " . $elt->getEiDataSet()->getName();
                }
                
            }
            /* Informations sur le device / driver / browser sur lequel le scénario est lancé */
            if($elt->getDeviceId() != 0)
            {
                $deviceUser = Doctrine_Core::getTable("EiDeviceUser")->findOneBy("device_id", $elt->getDeviceId());
                $device = Doctrine_Core::getTable("EiDevice")->findOneBy("id", $elt->getDeviceId());
                $deviceType = $device->getEiDeviceType(); 
            } 
            if( $elt->getDriverId()!=null && $elt->getBrowserId()!=null):
            $driverType = Doctrine_Core::getTable("EiDriverType")->findOneBy("id", $elt->getDriverId());
            $browserType = Doctrine_Core::getTable("EiBrowserType")->findOneBy("id", $elt->getBrowserId());
            endif; 
    ?>
    <div style="margin-left: 10px">
        <h3>
            <a href="<?php echo $objectUrl; ?>" title="Access to <?php echo $type; ?> element.">
            <?php echo $objectTitle ?>
            </a>
        </h3>
        <h5 style="margin-left: 20px;">
            Created at <?php echo $elt->getCreatedAt() ?><br /><br />

            <?php if( StatusConst::STATUS_PROCESSING_DB == $elt->getStatus() ): ?>
                Supported at <?php echo $elt->getUpdatedAt() ?><br /><br />
            <?php endif; ?>
            
            <?php if( $elt->getExpectedDate() != 0 && $elt->getExpectedDate() != null ): ?>
                Expected date : <?php echo $elt->getExpectedDate() ?><br /><br />
            <?php endif; ?>
                
            Status :
            <?php if( StatusConst::STATUS_NA_DB == $elt->getStatus() ): ?>
                <span class="label label-info">Queued</span>
            <?php elseif( StatusConst::STATUS_PROCESSING_DB == $elt->getStatus() ): ?>
                <span class="label label-warning">Processing</span>
            <?php elseif( StatusConst::STATUS_OK_DB == $elt->getStatus() ): ?>
                <span class="label label-success">Success</span>
            <?php elseif( StatusConst::STATUS_KO_DB == $elt->getStatus() ): ?>
                <span class="label label-danger">Failed</span>
            <?php elseif( StatusConst::STATUS_ABORTED_DB == $elt->getStatus() ): ?>
                <span class="label label-danger">Aborted</span>
            <?php endif; ?>
            <?php if (null!=$elt->getDeviceId() || null!=$elt->getDriverId() || null!=$elt->getBrowserId()): ?>
                <br/><br/>
                On :
                <?php
                if($elt->getDeviceId() != null)
                {
                ?>
                <img src="<?php echo sfConfig::get($deviceType['logo_path']); ?>" width="24" height="24" />&nbsp;
                <?php echo $deviceUser['name']?>
                <?php
                }
                ?>
                <?php
                if (null!=$elt->getDriverId())
                {
                    $driver = Doctrine_Core::getTable("EiDriverType")->findOneBy("id", $elt->getDriverId());
                    $browser = Doctrine_Core::getTable("EiBrowserType")->findOneBy("id", $elt->getBrowserId());
                    if($driver['hidden_name'] == 'selenium_ide')
                    {
                        ?>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($driver['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                    else
                    {
                        ?>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($browser['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                }
                ?>
            <?php endif; ?>
        </h5>
    </div>
    <hr />
    <?php endforeach; ?>
<?php endif; ?>