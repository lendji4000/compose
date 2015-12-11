 
<?php if(isset($msgs) && count($msgs)>0 && isset($level)):   ?>
<?php foreach ($msgs as $i=> $msg): ?> 
<?php $obj=$msg->getNode(); ?>
<?php if($obj->getLevel()==$level):  ?>
<li>
    <label class="tree-toggler nav-header">
        <?php echo $msg->getMessage().'/'.$msg->getSfGuardUser()->getUsername() ?>
        <?php 
        echo link_to2('reply', 'subject_message_create', array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'subject_id' => $subject_id,
            'parent_id' => $msg->getId(),
            'type' => $type,
            'action' => 'new'
        ),array(
            'class' => 'pull-right' ,
            'id' => 'addsubjectMessage'))
        ?>
    </label>
    
    <?php //unset($msgs[$i]); ?>
    <?php if($obj->hasChildren() ): ?>
    <ul class="nav nav-list tree"> 
        <?php 
        include_partial('eisubjectmessage/printNode',array(
            'level' => $msg->getLevel() +1 ,
            'parent_id'=> $msg->getId(),
            'msgs' => $obj->getChildren(),
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'subject_id' => $subject_id,
            'type' => $type,  
        )) 
                ?>
    </ul>
    <?php endif; ?>
</li>

<?php 
echo link_to2('Ask question', 'subject_message_create', array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'subject_id' => $subject_id,
    'parent_id' => $parent_id,
    'type' => $type,
    'action' => 'new'),array(
            'class' => ' ' ,
            'id' => 'addsubjectMessage')
        )
?>
<?php endif; ?>
<?php endforeach;  ?>
<?php endif; ?>

