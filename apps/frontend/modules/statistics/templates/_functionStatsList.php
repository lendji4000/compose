<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

    ?>

<div class="panel panel-default eiPanel" id="EiGeneralStatsFunctionsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_function') ?>
           Functions
            (<?php echo  (isset($exFunctions)? count($exFunctions->getRawValue()):0) ?>)
        </h2> 
        
    </div>
<div class="panel-body table-responsive" > 
        <i class="fa fa-4x fa-spin   fa-spinner " id="EiGeneralStatsFunctionsLoader"></i>
        <table class="table table-striped table-condensed bootstrap-datatable  dataTable " id="EiGeneralStatsFunctionsTable"  >
            <thead>
            <tr>
                <th style="width: 3%">N°</th>
                <th style="width: 27%">Path</th>
                <th>Function</th>
                <th>Criticity</th>
                <th>Status</th>
                <th>Nb Ex</th>
                <th>Nb Campaigns</th>
                <th>Nb Scenarios</th>
                <th>last execution</th> 
            </tr>
            </thead>
            <tbody> 
                <?php if(isset($exFunctions) && count($exFunctions)>0): ?>
                <?php foreach ($exFunctions as $ex):   ?> 
                <tr>
                    <td style="width: 5%"><?php echo $ex['id'] ?></td>
                    <td style="width: 27%">
                        <?php if(isset($ex['path']) && count($arrayPath=json_decode(html_entity_decode($ex['path']),true))>0): ?>
                        <?php //$arrayPath=  json_decode(html_entity_decode($ex['tr_path']),true);  ?>
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
                            $tab_stats = $url_tab; $tab_stats['function_id']=$ex['obj_id'];$tab_stats['function_ref']=$ex['ref_obj'];
                            //$tab_stats['execution_id']=$campaign_execution_id;
                            $tab_stats['action']='statistics'; ?>
                        <a href="<?php echo url_for2('functionActions',$tab_stats  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function') ?> <?php echo $ex['name'] ?>
                             </a>
                    </td>
                    <td>
                        <span class="<?php echo 'label  label-' . $ex['criticity'] ?> " >
                         <?php echo $ex['criticity'] ?> 
                        </span>  
                    </td>
                    <td>
                        <span class="label <?php if($ex['status']=="ok"): echo "label-success" ;endif;?> 
                              <?php if($ex['status']=="ko"): echo "label-danger" ;endif;?> 
                              <?php if($ex['status']!="ko" && $ex['status']!="ok"): echo "label-default" ;endif;?>">
                                  <?php echo $ex['status'] ?>
                        </span>
                    </td>
                    <td><?php echo $ex['nbEx'] ?></td>
                    <td><?php echo $ex['nbCamp'] ?></td>
                    <td><?php echo $ex['nbScenario'] ?></td>
                    <td><?php echo $ex['last_ex'] ?></td> 
                </tr>  
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>