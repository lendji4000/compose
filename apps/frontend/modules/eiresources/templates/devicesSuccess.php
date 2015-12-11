<?php
    $url_tab = array();
?>
<?php if ($sf_user->hasFlash('alert_form')): $alert_tab = $sf_user->getFlash('alert_form'); ?>  
    <div id="alertBox">
        <div class="alert <?php echo $alert_tab['class'] ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong><?php echo $alert_tab['title'] ?> !</strong> <?php echo $alert_tab['text'] ?>
        </div>
    </div>
<?php endif; ?> 
<?php
    $my_devices_list = $url_tab;
    
    $my_devices_list['form'] = $form;
    $my_devices_list['my_devices'] = $my_devices;
    include_partial('myDevices', $my_devices_list);
        
    $available_devices_list = $url_tab;
    $available_devices_list['available_devices'] = $available_devices;
    $available_devices_list['form'] = $form;
    include_partial('addDevice', $available_devices_list);
?>