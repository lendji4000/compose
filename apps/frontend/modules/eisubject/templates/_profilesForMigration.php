<?php if (isset($ei_profiles) && isset($current_profile) && isset($ei_project) && isset($ei_subject)): ?>
<?php 
   $uri_name='migrateManyBugFunction';
   $idButton='migrateSelectedFunctions';
   $idButtonsGroup='profilesForMigration';
   $classButton='changeProfileForMigration';
   
   if(isset($migrationCase)&& $migrationCase=="EiScenario"):  
    $uri_name='migrateManyBugScenario';
    $idButton='migrateSelectedScenarios';
    $idButtonsGroup='profilesForMigrationScenario';
    $classButton='changeProfileForMigrationScenario';
      endif; ?>
<div class="btn-group" role="group" id="<?php echo $idButtonsGroup ?>">
  

  <div class="btn-group" role="group">
    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
      <?php echo ei_icon('ei_profile') ?>
      <?php  echo $current_profile->getTroncatedName(20);      ?>
      <span class="caret"></span>
    </a>
    <input type="hidden" class="current_profile_migration_id" value="<?php echo $current_profile->getProfileId()?>"/>
    <input type="hidden" class="current_profile_migration_ref" value="<?php echo $current_profile->getProfileRef() ?>"/>
    <input type="hidden" class="current_profile_migration_name" value="<?php echo $current_profile->getName() ?>"/> 
    <ul class="dropdown-menu">
            <?php foreach ($ei_profiles as $ei_profile): ?>
                <li>
                    <?php  echo link_to2(ei_icon('ei_profile') . ' ' . $ei_profile->getTroncatedName(20), 'changeProfileForMigration',
                            array('profile_id' => $ei_profile->getProfileId(),
                                'profile_ref' => $ei_profile->getProfileRef(),
                                'project_id' => $ei_project->getProjectId(),
                                'project_ref' => $ei_project->getRefId(),
                                'subject_id' => $ei_subject->getId(),
                                'migrationCase' => isset($migrationCase)?$migrationCase:null), array('class' => $classButton))
                            ?>
                     
                </li>
            <?php endforeach; ?>
    </ul> 
  </div>
   
  <a href="<?php 
        echo url_for2($uri_name, array('profile_id' => $current_profile->getProfileId(),
        'profile_ref' => $current_profile->getProfileRef(),
        'project_id' => $ei_project->getProjectId(),
        'project_ref' => $ei_project->getRefId(),
        'subject_id' => $ei_subject->getId() ))  ?>"
                      class="btn btn-success btn-sm" id="<?php echo $idButton ?>">   Migrate
  </a>
</div>
<?php endif; ?>

