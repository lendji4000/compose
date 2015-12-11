<!--comments--> 

<link href="/assets/css/jquery.mmenu.css" rel="stylesheet"> 

<!-- page css files -->
<link href="/css/climacons-font.css" rel="stylesheet">

<link href="/css/plugins/fullcalendar/css/fullcalendar.css" rel="stylesheet">
<link href="/css/plugins/morris/css/morris.css" rel="stylesheet">
<link href="/css/plugins/jvectormap/css/jquery-jvectormap-1.2.2.css" rel="stylesheet">
 

<!-- Themes -->
<link href="/css/projects/themes.min.css" rel="stylesheet"> 
<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref); 
$username= $guardUser->getUsername() ;
?>
    <?php if ($sf_user->hasFlash('reload_success')): ?>
    <p class="flash_msg_success"> 
    <?php echo $sf_user->getFlash('reload_success') ?>
    </p>
<?php endif; ?>

    <?php if ($sf_user->hasFlash('reload_error')): ?>
    <p class="flash_msg_error"> 
    <?php echo $sf_user->getFlash('reload_error') ?>
    </p>
<?php endif; ?>
    <?php if ($sf_user->hasFlash('Suppression_scenario_success')): ?>
    <p class="flash_msg_success"> 
    <?php echo $sf_user->getFlash('Suppression_scenario_success') ?>
    </p>
<?php endif; ?>
<?php if ($sf_user->hasFlash('msg_success')): ?>

    <div class="flash_msg_success alert alert-success pagination-centered">
        <strong> Success ! </strong>
        <a class="close" data-dismiss="alert" > x</a>
        <?php if ($sf_user->hasFlash('msg_success')): ?>
            <?php echo $sf_user->getFlash('msg_success', ESC_RAW) ?>
    <?php endif; ?>    
    </div> 
