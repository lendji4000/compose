<?php if (isset($migrateFuncts) && count($migrateFuncts)>0): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
    <div id="functionsOfTicketList">
        <?php foreach ($migrateFuncts as $migrateFunct) : ?>
            <?php
            $functionLine = $url_tab ;
                        $functionLine['ei_profiles']=$ei_profiles;
                        $functionLine['ei_project'] =$ei_project; 
                        $functionLine['migrateFunct'] =$migrateFunct; 
                        $functionLine['migrateFunctsWithoutCount'] =$migrateFunctsWithoutCount;
                        $functionLine['scriptProfiles'] =$scriptProfiles; 
                        $functionLine['ei_delivery'] =(isset($ei_delivery)?$ei_delivery:null);
                        $functionLine['resolved_conflicts'] =isset($resolved_conflicts)?$resolved_conflicts:array(); // Liste des conflits de fonction résolus sur la livraison 
            include_partial('eidelivery/functionLine', $functionLine)
            ?>
        <?php endforeach; ?>
    </div> 

<?php else:  ?>
    <div class="alert alert-warning" id="functionsOfTicketList"> 
        <strong>    Warning !   </strong>   No function associate to the the package
    </div>
<?php endif; ?> 