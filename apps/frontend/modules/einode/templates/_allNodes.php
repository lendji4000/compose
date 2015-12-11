<!--Récupération de tous les enfants d'un noeud de l'arbre (vues, fonctions , raccourcis) -->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name)?> 

<h3>Node Childs</h3>
<table class="table table-striped table-bordered  node_childs">
<thead>
    <tr>
        <th>&nbsp;</th>
        <th >Position</th>
        <th>Name</th>
        <th >Type</th>
        <th >Last Updated</th>
        <th colspan="2">Actions</th>
    </tr>
</thead>
<tbody>
<?php if(isset($node) && isset($childs) && $node!=null && $childs->getFirst()): ?>
<?php foreach($childs as $key => $child): ?>
    <tr class="draggable"  draggable="true">
        <td><input type="hidden"  class="node_id" value="<?php echo $child->getId() ?>" /></td>
        <td class="position"><?php echo $child->getPosition() ?></td>
        <td><?php echo $child->getName() ?></td>
        <td><?php echo $child->getType() ?></td>
        <td><?php echo $child->getUpdatedAt() ?></td>
        <td class="delete_node"></td>
        <td >
            <?php
            $tab=MyFunction::getPathToTreeNode($child,$url_tab);
            $path_to_node=$tab['path_to_node'];
            echo link_to1('Details',$path_to_node)   
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>    
<?php else :?>
<?php endif;?>
</table>