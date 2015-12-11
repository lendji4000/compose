<?php $nbEtapes=0 ?> 
<?php $url_tab=$urlParameters->getRawValue() ?>
<?php if(isset($exByStateFunctions) && count($exByStateFunctions)> 0): ?>
<div class="row" id="functionStatsPercents"> 
    <?php foreach($exByStateFunctions as $ex) : $nbEtapes+=$ex['nbEx']; endforeach ;?> 
    <?php foreach($exByStateFunctions as $ex) : ?>
    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
        <div class="info-box success" style="background-color: <?php echo $ex['color_code'] ?>; border-color: <?php echo $ex['color_code'] ?>">
            <?php echo ei_icon('ei_function') ?>
            <div class="count"><?php echo ($nbEtapes > 0) ? number_format($ex['nbEx']*100/$nbEtapes, 2):0 ?>%</div>
            <div class="title"><?php echo $ex['nbEx']."     ".$ex['name'] ?></div>
            <div class="title"><?php echo $nbEtapes."   executed " ?></div>
        </div>
    </div> 
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php $functionReportPartial =$url_tab;  
$functionReportPartial['function_id']=$kal_function->getFunctionId();
$functionReportPartial['function_ref']=$kal_function->getFunctionRef(); 
$functionReportPartial['functionReportForm']=$functionReportForm  ;
include_partial('searchReportForm', $functionReportPartial) ?>  
        <!--<hr/>-->
<div class="table-responsive">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_function') ?> Reports (<?php echo $nbEtapes ?>)</h2>
                <div class="panel-actions">  
                </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped dataTable table-bordered bootstrap-datatable  " >
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Iteration</th>
                        <th>Scenario's Version</th>
                        <th>Mode</th>
                        <th>Environment</th>
                        <th>Dataset ID</th>
                        <th>Dataset Name</th>
                        
                        <th>Ex date</th>
                        <th>By</th>
                        <th>Sensors</th>
                        <th>Status</th>
                        <th>Oracle</th>
                        <th>Parameters & sensors</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($exFunctions) && count($exFunctions)>0): $tab=array(); $inParams=""; $outParams="";  $k=0; ?>

                       <?php  foreach ($exFunctions as $exFunction):  ?> 
                    
                    
                   <?php if (!array_key_exists($exFunction['num_ex'], $tab)):    ?>
                    <?php if($k!=0): ?> 
                    <td class="table-responsive">
                        <?php if(isset($logsParams)):?>
                        <h4><strong>Executions: </strong><?php echo count($logsParams) ?></h4>
                        <?php foreach($logsParams as $key => $param): ?>
                        <h5><strong>Execution N° : </strong><?php echo $key ?></h5>
                        <table class="table-striped bootstrap-datatable dataTable" style="display:block">
                            <thead>
                                <tr>  
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($param)): ?>
                                <?php foreach ($param as  $p): ?>
                                <tr>
                                    <td><?php echo $p['type'] ?></td>
                                    <td><?php echo $p['name'] ?></td>
                                    <td><?php echo $p['value'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <hr/>  
                        <?php endforeach; ?>
                        <?php endif;   ?>
                    </td> 
                    <?php  $inParams=""; $outParams=""; $logsParams=array(); ?>
                    </tr>
                    <?php endif; ?>
                    <tr> 
                        <td><?php echo $exFunction['num_ex']  ?></td>
                        <td><?php echo $exFunction['iteration_id']  ?></td>
                        <td><?php echo $exFunction['v_name'] ?></td>
                        <td><?php echo  $exFunction['ts_mode'] ?></td>
                        <td><?php echo  $exFunction['p_name'] ?></td>
                        <td><?php echo $exFunction['ts_ei_data_set_id']  ?></td>
                        <td><?php echo $exFunction['dt_name']  ?></td>
                        <td><?php echo  $exFunction['tsf_date_debut'] ?></td> 
                        <td><?php echo  $exFunction['g_username'] ?></td> 
                        <td>
                            Time : <?php echo  $exFunction['tsf_duree'] ?> ms
                            <?php $paramSensorsList = $url_tab;
                            $paramSensorsList['key'] = $exFunction['lf_id'];
                            include_partial('sensorsList', $paramSensorsList) ?>
                        </td> 
                        <td><span class="label  " style="background-color: <?php echo $exFunction['tsfs_color_code'] ?>"> <?php echo  $exFunction['tsfs_name'] ?> </span></td> 
                        <td> 
                            <?php
                            echo link_to2(ei_icon('ei_show'), 'eitestset_function_oracle', array(
                                    'project_id' => $url_tab['project_id'],
                                    'project_ref' => $url_tab['project_ref'],
                                    'ei_scenario_id' => $exFunction['ts_scenario_id'],
                                    'ei_test_set_id' => $exFunction['tsf_ts_id'], 
                                    'profile_id' => $url_tab['profile_id'],
                                    'profile_ref' => $url_tab['profile_ref'],
                                    'profile_name' => $url_tab['profile_name'],
                                    'function_id' => $exFunction['tsf_function_id'],
                                    'function_ref' => $exFunction['tsf_function_ref']
                                ), array(
                                    'target'=> '_blank',
                                    'title' => 'Function Oracle'
                                )) ?>   
                        </td>
                        <?php     $tab[$exFunction['num_ex']]=1;  $k++;   endif; ?>
                        <?php  
                            $logsParams[$exFunction['lf_id']][]=array(
                            "name"=>$exFunction['lp_param_name'],
                            "value" => $exFunction['lp_param_valeur'],
                            "type" => $exFunction['lp_param_type']); ?> 
                        
                        <?php endforeach; ?>
                        
                        <td class="table-responsive">
                        <?php if(isset($logsParams)):?>
                        <h4><strong>Executions: </strong><?php echo count($logsParams) ?></h4>
                        <?php foreach($logsParams as $key => $param): ?>
                        <h5><strong>Execution N° : </strong><?php echo $key ?></h5>
                        <table class="table-striped bootstrap-datatable dataTable">
                            <thead>
                                <tr>  
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($param)): ?>
                                <?php foreach ($param as  $p): ?>
                                <tr>
                                    <td><?php echo $p['type'] ?></td>
                                    <td><?php echo $p['name'] ?></td>
                                    <td><?php echo $p['value'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <hr/>
                        <?php endforeach; ?>
                        <?php endif;   ?>
                    </td>  
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
 