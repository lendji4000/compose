<?php if (isset($scenariosToMigrate) && count($scenariosToMigrate)>0 && isset($ei_project) && isset($ei_profile)):  ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
    <div id="scenariosOfTicketList">
        <?php foreach ($scenariosToMigrate as $scenarioToMigrate) : ?>
            <?php
            $scenarioLine = $url_tab ;
                        $scenarioLine['ei_profile']=$ei_profile;
                        $scenarioLine['ei_project'] =$ei_project; 
                        $scenarioLine['ei_profiles']=$ei_profiles;
                        $scenarioLine['scenarioToMigrate'] =$scenarioToMigrate; 
                        $scenarioLine['scenariosToMigrateWithoutCount'] =$scenariosToMigrateWithoutCount;
                        $scenarioLine['versionsProfiles'] =$versionsProfiles; 
                        $scenarioLine['ei_delivery'] =(isset($ei_delivery)?$ei_delivery:null);
                        $scenarioLine['resolved_conflicts'] =isset($resolved_conflicts)?$resolved_conflicts:array();  // Liste des conflits de scenarios résolus sur la livraison 
            include_partial('eidelivery/scenarioLine',$scenarioLine)
            ?>
        <?php endforeach; ?>
    </div> 

<?php else:  ?>
    <div class="alert alert-warning" id="scenariosOfTicketList"> 
        <strong>    Warning !   </strong>   No scenario associate to migrate
    </div>
<?php endif; ?> 
