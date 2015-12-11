<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name); 
?>
<div class="panel panel-default eiPanel" id="EiUnExecutedFunctionsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_function') ?>
            Unexecuted functions (<?php echo  (isset($unexecFunctions)? count($unexecFunctions->getRawValue()):0) ?>)
        </h2>
        <div class="panel-actions">
            
        </div>
        <ul class="nav nav-tabs">
            <?php  $execfunctionsuri=$url_tab;  ?>
                    <?php  $execfunctionsuri['campaign_id']=$ei_campaign->getId();  ?>
                    <?php  $execfunctionsuri['campaign_execution_id']=$campaign_execution->getId(); $execfunctionsuri['action']="statistics";  ?> 
                <li><a href="<?php echo url_for2("execution_stats", $execfunctionsuri);?>" id="loadExecutedFunctions">Executed functions</a></li> 
            </ul>
    </div>

    <div class="panel-body table-responsive" >
        <table class="table table-striped table-condensed bootstrap-datatable  dataTable " id="EiUnExecutedFunctions"  >
            <thead>
            <tr>
                <th style="width: 37%">Path</th>
                <th>Function</th>
                <th>Criticity</th>
                <th>Nb interventions</th>
                <th>Nb intervention open</th>
                <th>last intervention creation date</th>  
            </tr>
            </thead>
            <tbody> 
                <?php if(isset($unexecFunctions) && count($unexecFunctions)>0): ?>
                <?php foreach ($unexecFunctions as $ex):   ?> 
                <tr>
                    <td style="width: 37%">
                        <?php if(isset($ex['t_path']) && count($arrayPath=json_decode(html_entity_decode($ex['t_path']),true))>0): ?> 
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
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php  //Url de gestions des statistiques de fonction
                            $tab_stats = $url_tab; $tab_stats['function_id']=$ex['t_obj_id'];$tab_stats['function_ref']=$ex['t_ref_obj'];
                            $tab_stats['ceg_execution_id']=$campaign_execution->getId(); $tab_stats['action']='statistics'; ?>
                        <a href="<?php echo url_for2('functionActions',$tab_stats  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function') ?> <?php echo $ex['function_name'] ?>
                             </a>
                    </td>
                    <td><?php echo $ex['criticity'] ?></td>
                    <td><?php echo $ex['nbSubject'] ?></td>
                    <td><?php echo $ex['nbSubOpen'] ?></td>
                    <td><?php echo $ex['last_creat_date'] ?></td> 
                </tr> 
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div> 
</div> 