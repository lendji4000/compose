<?php if(isset($ei_delivery)): ?>
<?php 
 $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'delivery_id'=>$ei_delivery->getId() ); 
 $delivery_id=$ei_delivery->getId();
 ?>
<div class="panel panel-default eiPanel" id="iterationContent">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-cog"></i>
                    <span class="break"></span>  Impacted function executions
                </h2> 
                <ul class="nav nav-tabs">
                    <?php if(isset($current_iteration) && $current_iteration!=null): ?>
                    <li >
                        <?php  $ei_iteration_uri=$url_tab; $ei_iteration_uri['iteration_id']=$current_iteration->getId() ; 
                                            $ei_iteration_uri['action']='statistics'; unset($ei_iteration_uri['delivery_id']) ?>
                        <a class="btn btn-sm " id="followActiveIteration" href="<?php echo url_for2('ei_iteration_actions',$ei_iteration_uri) ?>" title="Go to current iteration"> 
                            <?php echo ei_icon('ei_iteration') ?>
                            <?php echo "Current iteration (".$current_iteration->getId().")";  ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="panel-body"> 
                
                <!--<div class="header"><strong><?php //echo ei_icon('ei_iteration')."    Iteration properties " ?></strong></div>-->
                <div id="iterationContentProperties" class="panel panel-default"> 
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog"></i>
                                <span class="break"></span>  Iterations for selected criterias
                            </h2> 
                            <ul class="nav nav-tabs"> 
                                <li > 
                                    <a class="btn btn-sm " id="setIteratationListForDelStats" href="#searchIterationForStatsModal" 
                                       data-toggle="modal" title="Set iteration list for delivery statistics"> 
                                        <?php echo ei_icon('ei_edit') ?> 
                                    </a>
                                </li> 
                            </ul>
                        </div>
                        <div class="panel-body"  > 
                            <table class="table table-striped table-bordered bootstrap-datatable dataTable " id="ItTableStatsPanel" >
                                <thead>
                                    <tr> 
                                        <th>Iteration N°</th>
                                        <th> Active ? </th>
                                        <th>Delivery</th>
                                        <th>Environment</th>
                                        <th>Author</th> 
                                        <th>Description</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead> 
                                    <?php  if(isset($ei_iterations)): ?>
                                   <?php $listForSearchBoxParams=$url_tab;
                                    $listForSearchBoxParams['ei_iterations']=$ei_iterations;
                                    $listForSearchBoxParams['display_check_box']=false;
                                    include_partial('eiiteration/listForSearchBox',$listForSearchBoxParams); ?>
                                                    
                                    <?php endif; ?> 
                            </table> 
                        </div>  
                    </div>  
                <?php if(isset($ei_impacted_functions_stats_with_params) && ($nbImpacts=count($ei_impacted_functions_stats_with_params))> 0):  ?>
                <?php $neverEx=0 ; $neverSuccess=0; $exWithErrs=0; $exWithLessThanFiveDiffVars=0; $exWithMorethanFiveDiffVars=0; ?>
                <?php $neverExTab=array() ; $neverSuccessTab=array(); $exWithErrsTab=array(); $exWithLessThanFiveDiffVarsTab=array(); $exWithMorethanFiveDiffVarsTab=array(); ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div id="<?php echo "hero-donut-".$delivery_id."-2"?>" class="graph" style="height: 271px;"></div> 
                </div>
                
                <?php foreach ($ei_impacted_functions_stats_with_params as $fct):
                    if(!isset($fct['iteration_id']) || $fct['iteration_id']==null):
                        $neverEx++; $neverExTab[]=$fct;
                    endif;
                    if(isset($fct['iteration_id']) && $fct['iteration_id']!=null && $fct['nbExOk']==0):
                        $neverSuccess++; $neverSuccessTab[]=$fct;
                    endif;
                    if(isset($fct['iteration_id']) && $fct['iteration_id']!=null && $fct['nbExOk']!=0 && $fct['nbExKo']!=0):
                        $exWithErrs++;  $exWithErrsTab[]=$fct;
                    endif;
                    if(isset($fct['iteration_id']) && $fct['iteration_id']!=null && $fct['nbExOk']!=0 && $fct['nbExKo']==0 && $fct['nbMinDistinctParams']<5 ):
                        $exWithLessThanFiveDiffVars++;  $exWithLessThanFiveDiffVarsTab[]=$fct;
                    endif;
                    if(isset($fct['iteration_id']) && $fct['iteration_id']!=null && $fct['nbExOk']!=0 && $fct['nbExKo']==0 && $fct['nbMinDistinctParams']>=5 ):
                        $exWithMorethanFiveDiffVars++;  $exWithMorethanFiveDiffVarsTab[]=$fct;
                    endif;
                endforeach; 
                $datas2[]=array('label'=>"Success with various datas(>=5)", 'value' =>  $exWithMorethanFiveDiffVars);
                $datas2[]=array('label'=>"Success with various datas(<5)", 'value' =>  $exWithLessThanFiveDiffVars);
                $datas2[]=array('label'=>"Executed with errors", 'value' =>  $exWithErrs);
                $datas2[]=array('label'=>"Never successful", 'value' =>  $neverSuccess);
                $datas2[]=array('label'=>"Never executed", 'value' =>  $neverEx);
                 ?> 
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                    <div class="panel panel-default eiFluidPanel" >
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog" style="background-color: #58a155;padding:8px;"></i> 
                                <span class="break"></span> Successful with various datas(>=5) :<strong><?php echo number_format(($nbImpacts!=0?$exWithMorethanFiveDiffVars/$nbImpacts:0)*100,2).'%' ?></strong>
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none"> 
                            <?php if(isset($exWithMorethanFiveDiffVarsTab) && count($exWithMorethanFiveDiffVarsTab)>0):  ?>
                                    <?php $minifyVar=$url_tab; $minifyVar['kal_functions']=$exWithMorethanFiveDiffVarsTab;?> 
                                    <?php include_partial('kalfonction/minifyTable',$minifyVar) ?>
                                    <?php endif;?>
                        </div>
                    </div>
                    <div class="panel panel-default eiFluidPanel">
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog" style="background-color: #F4DC00;padding:8px;"></i> 
                                <span class="break"></span> Successful with various datas(<5) :<strong><?php echo number_format(($nbImpacts!=0?$exWithLessThanFiveDiffVars/$nbImpacts:0)*100,2).'%' ?></strong>
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none">  
                            <?php if(isset($exWithLessThanFiveDiffVarsTab) && count($exWithLessThanFiveDiffVarsTab)>0):  ?>
                                    <?php $minifyVar=$url_tab; $minifyVar['kal_functions']=$exWithLessThanFiveDiffVarsTab;?> 
                                    <?php include_partial('kalfonction/minifyTable',$minifyVar) ?>
                                    <?php endif;?>
                        </div>
                    </div>
                    <div class="panel panel-default eiFluidPanel">
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog" style="background-color: #dea33f;padding:8px;"></i> 
                                <span class="break"></span> Executed with errors :<strong><?php echo number_format(($nbImpacts!=0?$exWithErrs/$nbImpacts:0)*100,2).'%' ?></strong>
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none"> 
                            <?php if(isset($exWithErrsTab) && count($exWithErrsTab)>0):  ?>
                                    <?php $minifyVar=$url_tab; $minifyVar['kal_functions']=$exWithErrsTab;?> 
                                    <?php include_partial('kalfonction/minifyTable',$minifyVar) ?>
                                    <?php endif;?>
                        </div>
                    </div>
                    <div class="panel panel-default eiFluidPanel">
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog" style="background-color: #d8473d;padding:8px;"></i> 
                                <span class="break"></span> Never successful (Executed but never in success) :<strong><?php echo number_format(($nbImpacts!=0?$neverSuccess/$nbImpacts:0)*100,2).'%' ?></strong> 
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none">  
                            <?php if(isset($neverSuccessTab) && count($neverSuccessTab)>0):  ?>
                                    <?php $minifyVar=$url_tab; $minifyVar['kal_functions']=$neverSuccessTab;?> 
                                    <?php include_partial('kalfonction/minifyTable',$minifyVar) ?>
                                    <?php endif;?>
                        </div>
                    </div>
                    <div class="panel panel-default eiFluidPanel" >
                        <div class="panel-heading">
                            <h2>
                                <i class="fa fa-cog" style="background-color: #1C1E1E;padding:8px;"></i> 
                                <span class="break"></span> Never executed :<strong><?php echo number_format(($nbImpacts!=0?$neverEx/$nbImpacts:0)*100 ,2).'%' ?></strong> 
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none">
                            <?php if(isset($neverExTab) && count($neverExTab)>0):  ?>
                            <?php $minifyVar=$url_tab; $minifyVar['kal_functions']=$neverExTab;?> 
                            <?php include_partial('kalfonction/minifyTable',$minifyVar) ?>
                            <?php endif;?>
                        </div>
                    </div>    
                </div>
                <?php endif;?>
               
            </div>
        </div> 
