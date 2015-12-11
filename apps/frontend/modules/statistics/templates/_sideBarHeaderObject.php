<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref); 
    ?> 
     <!-- Statistiques sur le projet--> 
    <div class="row" id="eisge-object">
       
        <h2>
            <?php echo ei_icon('ei_stats') ?>   
                <span class="text" title="General statistics">   
                    <strong> Statistics</strong> 
                </span> 
        </h2> 
    </div>
 

        <div class="row" id="eisge-object-actions">
            <!-- Menu principal des statistiques  --> 
            <ul class="nav nav-tabs" role="tablist"> 
                <li class="statsItem <?php if(isset($activeItem) && ($activeItem=='statistics')): echo 'active' ; endif; ?>">
                    <?php $generalStatsUri=$url_tab; $generalStatsUri['action']='stats' ?>
                    <a class="btn btn-sm" id="accessGeneralStats" 
                       href="<?php  echo url_for2('generalStats', $generalStatsUri) ?>"> 
                        <?php echo ei_icon("ei_project") ?> <span class="text"> General </span> 
                    </a> 
                </li>   
                <!--Details on subject -->
                <li class="statsItem  <?php if(isset($activeItem) && $activeItem=='functionsStats'): echo 'active' ; endif; ?>"> 
                    <?php $functionStatsUri=$url_tab; $functionStatsUri['action']='functionsStats' ?>
                    <a class="btn btn-sm" id="accessGeneralFunctionStats" 
                       href="<?php  echo url_for2('generalStats', $functionStatsUri) ?>"> 
                        <?php echo ei_icon("ei_function") ?> <span class="text"> Functions </span> 
                    </a> 
                </li>  
                
            </ul>
        </div>     
 