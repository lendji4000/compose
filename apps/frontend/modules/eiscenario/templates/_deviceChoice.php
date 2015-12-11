<?php
?>
        <li class="titleChoiceBrowser">
            <strong>Device <?php echo $device['device_name']?></strong>
        </li>
        <?php if($device['chrome']): ?>
        <li class="choice">
            <img src="<?php echo sfConfig::get("app_icone_chrome_24x24_path"); ?>" width="24" height="24" />&nbsp;
            <input type="checkbox" name="webdriversChoice[]" value="<?php echo $device['id']?>/Chrome" id="<?php echo $device['id'] ?>Chrome" class="hide" />&nbsp;
            <label for="<?php echo $device['id'] ?>/Chrome">Chrome</label>
        </li>
        <?php endif; ?> 
       