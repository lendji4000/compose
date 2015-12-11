<?php 
 $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'delivery_id'=>$ei_delivery->getId() );    
 ?>

<link href="/assets/css/jquery.mmenu.css" rel="stylesheet">  
<!-- page css files -->
<link href="/css/climacons-font.css" rel="stylesheet"> 
<link href="/css/plugins/morris/css/morris.css" rel="stylesheet">  
<!-- Themes -->
<link href="/css/projects/themes.min.css" rel="stylesheet"> 
<script>var colors1=[],colors2=[],colors3=[];</script>  
    
<div id="delStats"> 
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
   
   <div class="row" id="eiProjectDashboardProgress">
       <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2>
                    <?php echo ei_icon('ei_subject')?>
                    <span class="break"></span> Interventions impacts stats
                </h2> 
            </div>
            <div class="panel-body">   
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                     <?php $datas1[]=array('label'=>"Interventions with impacts", 'value' => count($bugsWithImpacts));
                           $datas1[]=array('label'=>"Interventions without impacts", 'value' => count($bugsWithoutImpacts));?> 
                    <div id="<?php echo "hero-donut-".$delivery_id."-1"?>" class="graph" style="height: 271px;"></div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                    <div class="panel panel-default eiFluidPanel " id="bugsWithImpacts">
                        <div class="panel-heading">
                            <h2>
                                <?php echo ei_icon("ei_subject",null,null,"Interventions with impacts","interventionWithImpact") ?> 
                                <span class="break"></span> Interventions with impacts : <strong><?php echo count($bugsWithImpacts)  ?></strong>
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none"> 
                            <?php $miniTableVars =$url_tab; $miniTableVars['ei_subjects']=$bugsWithImpacts ?>
                            <?php include_partial("eisubject/miniTable",$miniTableVars); ?>
                        </div>
                    </div>
                    <div class="panel panel-default eiFluidPanel " id="bugsWithoutImpacts">
                        <div class="panel-heading">
                            <h2>
                                <?php echo ei_icon("ei_subject",null,null,"Interventions without impacts","interventionWithoutImpact") ?> 
                                <span class="break"></span> Interventions without impacts :<strong><?php echo count($bugsWithoutImpacts)  ?></strong> 
                            </h2> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none"> 
                            <?php $miniTableVars =$url_tab; $miniTableVars['ei_subjects']=$bugsWithoutImpacts ?>
                            <?php include_partial("eisubject/miniTable",$miniTableVars); ?>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
       <?php $iterationBlocStats=$url_tab; 
       $iterationBlocStats['current_iteration']=isset($current_iteration)?$current_iteration:null;
       $iterationBlocStats['ei_delivery']=$ei_delivery;
       $iterationBlocStats['ei_iterations']=isset($ei_iterations)?$ei_iterations:null;       
       $iterationBlocStats['ei_impacted_functions_stats_with_params']=$ei_impacted_functions_stats_with_params; 
       include_partial("eiiteration/iterationBlocStats",$iterationBlocStats) ?>  
      
       <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2>
                    <?php echo ei_icon("ei_subject") ?>
                    <span class="break"></span>  Interventions progression
                </h2> 
            </div>
            <div class="panel-body">   
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div id="<?php echo "hero-donut-".$delivery_id."-3"?>" class="graph"  style="height: 271px;"></div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6 ei-smallstat-group">
                    <?php if(isset($bugsByStates) && count($bugsByStates) >0): ?>
                    <?php foreach( $bugsByStates as $i=> $state):   ?>  
                     <?php $datas3[]=array('label'=> $state['st_name'], 'value' => $state['nbBugs']); ?> 
                    <div class="panel panel-default eiPanel eiFluidPanel">
                        <div class="panel-heading"> 
                            <?php $bgUri=$url_tab; unset($bgUri['delivery_id']); $bgUri['delivery'] =$ei_delivery->getId(); $bgUri['state']=array($state['state_id']) ?>
                            <a href="<?php echo url_for2("subjects_list",$bgUri) ?>" style="text-decoration:none" target="_blank">
                                <h2>
                                    <i class="icon-wrench-gear6-6 " style="padding:8px;  background-color: <?php echo $state['color_code'] ?> "></i>
                                    <span class="title"> <?php echo $state['st_name'] ?> <strong> (<?php echo $state['nbBugs'] ?>)</strong></span> 
                                </h2> 
                            </a> 
                            <div class="panel-actions"> 
                                <a class="btn-minimize" href="#"><i class="fa fa-chevron-down"></i></a> 
                            </div>
                        </div>
                        <div class="panel-body" style="display:none"> 
                            <?php if(isset($stateBugs[$state['state_id']])): ?>
                            <?php $miniTableVars =$url_tab; $miniTableVars['ei_subjects']=$stateBugs[$state['state_id']]['ei_subjects'] ?>
                            <?php include_partial("eisubject/miniTable",$miniTableVars); ?> 
                            <?php endif; ?>
                        </div>
                    </div> 
                        
                    <?php endforeach; ?> 
                    <?php endif; ?>
                    <script>    
                        <?php if(isset($bugsByStates) && count($bugsByStates) >0): ?>
                        <?php foreach( $bugsByStates as $i=> $state):   ?>  
                            colors3[<?php echo $i ?>] = "<?php echo $state['color_code'] ?>"  ;
                        <?php endforeach; ?> 
                        <?php endif; ?>   
                    </script>
                     
                </div>
            </div>
        </div>       
       
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
                              
                            colors1[0] = "#58a155"  ; colors1[1] = "#d8473d"  ; 
                               $(window).resize(function() {
                                   window[<?php echo $delivery_id  ?> + "-1"].redraw();  
                                   window[<?php echo $delivery_id  ?> + "-3"].redraw();  
                               });
                               <?php if(isset($datas1)): ?>
                               generateHeroDonut(<?php echo $delivery_id ?>,1,<?php print_r(json_encode($datas1)); ?>,colors1); 
                               <?php endif; ?> 
                                generateHeroDonut(<?php echo $delivery_id ?>,3,<?php print_r(json_encode($datas3)); ?>,colors3); 
                               });