<?php if(isset($ei_impacted_functions_stats_with_params) && ($nbImpacts=count($ei_impacted_functions_stats_with_params))> 0):  ?>
<script>    
          function generateHeroDonut(delivery_id,numDonut,datas,colors){
               if($("#hero-donut-"+delivery_id+"-"+numDonut).length >0){  
                   window[delivery_id+"-"+numDonut]= Morris.Donut({
                       element: "hero-donut-"+delivery_id+"-"+numDonut,
                       resize : true,  
                       data: datas,
                       colors: colors,
                       formatter: function (y) {
                           return y
                       }
                   });
               } 
           }
           $(document).ready(function () { 
                               
                            colors2[0]= "#58a155";  colors2[1]= "#F4DC00";  colors2[2]="#dea33f"; colors2[3]= "#d8473d";colors2[4]="#1C1E1E";
                               $(window).resize(function() { 
                                   window[<?php echo $delivery_id  ?> + "-2"].redraw();    
                               }); 
                               <?php if(isset($datas2)): ?>
                               generateHeroDonut(<?php echo $delivery_id ?>,2,<?php print_r(json_encode($datas2)); ?>,colors2); 
                               <?php endif; ?>  
                               });
</script>
<?php endif; ?>
<script src="/js/pages/charts-flot.js"></script>
<?php endif; ?>