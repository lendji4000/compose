<?php

/**
 * EiDevice
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    kalifastRobot
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EiDevice extends BaseEiDevice
{
    /**
     * Permet d'afficher les drivers et browsers supportés par ceux-ci pour un device donné
     * @param type $device_id
     */
    public static function displayDrivers($device_id)
    {
        $device_drivers = Doctrine_Core::getTable('EiDeviceDriver')->findBy('device_id', $device_id);
        foreach($device_drivers as $device_driver)
        {
            $driver = Doctrine_Core::getTable('EiDriverType')->findOneBy('id', $device_driver['driver_type_id']);
            ?>
            <img src="<?php echo sfConfig::get($driver['logo_path']); ?>" width="20" height="20" />&nbsp;
            <strong><?php echo $driver['name'] . ' : '; ?></strong>
            <?php
            $driverBrowsers = Doctrine_Core::getTable('EiDriverBrowser')->findBy('device_driver_id', $device_driver['id']);
            foreach($driverBrowsers as $driverBrowser)
            {
                $browser = Doctrine_Core::getTable('EiBrowserType')->findOneBy('id', $driverBrowser['browser_type_id']);
                ?>
                <img src="<?php echo sfConfig::get($browser['logo_path']); ?>" width="20" height="20" />&nbsp;
                <?php
            }
            echo '<br/>';
        }
    }
    
    /**
     * Permet d'afficher la liste des devices et drivers à côté du play
     * @param type $user_id
     */
    public static function displayDevicesList($user_id)
    {
        $driversTab = array();
        $myDevicesId = EiDeviceUserTable::getMyDevicesId($user_id);
        ?>
        <li class="titleChoice titleChoiceDevices">
            <strong>Devices</strong>
            <?php
            if(count($myDevicesId) > 0){
                echo ei_icon('ei_add_square', null, 'iconExpand');
            }
            ?>
        </li>
        <?php
        foreach ($myDevicesId as $myDeviceId)
        {
            $device_user = Doctrine_Core::getTable('EiDeviceUser')->findOneBy('id', $myDeviceId['id']);
            $device =  $device_user->getEiDevice();
            $device_type = $device->getEiDeviceType();
            $device_drivers = $device->getEiDeviceDriver();
            ?>
            <li class="titleChoice titleChoiceBrowsers">
                &nbsp;&nbsp;
                <img src="<?php echo sfConfig::get($device_type['logo_path']); ?>" width="20" height="20" />&nbsp;
                <strong><?php echo $device_user['name']?></strong>
                <?php
                if(count($device_drivers) > 0){
                    echo ei_icon('ei_add_square', null, 'iconExpand');
                }
                ?>
            </li>
            <?php
            foreach($device_drivers as $device_driver)
            {
                $driver = $device_driver->getEiDriverType();
                $driverBrowsers = $device_driver->getEiDriverBrowser();
                foreach ($driverBrowsers as $driverBrowser)
                {
                    $browser = $driverBrowser->getEiBrowserType();
                ?>
                <li class="choice">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="webdriversChoice[]" value="<?php echo $device['id']; ?>/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>" id="<?php echo $device['id']; ?>/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>" class="hide" />&nbsp;
                    <?php
                    if($driver['hidden_name'] == 'selenium_ide')
                    {
                        ?>
                        <label for="<?php echo $device['id']; ?>/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>"><?php echo $driver['name']; ?></label>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($driver['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                    else
                    {
                        ?>
                        <label for="<?php echo $device['id']; ?>/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>"><?php echo $browser['name']; ?></label>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($browser['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                    ?>
                </li>
                <?php
                    $driverRow = array($driver['id'], $browser['id']);
                    if(!in_array($driverRow, $driversTab))
                    {
                        $driversTab[] = $driverRow;
                    }
                }
            }
        }
        ?>
        <li class="titleChoice titleChoiceDrivers">
            <strong>Browsers</strong>
            <?php
            if(count($driversTab) > 0){
                echo ei_icon('ei_add_square', null, 'iconExpand');
            }
            ?>
        </li>
        <?php
        foreach($driversTab as $driverRow)
        {
            $driver = Doctrine_Core::getTable('EiDriverType')->findOneBy('id', $driverRow[0]);
            $browser = Doctrine_Core::getTable('EiBrowserType')->findOneBy('id', $driverRow[1]);
            ?>
                <li class="choice">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="webdriversChoice[]" value="null/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>" id="null/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>" class="hide" />&nbsp;
                    <?php
                    if($driver['hidden_name'] == 'selenium_ide')
                    {
                        ?>
                        <label for="null/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>"><?php echo $driver['name']; ?></label>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($driver['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                    else
                    {
                        ?>
                        <label for="null/<?php echo $driver['id']; ?>/<?php echo $browser['id']; ?>"><?php echo $browser['name']; ?></label>
                        <img class="driverBrowserLogo" src="<?php echo sfConfig::get($browser['logo_path']); ?>" width="24" height="24" />&nbsp;
                        <?php
                    }
                    ?>
                </li>
            <?php
        }
        ?>
        <li id="selLi" class="choice">
            <input type="checkbox" name="webdriversChoice[]" value="<?php echo DevicesConst::SELENIUM_IDE; ?>" id="<?php echo DevicesConst::SELENIUM_IDE; ?>" class="hide">&nbsp;
            <label for="<?php echo DevicesConst::SELENIUM_IDE; ?>">Local <?php echo DevicesConst::getTitle(DevicesConst::SELENIUM_IDE); ?></label>
            <img class="driverBrowserLogo" src="<?php echo DevicesConst::getImgPath(DevicesConst::SELENIUM_IDE); ?>" class="btnSwitchDevice" for="<?php echo DevicesConst::SELENIUM_IDE; ?>" width="24" height="24">
        </li>
        <?php
    }
}