<!-- Arborescence d'un noeud de l'arbre -->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name) ?> 

    <?php if(isset($ei_node)  && $ei_node!=null && 
        isset($current_node) && $current_node!=null  ): ?> 

<?php  $node_childs=$ei_node->getNodes();?>
<?php if($node_childs->getFirst()):  ?> 
<?php foreach ($node_childs as $node_child) : ?>

<?php 
$tab=MyFunction::getPathToTreeNode($node_child,$url_tab);
$img_node=$tab['img_node'];
        ?>
<li class="item_tree">
    <ul>
        <li class="lien_survol_node">     
            <input type="hidden" name="obj_id" value="<?php echo $node_child->getObjId(); ?>" class="obj_id" />
            <input type="hidden" name="node_id" value="<?php echo $node_child->getId(); ?>" class="node_id" />
            <input type="hidden" name="node_type" value="<?php echo $node_child->getType(); ?>" class="node_type" />
            <!-- On vérifie chaque fois si c'est le noeud courant et par conséquent 
                on affiche pas les éléments permettant de dérouler dessus   
            -->
            <?php if(isset($current_node) && $current_node->getId()!=$node_child->getId()): ?>
            <?php $sendDiagramForCheck=$url_tab; 
            $sendDiagramForCheck['obj_id']=$node_child->getObjId();
            $sendDiagramForCheck['node_type']=$node_child->getType();
            $sendDiagramForCheck['current_node_id']=$current_node->getId(); ?>
            <i class="fa fa-plus-square show_node_diagram_check" title="Show Child Node" 
                 data-href="<?php echo url_for2('sendDiagramForCheck',$sendDiagramForCheck)?>">
            </i>
            <?php $ei_node_close=$url_tab; 
            $ei_node_close['ei_node_id']=$node_child->getId();  ?>
            <i  title="Hide Child Node"  class="fa fa-minus-square hide_node_diagram_check" 
               data-href="<?php echo url_for2('ei_node_close',$ei_node_close) ?>" >
            </i> 
            <?php endif; ?>  
            <a href="#"  class="checkNode">
                <?php echo $img_node.'  '.$node_child->getName() ?>
            </a>  
        </li>
        <li>
            <ul class="node_diagram_for_check"></ul>
        </li>
    </ul> 
</li>

<?php endforeach; ?> 
<?php endif; ?>
 
<?php endif; ?>  