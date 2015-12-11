<?php if(isset($openDeliveries) && count($openDeliveries)>0): ?>
<!--<ul class="nav nav-sidebar" id="recentOpenDeliveriesTitle">
    <li class="active">   
        <a href="#" > 
                <?php //echo ei_icon('ei_delivery') ?>
                <span class="text">   
                    <small>  Open deliveries </small>
                </span>
            </a>
    </li>
</ul> -->
<ul class="nav nav-sidebar" id="recentOpenDeliveries">
<?php $delivery_show=$delivery_show_uri->getRawValue(); ?>
<?php $getDeliverySubjects=$delivery_show_uri->getRawValue(); ?>
    <?php foreach ($openDeliveries as $openDelivery): ?>
        <li>  
            <?php $getDeliverySubjects['delivery_id'] = $openDelivery->getId() ?>
            <a href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>#" class="accessDelivery  " title="<?php echo $openDelivery ?>"
                 data-content="Delivery date :  <?php echo $openDelivery->getDeliveryDate() ?>"  > 
                <?php echo ei_icon('ei_delivery') ?>
                <span class="text">   
                    <small><?php echo MyFunction::troncatedText($openDelivery, 17) ?></small>
                </span>
            </a>
        </li> 
    <?php endforeach; ?>

</ul>
<?php endif; ?>
 