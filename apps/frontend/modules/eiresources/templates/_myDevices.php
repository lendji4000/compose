<div class="row">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_devices') ?> My devices </h2>
            <div class="panel-actions">
            </div>
        </div>
        <div class="panel-body table-responsive" >
            <table class="table table-striped   dataTable  small-font" >
                <?php if(isset($my_devices) && count($my_devices) > 0): ?>
                <thead> 
                    <tr>
                        <th> Device name </th>
                        <th> Owner </th>
                        <th> Visibility </th>
                        <th> Device identifier </th>
                        <th> Type </th>
                        <th> Drivers & supported browsers </th>
                        <th> Actions </th>
                    </tr> 
                </thead>
                <tbody>
                    <?php foreach($my_devices as $my_device): ?>
                    <tr>
                        <td>
                            <?php echo $my_device['device_name'];?>
                        </td>
                        <td>
                            <?php echo $my_device['username'];?>
                        </td>
                        <td>
                            <?php echo $my_device['visibility'];?>
                        </td>
                        <td>
                            <?php echo $my_device['device_identifier'];?>
                        </td>
                        <td>
                            <img src="<?php echo sfConfig::get($my_device['logo_path']); ?>" width="20" height="20" />&nbsp;
                            <?php echo $my_device['name'];?>
                        </td>
                        <td>
                            <?php
                                EiDevice::displayDrivers($my_device['device_id']);
                            ?>
                        </td>
                        <td>
                            <!--<a class="btn btn-sm btn-success" role="button" data-toggle="modal" title="Edit device" href="#editDevice">
                                <?php echo ei_icon('ei_edit') ?> Edit
                            </a>-->
                            <a href="<?php echo url_for2('resourcesDeviceDisown', array('device_id' => $my_device['id'])) ?>" role="button" class="btn btn-sm btn-danger" data-toggle="modal" title="Remove device from my devices">
                                <?php echo ei_icon('ei_unlink') ?> Remove
                            </a> 
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody> 
                <?php else : ?>
                    You don't have devices affected to you
                <?php endif; ?> 
            </table>    
                <a href="#addDevice" role="button" class="btn btn-sm btn-success" data-toggle="modal" title="Add device">
                   <?php echo ei_icon('ei_add' ) ?> Add to my devices
                </a> 
        </div>
    </div>
</div>
<?php
    $edit_devices['my_devices'] = $my_devices;    
    $edit_devices['form'] = $form;
    include_partial('editDevice', $edit_devices);
?>