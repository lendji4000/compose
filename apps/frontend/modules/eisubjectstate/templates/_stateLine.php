<?php if(isset($stateLineParams)):  //var_dump($stateLineParams) ?>
<?php $state=$stateLineParams['state'] ?> 
<tr class="bugsStateLine">
    <td class="ei_subjects_state"><?php echo $state->getName() ?></td>
    <td class="ei_subjects_state_color"><span style="background-color:<?php echo $state->getColorCode() ?> "><?php echo $state->getColorCode() ?></span></td>
    <td class="ei_subjects_state_display_homepage">
        <?php if($state->getDisplayInHomePage()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Will be display in home page " ></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Will not be display in home page "></i>
            <?php endif; ?> 
    </td>
    <td class="ei_subjects_state_display_search">
        <?php if($state->getDisplayInSearch()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Will be display in search page "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Will not be display in search page " ></i>
                <?php endif; ?> 
    </td>
    <td class="ei_subjects_state_display_todolist " >
        <?php if($state->getDisplayInTodolist()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Will be display in to do list "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Will not be display in to do list "></i>
                <?php endif; ?> 
    </td>
    <td class="ei_deliveries_state_close_del_state">
        <?php if( $state->getCloseDelState() ): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Need to be active to close delivery "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger"></i>
                <?php endif; ?>  
    </td>   
    <td class="ei_deliveries_state_updated">  <?php echo $state->getUpdatedAt(); ?> </td> 
    <td>
        <?php $bug_state_edit = $stateLineParams->getRawValue(); unset($bug_state_edit['state']);
        $bug_state_edit['state_id'] = $state->getId();
        $bug_state_edit['action'] = 'edit'; ?> 
        <a class="editBugState btn btn-sm btn-success" href="<?php echo url_for2('bug_state_edit', $bug_state_edit) ?>">
            <?php echo ei_icon('ei_edit') ?>
        </a>
    </td>
</tr>
<?php  endif; ?> 