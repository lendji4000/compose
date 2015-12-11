<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php
    $url_form='resourcesDeviceCreate';
?>  
<form action="<?php echo url_for2($url_form) ?>" method="post" id="addDeviceForm">      
    <div id="addDevice" class="modal " tabindex="-1" role="dialog" aria-labelledby="addDevice" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                     <h3>Add a device to my devices</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <?php echo $form->renderHiddenFields() ?> 
                    <?php echo $form->renderGlobalErrors() ?> 
                    </div>  
                    <div class="row">
                        <div class=" form-group">
                            <label class="control-label col-md-4" for="name">Name</label>
                            <div class="col-md-8"> 
                                <?php echo $form['name']->renderError() ?>
                                <?php echo $form['name'] ?>
                            </div>
                        </div>
                        <br/>
                        <div class=" form-group">
                             <label class="control-label col-md-4" for="deviceVisibility">Device visibility</label>
                             <div class="col-md-8"> 
                                <?php echo $form['device_user_visibility_id']->renderError() ?>
                                <?php echo $form['device_user_visibility_id'] ?>
                             </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default eiPanel">
                            <div class="panel-heading">
                                <h2><?php echo ei_icon('ei_devices') ?> Available devices </h2>
                                <div class="panel-actions"> 
                                </div>
                            </div>
                            <div class="panel-body table-responsive" >
                                <table class="table table-striped   dataTable  small-font" >
                                    <?php if(isset($available_devices) && count($available_devices) > 0): ?>
                                    <thead> 
                                        <tr>
                                            <th> Device identifier </th>
                                            <th> Type </th>
                                            <th> Drivers & supported browsers </th>
                                            <th> Select </th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach($available_devices as $available_device): ?>
                                        <tr>
                                            <td>
                                                <?php echo $available_device['device_identifier'];?>
                                            </td>
                                            <td>
                                                <img src="<?php echo sfConfig::get($available_device['logo_path']); ?>" width="20" height="20" />&nbsp;
                                                <?php echo $available_device['name'];?>
                                            </td>
                                            <td>
                                                <?php
                                                    EiDevice::displayDrivers($available_device['id']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $form['device_id']->renderError() ?>
                                                <input name="ei_device_user[device_id]" type="radio" value="<?php echo $available_device['id'];?>" id="ei_device_user_device_id_<?php echo $available_device['id'];?>">
                                            </td>
                                        </tr> 
                                        <?php endforeach; ?>
                                    </tbody> 
                                    <?php else : ?>
                                        There are no available devices
                                    <?php endif; ?> 
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" type="submit"></i> Add</button>
                    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>