</script>
<script src="/js/pages/charts-flot.js"></script> 

<!--Fenêtre modale de recherche des itérations pour la génération des statistiques d'une livraison -->
<div id="searchIterationForStatsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="searchIterationForStatsModal" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="searchIterationForStatsModalLabel"></h3>  
                <div class="eiLoading">   <i class="fa fa-spinner fa-spin fa-4x" ></i>     </div>
            </div>
            <div class="modal-body " id="searchIterationForStatsModalBody"> 
                <?php $iterationSearchBoxForDelStatsParams= $url_tab;
                      $iterationSearchBoxForDelStatsParams['form']=$iterationSearchStatsForm;
                      $iterationSearchBoxForDelStatsParams['iterationsAuthors']=$iterationsAuthors;
                      $iterationSearchBoxForDelStatsParams['ei_profiles']=$ei_profiles;
                       ?>
                    <?php include_partial("eiiteration/iterationSearchBoxForDelStats",$iterationSearchBoxForDelStatsParams ) ?>  
                    <table class="table table-striped table-bordered bootstrap-datatable dataTable " id="ItTablePanel" >
                        <thead>
                            <tr>
                                <th class="datatable-nosort">
                                    <input  id="selectAllItForDelStats" type="checkbox" />
                                </th>
                                <th>Iteration N°</th>
                                <th> Active ? </th>
                                <th>Delivery</th>
                                <th>Environment</th>
                                <th>Author</th> 
                                <th>Description</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody> 
                    </table> 
            </div>
            <div class="modal-footer" id="searchIterationForStatsModalFooter">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button> 
                <?php $getStatsForSelectedIdParams=$url_tab; $getStatsForSelectedIdParams['delivery_id']=$ei_delivery->getId();
                                $getStatsForSelectedIdParams['action']="getDelStatsForManyIterations" ?>
                <a id="getStatsForSelectedId" title="Get delivery statistics for selected iterations" class="btn btn-sm btn-success"
                   itemref="<?php echo url_for2("ei_iteration_global",$getStatsForSelectedIdParams) ?>">
                                        <?php echo ei_icon("ei_stats") ?> Get statistics
                </a>
            </div>
        </div>
    </div>
</div>