<?php if(isset($ei_ticket) && isset($ei_project) && isset($ei_profile)  && isset($ei_profiles)): ?>
<input type="hidden" name="ticket_ref" value="<?php echo $ei_ticket->getTicketRef(); ?>" id="ticket_ref" />
<input type="hidden" name="ticket_id" value="<?php echo $ei_ticket->getTicketId(); ?>" id="ticket_id" />
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<div class="panel panel-default eiPanel" id="TicketMigration">
        <div class="panel-heading">
            
            <h2><i class="fa fa-text-width "></i>  <?php echo $ei_ticket.'  /   Migrate functions' ?></h2> 
                 
        </div> 

        <div class="panel-body" id="functionsOfTicket">   
            <div class=" row ">
                <ul class="nav nav-tabs">
                    <li><input  type="checkbox" id ="check_functions_for_migration"  /></li>
                    <li id="loading-indicator"> 
                        <i class="fa fa-spinner fa-spin fa-2x"  ></i>  
                    </li>
                    <?php if(!$ei_profiles->getFirst()): ?>
                <div class="alert alert-warning"> 
                    <strong>Warning!</strong> No Environment in project.Correct it
                </div>
                <?php else : ?>  
                    <li class="pull-right"> 
                        <?php  include_partial('eisubject/profilesForMigration',
                            array('ei_profiles'=>$ei_profiles,
                                'ei_project' =>$ei_project,
                                'ei_subject' =>$ei_subject,
                                'current_profile'=>$ei_profiles->getFirst()))  ?>
                    </li>  
                
                <?php endif; ?> 
              </ul> 
            </div>  
            <?php if(isset($migrateFuncts) && count($migrateFuncts)> 0): 
                $functionsToMigrateList = $url_tab ;
                        $functionsToMigrateList['ei_profiles']=$ei_profiles;
                        $functionsToMigrateList['ei_project'] =$ei_project; 
                        $functionsToMigrateList['migrateFuncts'] =$migrateFuncts; 
                        $functionsToMigrateList['migrateFunctsWithoutCount'] =$migrateFunctsWithoutCount;
                        $functionsToMigrateList['scriptProfiles'] =$scriptProfiles;   
                        include_partial('eidelivery/functionsToMigrateList',$functionsToMigrateList);  endif; ?> 
             
        </div> 
        <div class="panel-footer"> 
        </div>
    </div>
<div class="panel panel-default eiPanel" id="TicketMigrationScenarios">
        <div class="panel-heading">
            
            <h2><i class="fa fa-text-width "></i>  <?php echo $ei_ticket.'  /   Migrate scenarios' ?></h2> 
                 
        </div> 

        <div class="panel-body" id="scenariosOfTicket">   
            <div class=" row ">
                <ul class="nav nav-tabs">
                    <li><input  type="checkbox" id ="check_scenarios_for_migration"  /></li>
                    <li id="loading-indicator"> 
                        <i class="fa fa-spinner fa-spin fa-2x"  ></i>  
                    </li>
                    <?php if(!$ei_profiles->getFirst()): ?>
                <div class="alert alert-warning"> 
                    <strong>Warning!</strong> No Environment in project.Correct it
                </div>
                <?php else : ?>  
                    <li class="pull-right"> 
                        <?php  include_partial('eisubject/profilesForMigration',
                            array('ei_profiles'=>$ei_profiles,
                                'ei_project' =>$ei_project,
                                'ei_subject' =>$ei_subject,
                                'current_profile'=>$ei_profiles->getFirst(),
                                'migrationCase' => 'EiScenario'))  ?>
                    </li>  
                
                <?php endif; ?> 
              </ul> 
            </div>   
            
            <?php if(isset($scenariosToMigrate)   && count($scenariosToMigrate)> 0 && isset($versionsProfiles)):  ?> 
            <?php
            $scenariosToMigrateList = $url_tab ;
                        $scenariosToMigrateList['ei_profiles']=$ei_profiles;
                        $scenariosToMigrateList['ei_project'] =$ei_project;    
                        $scenariosToMigrateList['scenariosToMigrate'] =$scenariosToMigrate;
                        $scenariosToMigrateList['scenariosToMigrateWithoutCount'] =$scenariosToMigrateWithoutCount;
                        $scenariosToMigrateList['versionsProfiles'] =$versionsProfiles;
                        $scenariosToMigrateList['ei_profile'] =$ei_profile; 
            include_partial('eidelivery/scenariosToMigrateList',$scenariosToMigrateList);  ?> 
            
            <?php  endif; ?> 
            
            
            <?php //var_dump($versionsProfiles) ?>
        </div> 
        <div class="panel-footer"> 
        </div>
    </div>
<?php endif; ?> 