<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);       
    ?> 
<?php if (isset($ei_delivery)):  $url_tab['delivery_id'] =  $ei_delivery->getId() ;    endif; ?>
    <div class="row" id="eisge-object">
        <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
        <h2>
            <?php echo ei_icon('ei_delivery') ?> 
            <?php if(isset($ei_delivery)): ?>  
                <span class="text"  title="<?php echo $ei_delivery   ?>">   
                    <strong><?php echo 'D'.$ei_delivery->getId().'/'  ?></strong>
                     <?php  echo  $ei_delivery  ?> 
                </span> 
            <?php else:  ?>
            <a href="#"  > 
                <span class="text" title="Administrate deliveries">   
                    <strong>Administrate deliveries</strong> 
                </span>
            </a>
            <?php endif; ?>
        </h2>

    </div>
 
        
        <div class="row" id="eisge-object-actions">
            <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
                 On vérifie que des actions principales ont été définies pour cet objet
            --> 
            <ul class="nav nav-tabs" role="tablist">
                <?php if (isset($ei_delivery)):  ?>
                <li class="<?php if(isset($activeItem) && ($activeItem=='Show' || $activeItem=='Edit')): echo 'active' ; endif; ?>">
                    <?php $delivery_show=$url_tab;  $delivery_show['action']='show' ?>
                    <a class=" btn btn-sm " title="Delivery properties"  id="accessDeliveryProperties" href="<?php echo url_for2('delivery_edit',$delivery_show) ?>"> 
                        <i class="fa fa-wrench "></i><span class="text">     Properties</span>   
                    </a> 
                </li>   
                <li class="<?php if(isset($activeItem) && $activeItem=='Campaigns'): echo 'active' ; endif; ?>">
                    <?php $getDeliveryCampaigns=$url_tab; ?>
                    <a class=" btn btn-sm " title="Delivery campaigns"  id="accessDeliveryCampaigns"  href="<?php  echo url_for2('getDeliveryCampaigns',$getDeliveryCampaigns)  ?>">
                         <?php echo ei_icon('ei_campaign') ?><span class="text">    Campaigns </span>     
                    </a>
                </li> 
                <li class="<?php if(isset($activeItem) && $activeItem=='Bugs'): echo 'active' ; endif; ?>">
                    <?php $getDeliverySubjects=$url_tab; ?>
                    <a class=" btn btn-sm " title="Delivery interventions"   id="accessDeliveryBugs" href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>">
                        <?php echo ei_icon('ei_subject') ?> <span class="text">    Interventions </span>   
                    </a>
                </li>  
                <li class="<?php if(isset($activeItem) && $activeItem=='adminMigration'): echo 'active' ; endif; ?>">
                    <?php $deliveryAdminMigration=$url_tab; ?>
                    <a class=" btn btn-sm " id="accessDeliveryBugs" title="Tests migration"  href="<?php echo url_for2('deliveryAdminMigration', $deliveryAdminMigration) ?>">
                        <?php echo ei_icon('ei_profile') ?> <span class="text"> Tests Migration </span>   
                    </a>
                </li>
                <li class="<?php if(isset($activeItem) && $activeItem=='deliveryProcess'): echo 'active' ; endif; ?>">
                    <?php $deliveryProcess=$url_tab; ?>
                    <a class=" btn btn-sm " id="accessDeliveryBugs" title="Delivery process"  href="<?php echo url_for2('deliveryProcess', $deliveryProcess) ?>">
                        <?php echo ei_icon('ei_profile') ?> <span class="text"> Delivery process </span>   
                    </a>
                </li>
                <li class="<?php if(isset($activeItem) && $activeItem=='impacts'  ): echo 'active' ; endif; ?>">
                    <?php $del_stats_uri=$url_tab; $del_stats_uri['action']="impacts" ?>
                    <a class=" btn btn-sm " href="<?php echo url_for2('delivery_edit', $del_stats_uri) ?>#" id="AccessDeliveriesImpactsOnHeader" title="Functions impacted">
                        <i class="fa fa-bomb"></i> Impacts
                    </a>
                </li>
                <li class="<?php if(isset($activeItem) && $activeItem=='statistics'  ): echo 'active' ; endif; ?>">
                    <?php $del_stats_uri=$url_tab; $del_stats_uri['action']="statistics" ?>
                    <a class=" btn btn-sm " href="<?php echo url_for2('delivery_edit', $del_stats_uri) ?>#" id="AccessDeliveriesStatsOnHeader" title="Delivery statistics">
                        <i class="fa fa-area-chart"></i> Statistics
                    </a>
                </li>
                <li class="<?php if(isset($activeItem) && $activeItem=='Iterations'  ): echo 'active' ; endif; ?>">
                    <?php $ei_iteration_global_uri=$url_tab; $ei_iteration_global_uri['action']='index' ?>
                    <a class=" btn btn-sm " href="<?php echo url_for2('ei_iteration_global', $ei_iteration_global_uri) ?>#"
                       title="Delivery iterations"  id="AccessDeliveriesIterations">
                        <?php echo ei_icon('ei_iteration') ?> Iterations
                    </a>
                </li>
                <?php else:  ?> 
                <li class="<?php if(isset($activeItem) && $activeItem=='deliveriesList'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php echo url_for2('delivery_list', $url_tab) ?>#"
                       title="Delivery list"  id="AccessDeliveriesListOnHeader">
                        <?php echo ei_icon('ei_delivery') ?> List
                    </a>
                </li>
                <li class="<?php if(isset($activeItem) && $activeItem=='New'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php echo url_for2('delivery_new', $url_tab) ?>#" 
                       title="Create delivery" id="AccessDeliveriesNewOnHeader">
                        <?php echo ei_icon('ei_delivery') ?> New delivery
                    </a>
                </li>
                
<!--                <li class="<?php //if(isset($activeItem) && $activeItem=='stateList'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php //echo url_for2('delivery_state', $url_tab) ?>#" id="adminDeliveriesStates">
                        <i class="fa fa-genderless"></i> States
                    </a>
                </li>-->
              <?php  endif; ?>  
            </ul>
        </div>    


 