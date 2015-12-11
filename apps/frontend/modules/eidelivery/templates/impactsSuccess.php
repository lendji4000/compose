<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

    ?>

<div class="panel panel-default eiPanel" id="EiExecutedFunctionsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_function') ?>
           <?php if(isset($modName) && $modName="EiSubject"): echo "Functions" ?>
            <?php else: ?>
           <?php echo(isset($exec) && $exec)? "Executed functions":"Unexecuted functions";  ?>
            <?php endif; ?>
            (<?php echo  (isset($exFunctions)? count($exFunctions->getRawValue()):0) ?>)
        </h2>
        <div class="panel-actions">
            
        </div>
        <ul class="nav nav-tabs"> 
                <li>
                    <?php if(isset($modName) && $modName="EiSubject"):
                        $subjectFunctionListUri=$url_tab; $subjectFunctionListUri['subject_id']=$ei_subject->getId() ; 
                        $subUri=url_for2("subjectFunctionList",$subjectFunctionListUri) ?>
                    <?php else:  ?>
                    <?php $del_stats_uri=$url_tab; $del_stats_uri['delivery_id']=$delivery_id; $del_stats_uri['action']="impacts" ;
                          $del_stats_uri['exec']=(isset($exec) && $exec)?false:true; 
                          $delUri=url_for2('delivery_edit', $del_stats_uri) ?>
                    <?php if(isset($exec) && $exec):  
                        $text="Get unexecuted functions";
                        else:  
                                $text="Get executed functions";
                        endif; ?>
                    <?php endif; ?> 
                    <?php if(!isset($modName)):?>
                    <a class=" btn btn-sm "  href="<?php echo $delUri ?>" 
                       id="<?php echo(isset($exec) && $exec)? "loadDelUnexecutedFunctions":"loadDelexecutedFunctions";  ?>">
                         <?php echo $text ?>
                    </a> 
                    <?php endif;?>
                </li> 
            </ul>
    </div>
<div class="panel-body table-responsive" >
        <i class="fa fa-4x fa-spin   fa-spinner " id="EiExecutedFunctionsLoader" style="display: none;"></i>
        <table class="table table-striped table-condensed bootstrap-datatable  dataTable " id="executionFunctions"  >
            <thead>
            <tr>
                <th style="width: 5%">N°</th>
                <th style="width: 27%">Path</th>
                <th>Function</th>
                <th>Criticity</th>
                <th>Nb Campaigns</th>
                <th>Nb Scenarios</th>
                <th>last execution</th> 
                <?php if(isset($modName) && $modName="EiSubject"): ?>
                <th>Actions </th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody> 
                <?php if(isset($exFunctions) && count($exFunctions)>0): ?>
                <?php foreach ($exFunctions as $ex):   ?> 
                <tr class="subjectFunctionLine">
                    <td style="width: 5%"><?php echo $ex['id'] ?></td>
                    <td style="width: 27%">
                        <?php if(isset($ex['t_path']) && count($arrayPath=json_decode(html_entity_decode($ex['t_path']),true))>0): ?>
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
                            $tab_stats = $url_tab; $tab_stats['function_id']=$ex['function_id'];$tab_stats['function_ref']=$ex['function_ref'];
                            //$tab_stats['execution_id']=$campaign_execution_id;
                            $tab_stats['action']='statistics'; ?>
                        <a href="<?php echo url_for2('functionActions',$tab_stats  ) ?>" title="Function reports..."  target="_blank">
                                <?php echo ei_icon('ei_function') ?> <?php echo $ex['t_name'] ?>
                             </a>
                    </td>
                    <td><?php echo $ex['f_criticity'] ?></td>
                    <td><?php echo $ex['nbCamp'] ?></td>
                    <td><?php echo $ex['nbScenario'] ?></td>
                    <td><?php echo $ex['last_ex'] ?></td> 
                    <!-- Suivant que le lien bug-fonction ait été crée automatiquement ou non, on autorise la suppression du lien-->
                    <?php if(isset($modName) && $modName="EiSubject"): ?>
                    <td>
                        <?php if( !((isset($ex['sf_automate']) && $ex['sf_automate']) ||  ($ex['s_id']!=null))): ?>
                        <?php $subjectFunctionRemove=$url_tab; 
                        $subjectFunctionRemove['action']= "removeFunction" ;
                        $subjectFunctionRemove['function_id']=$ex['t_obj_id'] ;
                        $subjectFunctionRemove['function_ref']=$ex['t_ref_obj'] ;
                        $subjectFunctionRemove['subject_id']= isset($ex['s2_id'])?$ex['s2_id']:$ex['s_id']; ?>
                       <a class="btn btn-danger btn-sm removeFunctionFormSubject " href="<?php echo url_for2('subjectFunction', $subjectFunctionRemove) ?>">
                                <i class="fa fa-times-circle-o fa-lg "></i>
                            </a> 
                        <?php endif; ?>
                    </td>
                    
                    <?php endif; ?> 
                </tr>  
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody> 
        </table>
    </div>
    <?php if(isset($modName) && $modName="EiSubject"): ?>
    <div class="panel panel-footer">
        <a class="openFunctionsTreeForImpacts btn btn-success" data-toggle="modal" data-target="#addImpactsModal">
           <?php echo ei_icon('ei_add') ?> Add  
        </a>
    </div>
    <?php endif; ?>
</div> 


<?php if(isset($modName) && $modName="EiSubject"): ?>
<!--Fenêtre modale d'ajout d'une fonction à un bug-->
<div id="addImpactsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="addImpactsModalLabel"><?php echo ei_icon('ei_function') ?> Select new impact</h3>  
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <?php $getRootTreeUri=$url_tab; $getRootTreeUri['is_function_context']=true;  ?>
            <div class="modal-body addImpactsModalBody" itemref="<?php echo url_for2('getRootTree',$getRootTreeUri  ) ?>">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button> 
            </div>
        </div>
    </div>
</div>
<?php endif; ?>