<?php if(isset($ei_nodes)): ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);  ?>
<ul class="node_childs">
<?php  foreach($ei_nodes as $ei_node) :
    $ei_node_line=$url_tab;
    $ei_node_line['ei_node']=$ei_node;  
    include_partial('ei_node_line',$ei_node_line) ?>
<?php endforeach; ?>    
</ul>

<?php endif; ?>

