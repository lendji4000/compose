<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);       
    ?> 

    
    <div class="row" id="eisge-object">
        <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
        <h2>
            <?php echo ei_icon('ei_iteration') ?>  
            <?php if (isset($ei_iteration)):  ?> 
                <?php $url_tab['iteration_id']=$ei_iteration->getId();         ?>
                <span class="text"  title="<?php echo $ei_iteration   ?>">   
                    <strong> <?php echo ' I'.$ei_iteration->getId().'/'  ?></strong>
                     <?php  echo  $ei_iteration  ?> 
                </span>  
            <?php else: ?>
                <span class="text"  title="Delivery iterations">   
                    <strong>  Delivery iterations</strong>
                </span> 
            <?php endif; ?>
        </h2> 
    </div>
 
        
        <div class="row" id="eisge-object-actions">
            <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
                 On vérifie que des actions principales ont été définies pour cet objet
            --> 
            <ul class="nav nav-tabs" role="tablist">   
                
                <li class="<?php if(isset($activeItem) && $activeItem=='List'  ): echo 'active' ; endif; ?>">
                    <?php $ei_iteration_global_uri=$url_tab; unset($ei_iteration_global_uri['iteration_id']);
                    $ei_iteration_global_uri['delivery_id']=$ei_delivery->getId();
                    $ei_iteration_global_uri['action']='index' ?>
                    <a class=" btn btn-sm " href="<?php echo url_for2('ei_iteration_global', $ei_iteration_global_uri) ?>#"
                       title="Delivery iterations"  id="AccessDeliveriesIterations">
                        <?php echo ei_icon('ei_iteration') ?> List
                    </a>
                </li>  
                <?php if (isset($ei_iteration)):  ?> 
                <li class="<?php if(isset($activeItem) && $activeItem=='statistics'  ): echo 'active' ; endif; ?>">
                    <?php $it_stats_uri=$url_tab; $it_stats_uri['action']="statistics" ?>
                    <a class=" btn btn-sm " href="<?php echo url_for2('ei_iteration_actions', $it_stats_uri) ?>#" id="AccessIterationStatsOnHeader" title="Iteration statistics">
                        <?php echo ei_icon('ei_stats') ?> Statistics
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>    


 