<?php
    $url_tab = array(); 
?> 
<div class="row" id="eisge-object">
    <h2>
        <i class="fa fa-cubes"></i> 
        <span class="text" title="Resources">   
            <strong>Resources</strong> 
        </span> 
    </h2>
</div>
<div class="row" id="eisge-object-actions">
    <ul class="nav nav-tabs" role="tablist">
        <li class="<?php if(isset($act) && ($act=='download')): echo 'active' ; endif; ?>">
            <?php $resourcesUri=$url_tab; $resourcesUri['action']='download' ?>
            <a class="btn btn-sm" id="accessDownloadResources" href="<?php  echo url_for2('resources', $resourcesUri) ?>">
                 <?php echo ei_icon('ei_download') ?> <span class="text"> Download Resources </span>  
            </a>
        </li> 
        <li class="<?php if(isset($act) && ($act=='devices')): echo 'active' ; endif; ?>">
            <?php $resourcesUri=$url_tab; $resourcesUri['action']='devices' ?>
            <a  id="accessDevices" class="btn btn-sm" href="<?php  echo url_for2('resourcesDevices', $resourcesUri) ?>">
                <?php echo ei_icon('ei_devices') ?> <span class="text"> Devices </span>    
            </a>
        </li> 
    </ul>
</div> 