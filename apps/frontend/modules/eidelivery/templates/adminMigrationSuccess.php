<?php if( isset($ei_project) && isset($ei_profiles)): ?>
<input type="hidden" name="ticket_ref" value="<?php //echo $ei_ticket->getTicketRef(); ?>" id="ticket_ref" />
<input type="hidden" name="ticket_id" value="<?php //echo $ei_ticket->getTicketId(); ?>" id="ticket_id" />
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<div class="panel panel-default eiPanel" id="deliveryMigration">
        <div class="panel-heading"> 
            <h2><?php echo ei_icon('ei_function') ?>  Migrate functions</h2> 
        </div> 

        <div class="panel-body" id="functionsDeliveryToMigrate">  
            
            <div class=" row ">
                <ul class="nav nav-tabs">
                    <li><input  type="checkbox" id ="check_functions_for_migration_del"  /></li>
                    <li id="loading-indicator"> 
                        <i class="fa fa-spinner fa-spin fa-2x"  ></i>  
                    </li>
                    <?php if(!$ei_profiles->getFirst()): ?>
                <div class="alert alert-warning"> 
                    <strong>Warning!</strong> No Environment in project.Correct it
                </div>
                <?php else : ?>  
                    <li class="pull-right"> 
                        <?php  
                        $profilesForMigration['ei_profiles']=$ei_profiles;
                        $profilesForMigration['ei_project'] =$ei_project; 
                        $profilesForMigration['current_profile'] =$ei_profiles->getFirst(); 
                        $profilesForMigration['ei_delivery'] =$ei_delivery; ?>
                        <?php  include_partial('eidelivery/profilesForMigration',$profilesForMigration)  ?>
                    </li>  
                
                <?php endif; ?> 
              </ul> 
            </div>  
            <?php if(isset($migrateFuncts)): ?>
            <?php $functionsToMigrateList = $url_tab ;
                        $functionsToMigrateList['ei_profiles']=$ei_profiles;
                        $functionsToMigrateList['ei_project'] =$ei_project; 
                        $functionsToMigrateList['migrateFuncts'] =$migrateFuncts; 
                        $functionsToMigrateList['migrateFunctsWithoutCount'] =$migrateFunctsWithoutCount;
                        $functionsToMigrateList['scriptProfiles'] =$scriptProfiles; 
                        $functionsToMigrateList['ei_delivery'] =$ei_delivery;
                        $functionsToMigrateList['resolved_conflicts'] =$resolved_conflicts; 
                        include_partial('eidelivery/functionsToMigrateList',$functionsToMigrateList);  ?> 
            
            <?php  endif; ?> 
        </div>  
    </div>

<div class="panel panel-default eiPanel" id="deliveryMigrationScenarios">
        <div class="panel-heading"> 
            <h2><?php echo ei_icon('ei_scenario') ?>  Migrate scenarios</h2> 
        </div> 

        <div class="panel-body" id="scenariosDeliveryToMigrate">  
            
            <div class=" row ">
                <ul class="nav nav-tabs">
                    <li><input  type="checkbox" id ="check_scenarios_for_migration_del"  /></li>
                    <li id="loading-indicator"> 
                        <i class="fa fa-spinner fa-spin fa-2x"  ></i>  
                    </li>
                    <?php if(!$ei_profiles->getFirst()): ?>
                <div class="alert alert-warning"> 
                    <strong>Warning!</strong> No Environment in project.Correct it
                </div>
                <?php else : ?>  
                    <li class="pull-right"> 
                        <?php   
                        $profilesForMigration2['ei_profiles']=$ei_profiles;
                        $profilesForMigration2['ei_project'] =$ei_project;  
                        $profilesForMigration2['current_profile'] =$ei_profiles->getFirst(); 
                        $profilesForMigration2['ei_delivery'] =$ei_delivery;
                        $profilesForMigration2['migrationCase'] ='EiScenario';
                        include_partial('eidelivery/profilesForMigration',$profilesForMigration2)  ?>
                    </li>  
                
                <?php endif; ?> 
              </ul> 
            </div>   
            <?php if(isset($scenariosToMigrate) && isset($versionsProfiles)):  ?> 
            <?php
            $scenariosToMigrateList = $url_tab ;
                        $scenariosToMigrateList['ei_profiles']=$ei_profiles;
                        $scenariosToMigrateList['ei_project'] =$ei_project;  
                        $scenariosToMigrateList['current_profile'] =$ei_profiles->getFirst(); 
                        $scenariosToMigrateList['ei_delivery'] =$ei_delivery;
                        $scenariosToMigrateList['scenariosToMigrate'] =$scenariosToMigrate;
                        $scenariosToMigrateList['scenariosToMigrateWithoutCount'] =$scenariosToMigrateWithoutCount;
                        $scenariosToMigrateList['versionsProfiles'] =$versionsProfiles;
                        $scenariosToMigrateList['ei_profile'] =$ei_profile;
                        $scenariosToMigrateList['resolved_conflicts'] =$resolved_conflicts_scenarios; // Liste des conflits de scenarios résolus sur la livraison 
            include_partial('eidelivery/scenariosToMigrateList',$scenariosToMigrateList);  ?> 
            
            <?php  endif; ?> 
        </div>  
    </div>
<?php endif; ?>
  

 