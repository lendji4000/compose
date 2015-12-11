<?php if(isset($ei_project) ): ?>
<?php $mod=$sf_request->getParameter('module'); 
$act=$sf_request->getParameter('action')?>
<div class="row" id="eisge-object"> 
     <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
        <h2>
            <i class="fa fa-gears "></i>
            <span class="text" title=" User Settings" > User Settings   </span> 
        </h2>    
</div>
<div class="row" id="eisge-object-actions">
    <ul  class="nav nav-tabs" role="tablist"> 
        <li class="<?php echo (($mod=='eiuser' && $act=="index")?'active':'') ?>"> 
            <?php echo link_to2('<i class="fa fa-gears"></i> Browser Settings', 'default', array('module' => 'eiuser', 'action' => 'index'), 
                                array('title' => 'User Settings',
                                    'class' => 'btn btn-sm',
                                    'title'=>'User Settings')); ?>
        </li>
        <li class="<?php echo (($mod=='eiuser' && $act=="setDefaultPackage")?'active':'') ?>"> 
            <a class="btn btn-sm" href="<?php echo url_for2('setDefaultPackage',array(
            'action' =>'setDefaultPackage',
            'project_id' => $ei_project->getProjectId(),
                'project_ref' => $ei_project->getRefId())) ?>" title="Define default package">
                <i class="fa fa-apple"></i> Define default package
            </a>
        </li> 
        <li  class="<?php echo (($mod=='eiuser' && ($act=="projectProfiles" || $act=="setUserDefaultProfile"))?'active':'') ?>"> 
            <a id="projectProfilesLink" class="btn btn-sm" href="<?php echo url_for2('projectProfiles',
                    array('project_id' => $ei_project->getProjectId(),
                          'project_ref' => $ei_project->getRefId(),
                          'action' => 'projectProfiles')) ?>" title="Project Environments ">
                <?php echo ei_icon("ei_profile") ?> Environments
            </a>
        </li>
<!--        <li  class="<?php //echo (($mod=='eiuserparam' && $act=="index")?'active':'') ?>"> 
            <a id="goToUserParams" class="btn btn-sm" href="<?php //echo url_for('eiuserparam/index') ?>" title="User parameters">
                <i class="fa fa-gear"></i> User parameters
            </a>
        </li>-->
        <li  class="<?php echo (($mod=='eiuserprofileparam')?'active':'') ?>"> 
            <a  id="goToUserProfileParams" class="btn btn-sm" href="<?php echo url_for('userProfileParam',array(
                'action' =>'index',
                'project_id' => $ei_project->getProjectId(),
                'project_ref' => $ei_project->getRefId()
            )) ?>" title="Overwrite environment parameters">
                <i class="fa fa-database"></i> Overwrite environment parameters
            </a>
        </li>
    </ul>
    
</div>
<?php endif; ?>