<div id="deliveryHeader">
    <h5>Delivery N° &nbsp; : &nbsp; <?php echo 'D'.$ei_delivery->getId() ?></h5>
    <hr/>
    <div class="navbar" >
        <div class="navbar-inner" >
            <!--<a class="navbar-brand" href="#"><?php //echo $ei_delivery->getName() ?></a>-->
            <ul class="nav" >
                <li class="<?php if(isset($activeItem) && $activeItem=='Properties'): echo 'active' ; endif; ?>">
                    <a href="<?php
                    echo url_for2('delivery_edit', array(
                        'delivery_id' => $ei_delivery->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'action' => 'show'
                    ))
                    ?>"> 
                        <i class="fa fa-wrench "></i> Properties 
                    </a> 
                </li>
                <li class="divider-vertical"></li>
                <li class="<?php if(isset($activeItem) && $activeItem=='Campaigns'): echo 'active' ; endif; ?>">
                    <a href="<?php
                    echo url_for2('getDeliveryCampaigns', array(
                        'delivery_id' => $ei_delivery->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    )) 
                    ?>"><?php echo ei_icon('ei_campaign', 'lg') ?> Campaigns  
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <li class="<?php if(isset($activeItem) && $activeItem=='Subjects'): echo 'active' ; endif; ?>">
                    <a href="<?php
                    echo url_for2('getDeliverySubjects', array(
                        'delivery_id' => $ei_delivery->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    ))
                    ?>"><?php echo ei_icon('ei_subject', 'lg') ?> Bugs 
                    </a>
                </li>
                <li class="divider-vertical"></li>
            </ul>
            
        </div>
    </div>
</div>
