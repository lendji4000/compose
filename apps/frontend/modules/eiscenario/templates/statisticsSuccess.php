<link rel=" stylesheet" href="/js/plugins/xcharts/css/xcharts.min.css">


<?php 
if(isset($scenarioStats) && count($scenarioStats)>0):  
     
    foreach ($scenarioStats as $key => $scenarioStat): 
            $execTime=$scenarioStat['scenario_exe_time'];
            $lastExTabs[] = array($key,($execTime==null?0:$execTime),$scenarioStat['id']);  
            //if($execTime!=null):
            $lastEx[]=array(
                "x" =>$key,
                "y" => ($execTime==null?0:$execTime)/1000000,
                "z" => substr($scenarioStat['created_at'], 0,-9).'T'.substr($scenarioStat['created_at'], 11)
            );
            //endif;
        endforeach;
    endif;    
      
?>  
    <script>
        <?php if(isset($lastEx) && count($lastEx)>0): ?>
        lastEx = <?php print_r(json_encode($lastEx)); ?>  
        lastExTabs = <?php print_r(json_encode($lastExTabs)); ?> 
        <?php endif; ?>
    </script>
    
 
<div class="row" id="eiProjectDashboardLastExecutions">
        <div class="col-sm-12">
            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2>
                        <?php echo ei_icon('ei_scenario') ?> Scenario executions (<?php echo (isset($lastEx)?count($lastEx):0)?> executions ) 
                    </h2>
                </div>
                <div class="panel-body">
                    <!--<div id="twitterChart2" style="height:300px" ></div>-->
                    <?php if(isset($lastEx) && count($lastEx)>0): ?>
                    <figure class="demo" id="scenarioExecChart" style="height: 300px"></figure>
                    <?php else: ?>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Warning!</strong> No execution for this scenario ...
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!--/col-->

    </div><!--/row--> 
    
    
<?php if (isset($lastExTab)): ?>
    <script>
        lastExTab = <?php print_r(json_encode($lastExTab->getRawValue())); ?>
    </script> 
 
    <div class="row" id="eiProjectDashboardLastExecution">
        <div class="col-sm-12">
            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2><?php echo ei_icon('ei_scenario') ?> Last scenario execution</h2>
                </div>
                <div class="panel-body">
                    <div id="twitterChart" style="height:300px" ></div>
                </div>
            </div>
        </div><!--/col-->

    </div><!--/row--> 
<?php endif; ?>
<?php if(isset($lastEx) && count($lastEx)>0): ?>
<script src="/js/plugins/flot/jquery.flot.min.js"></script> 
<script src="/js/pages/charts-flot.js"></script> 
<script src="/js/plugins/d3/d3.min.js"></script> 
<script src="/js/plugins/xcharts/js/xcharts.min.js"> </script> 
<script src="/js/pages/charts-xcharts.js">  </script> 
<?php endif; ?>             
	 
	 
            