<?php endif; ?> 
    
    
<div id="eiProjectDashboard">
    <div class=" " style="opacity: 1; min-height: 504px;">
        <script src="/js/plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
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
        <script src="/js/plugins/jvectormap/js/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="/js/plugins/jvectormap/js/jquery-jvectormap-world-mill-en.js"></script>
        <script src="/js/plugins/jvectormap/js/gdp-data.js"></script>
        <!--<script src="/js/plugins/gauge/gauge.min.js"></script>--> 
        <script src="/js/pages/index.js"></script>
        <!--Définition des couleurs pour les différents statuts de bug--> 
        
            <?php if (isset($lastExTab)): ?><!-- Dernier scénario executé-->
            <script>
                lastExTab = <?php print_r(json_encode($lastExTab->getRawValue())); ?>
            </script> 
            <?php endif; ?> 
            <script>   
                bugStatesColors=[];
                <?php if(isset($projectBugsStates) && count($projectBugsStates) >0): ?>
                <?php foreach( $projectBugsStates as $i=> $state):   ?>  
                    bugStatesColors[<?php echo $i ?>] = "<?php echo $state['color_code'] ?>"  ;
                <?php endforeach; ?> 
                <?php endif; ?>   
            </script>
   
   <div class="row" id="eiProjectDashboardProgress">
      <script> 
          var datas1=[], datas2=[], datas3=[];
           var           colors1=[],colors2=[],colors3=[];
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
                  </script>
       <?php if(isset($tabDels) && count($tabDels) >0): ?>
       
       <?php  foreach ($tabDels as $k => $tabDel): $datas1=array();$datas2=array(); $datas3=array(); $nbSub=0; $nbCloseDel=0; $nbNonAssignBug=0;
           if(count($tabDel)>0):  foreach($tabDel as $i=> $tab):      
                        $delName=$tab['d_name']; $nbSub+=$tab['nbDelSub']; $nbNonAssignBug+=$tab['nbDelUserNoSub'];
                        $delDate=$tab['d_delivery_date'];
                        if($tab['st_close_del_state']==1): $nbCloseDel+=$tab['nbDelSub'];   endif;
                        $datas1[]=array('label'=> $tab['st_name'], 'value' => $tab['nbDelSub']);
                         $datas2[]=array('label'=> $tab['st_name'], 'value' => $tab['nbDelUserSub']);
                          //$datas3[]=array('label'=> $tab['st_name'], 'value' => $tab['nbDelUserNoSub']);?>  
                  <?php endforeach; endif; ?>
                <div class="col-lg-4 col-md-4 col-sm-6 eiProjectDashboardProgress"> 
                    <div class="panel panel-default eiPanel">
                            <div class="panel-heading">
                                <h2><?php echo ei_icon('ei_delivery') ?> 
                                <?php  $getDeliverySubjects = $url_tab;
                                    $getDeliverySubjects['delivery_id']=$k;   ?>
                                    <a href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>"
                                       class="tooltipObjTitle "   >
                                        <strong><?php echo "D".$k ?>    /   </strong>   <?php echo $delName ?>
                                    </a>     
                                </h2>
                                <ul class="nav nav-tabs">
                                    <li>
                                         <?php echo substr($delDate,0,10);  ?> &nbsp;&nbsp;
                                    </li>
                                </ul>
                                <div class="panel-actions">  
                                </div> 
                            </div>
                            <div class="panel-body"> 
                    <div class="row eiProjectDashboardProgressSmItem"> 
                        <div class="col-lg-7 col-md-7 col-sm-8 " > 
                            <div id="<?php echo "hero-donut-".$k."-2"?>" class=" graph" style="height: 200px;"></div>
                            <h6 class="center text-center">
                                <?php echo ei_icon('ei_user') ?> 
                                <strong> <?php echo $username."    ( Me )" ?> </strong>    
                            </h6>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-4 " >  
                                <div id="<?php echo "hero-donut-".$k."-1"?>" class=" graph" style="height: 160px;"></div> 
                                <h6 class="center text-center">
                                    <?php echo ei_icon('ei_team') ?> 
                                    <strong> Team </strong>    
                                </h6>
                        </div>
                    </div>
                       
                    <script>
                         $(document).ready(function () { 
                                $(window).resize(function() {
                                    window[<?php echo $k ?> +"-1"].redraw();
                                    window[<?php echo $k ?> +"-2"].redraw(); 
                                });
                                generateHeroDonut(<?php echo $k ?>,1,<?php print_r(json_encode($datas1)); ?>,bugStatesColors);
                                generateHeroDonut(<?php echo $k ?>,2,<?php print_r(json_encode($datas2)); ?>,bugStatesColors); 
                                });
                                                                     </script>
                         
                            </div>
                        <div class="panel-footer">  
                            <span class="text-danger">
                                <?php if (isset($nbNonAssignBug) && $nbNonAssignBug > 0): ?>
                                <strong><?php echo ei_icon('ei_subject')."&nbsp;"  ?><?php echo $nbNonAssignBug."&nbsp;" ?></strong>  unassigned !
                                <?php else : ?> &nbsp;
                                <?php endif; ?>
                            </span>  
                        </div>
                            
                    </div>
                </div>
                 <?php       
        endforeach;
        endif; ?>   
  </div>          
  

            <div class="panel panel-default eiPanel" id="eiProjectDashboardToDoList">
                <div class="panel-heading">
                    <h2><?php echo ei_icon('ei_subject') ?> To do list</h2>
                    <div class="panel-actions">  
                    </div>
                    <ul class="nav nav-tabs" id="recent">
                        <li class="active"><a href="#Defects">Defects (<?php echo count($defectsUserBugs) ?>)</a></li>
                        <li><a href="#Kalifasts">Kalifast(<?php echo count($kalifastUserBugs) ?>)</a></li>
                        <li><a href="#Enhancements">Enhancements (<?php echo count($enhancementUserBugs) ?>)</a></li>
                        <li><a href="#ServiceRequest">Service requests (<?php echo count($serviceRequestUserBugs) ?>) </a></li>
                    </ul>
                </div>
                <div class="panel-body table-responsive">
                    <div class="tab-content">
                        <div class="tab-pane active" id="Defects"> 
                                    <?php $homePageSubjectList=$url_tab; $homePageSubjectList['bugList']=$defectsUserBugs ;
                                    $homePageSubjectList['datatableId']='dataTableDefects' ?>
                                    <?php include_partial('eisubject/homePageSubjectList',$homePageSubjectList) ?>  									
                                
                        </div>	
                        <div class="tab-pane" id="Kalifasts"> 
                                    <?php $homePageSubjectList=$url_tab; $homePageSubjectList['bugList']=$kalifastUserBugs ;
                                    $homePageSubjectList['datatableId']='dataTableKalifasts'?>
                                    <?php include_partial('eisubject/homePageSubjectList',$homePageSubjectList) ?>   
                        </div>
                        <div class="tab-pane" id="Enhancements"> 
                                    <?php $homePageSubjectList=$url_tab; $homePageSubjectList['bugList']=$enhancementUserBugs;
                                    $homePageSubjectList['datatableId']='dataTableEnhancements' ?>
                                    <?php include_partial('eisubject/homePageSubjectList',$homePageSubjectList) ?>   
                        </div>
                        <div class="tab-pane" id="ServiceRequest"> 
                                    <?php $homePageSubjectList=$url_tab; $homePageSubjectList['bugList']=$serviceRequestUserBugs;
                                    $homePageSubjectList['datatableId']='dataTableServiceRequest'  ?>
                                    <?php include_partial('eisubject/homePageSubjectList',$homePageSubjectList) ?>  
                               
                        </div>
                    </div>	 	
                </div>
            </div>  	        



</div>
</div>     
    <script src="/js/pages/charts-flot.js"></script> 
     