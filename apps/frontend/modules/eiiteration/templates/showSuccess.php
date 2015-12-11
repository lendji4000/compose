<link href="/assets/css/jquery.mmenu.css" rel="stylesheet">  
<!-- page css files -->
<link href="/css/climacons-font.css" rel="stylesheet"> 
<link href="/css/plugins/morris/css/morris.css" rel="stylesheet">  
<!-- Themes -->
<link href="/css/projects/themes.min.css" rel="stylesheet"> 
<?php if(isset($ei_project) && isset($ei_delivery)): ?>
<?php 
 $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'delivery_id'=>$ei_iteration->getDeliveryId(),
            'iteration_id'=>$ei_iteration->getId());  
?>
<div class="row" id="showDeliveryIteration"> 
    <div class="panel panel-default eiPanel" class=" ">
        <div class="panel-heading">
            <h2>   <?php echo ei_icon('ei_iteration') ?> 
                <span class="break"></span>  <?php echo $ei_iteration->getId() ?>  
            </h2>
            <ul class="nav nav-tabs">
                    <li >
                        <?php $ei_iteration_global_uri=$url_tab; $ei_iteration_global_uri['action']='index'; unset($ei_iteration_global_uri['iteration_id']) ?>
                        <a class=" " href="<?php echo url_for2('ei_iteration_global', $ei_iteration_global_uri) ?>#"
                           title="Delivery iterations"  id="AccessDeliveriesIterationsOnShow">
                            <?php echo ei_icon('ei_iteration') ?> All 
                        </a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">  
                
            </div>
            <div class="panel panel-footer"> 
            </div>    
        </div>  
    <script>var colors1=[],colors2=[],colors3=[];</script>  
     
        <!--<script src="/js/plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>-->
        <script src="/js/plugins/moment/moment.min.js"></script>
        <script src="/js/plugins/fullcalendar/js/fullcalendar.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.pie.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.stack.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="/js/plugins/flot/jquery.flot.spline.min.js"></script>
        <script src="/js/plugins/autosize/jquery.autosize.min.js"></script>
        <script src="/js/plugins/placeholder/jquery.placeholder.min.js"></script>
        <script src="/js/plugins/raphael/raphael.min.js"></script>
        <script src="/js/plugins/morris/js/morris.min.js"></script>
         
        <script src="/js/pages/index.js"></script> 
    
        <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-cog"></i>
                    <span class="break"></span>  Impacted function executions
                </h2>  
            </div>
            
            
            
            <div class="panel-body">  
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
                                <span class="break"></span> Never executed :<strong><?php echo number_format(($nbImpacts!=0?$neverEx/$nbImpacts:0)*100,2).'%' ?></strong> 
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
                                               window[<?php echo $ei_delivery->getId() ?> + "-2"].redraw();   
                                           }); 
                                           generateHeroDonut(<?php echo $ei_delivery->getId() ?>,2,<?php print_r(json_encode($datas2)); ?>,colors2);  
                                           });
            </script>
<script src="/js/pages/charts-flot.js"></script> 
</div>



<?php endif; ?>

 


