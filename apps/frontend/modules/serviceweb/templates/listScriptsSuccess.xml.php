<?php

/*
 *compose.local/frontend_dev.php/serviceweb/getScriptWithVariables/admin/eisgeeisge2010/project/1/2/profile/1/5/scenario/1/Jdt/5/position/1/scriptCmds.xml 
 */
?>
<?php if(isset($error)): ?>
<error>
    <?php echo $error  ?>
</error>
<?php else: ?>
<?php if(isset($cmds) && count($cmds)>0): $first=$cmds->getFirst(); ?>  
<commandes>
    <properties>
        <function_id><?php echo $first->getFunctionId() ?></function_id>
        <function_ref><?php echo $first->getFunctionRef() ?></function_ref>
        <script_id><?php echo $first->getScriptId() ?></script_id>
    </properties>
    <?php foreach($cmds as $cmd): ?>
    <commande> 
        <command><?php echo $cmd->getName()?></command>
        <target><?php echo $cmd->getCommandTarget()?></target>
        <value><?php echo $cmd->getCommandValue()?></value>
    </commande> 
    <?php endforeach; ?>
</commandes>
<?php else: ?>
<error>
    No command
</error>
    <?php endif;?>
<?php endif;?>
