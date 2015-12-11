<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>
<?php if(isset($root_folder)): ?>
<div id="arbre_scenarios">
    <ul id="root_diagram_test_suite">
        <li>
            <span><i class="cus-house"></i> <?php echo $root_folder->getName() ?></span>
            <?php if(isset($ei_nodes) && count($ei_nodes) >0): ?> 
            <ul>
                <?php  foreach($ei_nodes as $ei_node) :?>
                <?php $ei_node_line=$url_tab  ?>
                <?php $ei_node_line['ei_node']=$ei_node  ?> 
                <?php include_partial('eicampaigngraph/ei_node_line',$ei_node_line) ?>
                <?php endforeach; ?>
                
            </ul>
            <?php endif; ?>
        </li> 
    </ul>
</div>
<?php endif; ?>
 