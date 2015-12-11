<?php if(isset($stateLineParams)):  //var_dump($stateLineParams) ?>
<?php $state=$stateLineParams['state'] ?> 
<tr class="deliveriesStateLine">
    <td class="ei_deliveries_state"><?php echo $state->getName() ?></td>
    <td class="ei_deliveries_state_color"> 
        <span style="background-color:<?php echo $state->getColorCode() ?> "><?php echo $state->getColorCode() ?></span>
    </td>
    <td class="ei_deliveries_state_display_homepage">
        <?php if( $state->getDisplayInHomePage()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Will be display in homepage "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Will not be display in homepage " ></i>
                <?php endif; ?>  
    </td>
    <td class="ei_deliveries_state_display_search"> 
        <?php if($state->getDisplayInSearch()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Will be display in search page "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Will not be display in search page " ></i>
                <?php endif; ?> 
    </td>
    <td class="ei_deliveries_state_close_state"> 
        <?php if($state->getCloseState()): ?>
        <i class="fa fa-check-circle btn-xs btn-success" title="Check to close delivery "></i>
            <?php else: ?>
        <i class="fa fa-times-circle btn-xs btn-danger" title="Uncheck to display delivery " ></i>
                <?php endif; ?>
    </td>   
    <td class="ei_deliveries_state_updated">  <?php echo $state->getUpdatedAt(); ?> </td> 
    <td>
        <?php $delivery_state_edit = $stateLineParams->getRawValue(); unset($delivery_state_edit['state']);
        $delivery_state_edit['state_id'] = $state->getId();
        $delivery_state_edit['action'] = 'edit'; ?> 
        <a class="editDeliveriesState btn btn-sm btn-success" href="<?php echo url_for2('delivery_state_edit', $delivery_state_edit) ?>">
            <?php echo ei_icon('ei_edit') ?>
        </a>
    </td>
</tr>
<?php  endif; ?> 