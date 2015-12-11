<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);
?>
<?php if(isset($campaign_execution)): ?> 
<div class="panel panel-default eiPanel" >
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_testset') ?>&nbsp;Execution Informations</h2>
        <div class="panel-actions">
        </div>
    </div>

    <div class="panel-body table-responsive" > 
        <table class="table table-striped bootstrap-datatable dataTable">
            <thead>
            <tr>
                <th>N°</th>
                <th>On Error</th>
                <th>Profil</th>
                <th>Execution Date</th>
                <th>By</th>
                <th>Time</th>
                <th>Step Count</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody> 
            <tr>
                <td><?php echo $campaign_execution['ce_id'] ?></td>
                <td><?php echo $campaign_execution['bt_name'] == null ? "Continue":$campaign_execution['bt_name']; ?></td>
                <td><?php echo $campaign_execution['p_name'] ?></td>
                <td><?php echo $campaign_execution['ce_created_at'] ?></td>
                <td><?php echo $campaign_execution['g_username'] ?></td> 
                <td><?php echo date("i:s", $campaign_execution['time']/1000)." s"; ?></td> 
                <td><?php echo $campaign_execution['nbStep'] ?></td>
                <td>
                    <span style="background-color:<?php //echo $campaign_execution->getStatusColor() ?> " class="label">
                        <?php //echo $campaign_execution->getStatusName(); ?>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div> 
</div>
<?php endif; ?> 
 
<div class="panel panel-default eiPanel" id="EiExecutedFunctionsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_function') ?>
            Executed functions (<?php echo  (isset($execFunctions)? count($execFunctions->getRawValue()):0) ?>)
        </h2>
        <div class="panel-actions">
            
        </div>
        <ul class="nav nav-tabs">
             <?php  $unexecfunctionsuri=$url_tab;  ?>
                    <?php  $unexecfunctionsuri['campaign_id']=$ei_campaign->getId();  ?>
                    <?php  $unexecfunctionsuri['campaign_execution_id']=$campaign_execution['ce_id']; $unexecfunctionsuri['action']="getUnexecFunctions";  ?> 
                <li><a href="#" itemref="<?php echo url_for2("execution_stats", $unexecfunctionsuri);?>" id="loadUnexecutedFunctions">Get list of unexecuted functions for this execution</a></li> 
            </ul>
    </div>

    <div class="panel-body table-responsive" >
        <i class="fa fa-4x  fa-spinner fa-spin " id="EiExecutedFunctionsLoader" style="display: none;"></i>
        <table class="table table-striped table-condensed bootstrap-datatable  dataTable " id="executionFunctions"  >
            <thead>
            <tr> 
                <th style="width: 3%">N°</th>
                <th style="width: 27%">Path</th>
                <th>Function</th> 
                <th>Criticity</th>
                <th>Nb interventions</th>
                <th>Nb intervention open</th>
                <th>last intervention creation date</th>
                <th>Nb exec</th>
                <th>Nb success</th>
                <th>Nb error</th>
                <th>Nb aborted</th>
                <th>Avg time (s)</th>
                <th>Min time (s)</th>
                <th>Max time (s)</th>
                <th> % Success</th>
            </tr>
            </thead>
            <tbody> 
                <?php if(isset($execFunctions) && count($execFunctions)>0): $distinctFunct=array(); ?>
                <?php foreach ($execFunctions as $ex):   ?>
                <?php if(!array_key_exists($ex['function_id'].$ex['function_ref'], $distinctFunct)): ?>
                <tr>
                    <td style="width: 5%"><?php echo $ex['ei_test_set_id'] ?></td>
                    <td style="width: 27%">
                        <?php $arrayPath=  json_decode(html_entity_decode($ex['tr_path']),true);  ?>
                        <ol class="breadcrumb"> 
                        <?php foreach($arrayPath as $item): ?>
                        <li>
                            <?php if($item['type']=="View"): ?>
                            <?php echo ei_icon('ei_folder',null,'ei-folder').'  '.$item['name']; endif;?>
                            <?php if($item['type']=="Function"):?> 
                            <?php  //Url de gestions des statistiques de fonction
                            $itemReportsUri = $url_tab; $itemReportsUri['function_id']=$item['obj_id'];$itemReportsUri['function_ref']=$item['ref_obj'];
                            $itemReportsUri['action']='statistics'; ?>
                            <a href="<?php echo url_for2('functionActions',$itemReportsUri  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function'); ?>  
                                <?php echo $item['name'] ?> 
                            </a>
                           <?php endif;?> 
                        </li> 
                        <?php endforeach; ?>  
                        </ol>
                    </td>
                    <td>
                        <?php  //Url de gestions des statistiques de fonction
                            $tab_stats = $url_tab; $tab_stats['function_id']=$ex['function_id'];$tab_stats['function_ref']=$ex['function_ref'];
                            $tab_stats['action']='statistics'; ?> 
                            <a href="<?php echo url_for2('functionActions',$tab_stats  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function') ?> <?php echo $ex['function_name'] ?>
                             </a> 
                    </td>
                    <td><?php echo $ex['criticity'] ?></td>
                    <td><?php echo $ex['nbBugs'] ?></td>
                    <td><?php echo $ex['nbOpenBugs'] ?></td>
                    <td><?php echo $ex['last_bug_date_creation'] ?></td>
                    <td><?php echo $ex['nbEx'] ?></td>
                    <td><?php echo $ex['nbExOk'] ?></td>
                    <td><?php echo $ex['nbExKo'] ?></td>
                    <td><?php echo ($ex['nbEx']- $ex['nbExOk']-$ex['nbExKo']) ?></td>
                    <td><?php echo number_format($ex['avg_time']/1000,2) ?></td>
                    <td><?php echo number_format($ex['min_time']/1000,2) ?></td>
                    <td><?php echo number_format($ex['max_time']/1000,2) ?></td>
                    <td><?php echo number_format((($ex['nbEx']>0)?($ex['nbExOk']/$ex['nbEx'])*100 : 0),2)." % " ?></td> 
                </tr>
                <?php $distinctFunct[$ex['function_id'].$ex['function_ref']]=1; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div> 
</div> 
 