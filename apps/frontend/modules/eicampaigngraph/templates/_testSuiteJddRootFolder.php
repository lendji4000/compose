
<?php if(isset($ei_data_set_root_folder) && isset($project_id) && isset($project_ref)): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<div id="arbre_jdd">
    <ul id="root_diagram_jdd">
        <li>
            <span><i class="cus-house"></i> <?php echo $ei_data_set_root_folder->getName();   ?></span>
            <?php if(isset($ei_data_set_children) && count($ei_data_set_children) >0):  ?> 
            <ul>
                <?php  foreach($ei_data_set_children as $ei_node) :?>
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