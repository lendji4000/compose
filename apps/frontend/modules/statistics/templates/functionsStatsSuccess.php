<?php  //if(isset($exFunctions)) : echo count($exFunctions); endif; ?>
<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

    ?>
<?php $formUri=$url_tab;  $formUri['action']="functionsStats" ?>

<form id="searchGeneralFunctionsStats" class="form-horizontal" method="POST" itemref="<?php echo url_for2("generalStats", $formUri) ?>" action="#">
    <div class="panel panel-default eiPanel" id=" ">
        <div class="panel-heading">
            <h2>
                <?php echo ei_icon('ei_stats') ?>
                <span class="break"></span>  Search   
            </h2> 
        </div>
        <div class="panel-body">  
            <div class="row"> 
                <div class="col-lg-7 col-md-7 col-sm-7 " >
                    <div class="smallstat"> 
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 control-label">Executed</label>
                            <div class="col-lg-10 col-md-10 col-sm-10"> 
                                <label class="checkbox-inline" for="inline-checkbox2">
                                    <input type="checkbox" id="inline_checkbox_all" name="searchStatsFunctionForm[all]" > <span class="label label-primary">All</span>
                                </label>
                                <label class="checkbox-inline" for="inline-checkbox2">
                                    <input type="checkbox" id="inline_checkbox_success" name="searchStatsFunctionForm[success]" ><span class="label label-success">Success</span> 
                                </label>
                                <label class="checkbox-inline" for="inline-checkbox3">
                                    <input type="checkbox" id="inline_checkbox_failed" name="searchStatsFunctionForm[failed]" > <span class="label label-danger">Failed</span> 
                                </label>
                                <label class="checkbox-inline" for="inline-checkbox1">
                                    <input type="checkbox" id="inline_checkbox_never_plan" name="searchStatsFunctionForm[never_plan]" > <span class="label label-default">Never planned</span> 
                                </label>
                                <label class="checkbox-inline" for="inline-aborted">
                                    <input type="checkbox" id="inline_checkbox_aborted" name="searchStatsFunctionForm[aborted]" > <span class="label label-default">Aborted</span>
                                </label> 
                            </div>
                        </div>
                    </div> 
                    <div class="smallstat"> 
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 col-sm-2 control-label">Criticity</label>
                            <div class="col-lg-10 col-md-10 col-sm-10"> 
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inline_checkbox_criticity_blank" name="searchStatsFunctionForm[criticity_blank]" > <span class="label label-Blank">Blank</span>
                                </label>
                                <label class="checkbox-inline" >
                                    <input type="checkbox" id="inline_checkbox_criticity_low" name="searchStatsFunctionForm[criticity_low]" ><span class="label label-Low">Low</span> 
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="inline_checkbox_criticity_medium" name="searchStatsFunctionForm[criticity_medium]" > <span class="label label-Medium">Medium</span> 
                                </label>
                                <label class="checkbox-inline" >
                                    <input type="checkbox" id="inline_checkbox_criticity_high" name="searchStatsFunctionForm[criticity_high]" > <span class="label label-High">High</span> 
                                </label> 
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="col-lg-5 col-md-5 col-sm-5 ">
                    <div class="row smallstatDate">
                        <div class="col-lg-1 col-md-1 col-sm-1"><strong>From</strong></div>
                        <div class="col-lg-11 col-md-11 col-sm-11">
                            <div id="datetimepickerSearchStatsMin" class="input-group input-append  date">
                                <input class="form-control col-lg-12 col-md-12 col-sm-12" data-format="yyyy-MM-dd" type="text" name="searchStatsFunctionForm[min_date]"   id="min_date">   
                                <span class="input-group-addon add-on">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div> 
                        </div>
                    </div>
                    <div>
                        <div class="col-lg-1 col-md-1 col-sm-1"><strong>To</strong></div>
                        <div class="col-lg-11 col-md-11 col-sm-11">
                            <div id="datetimepickerSearchStatsMax" class="input-group input-append  date">
                                <input class="form-control col-lg-12 col-md-12 col-sm-12" data-format="yyyy-MM-dd" type="text" name="searchStatsFunctionForm[max_date]"   id="ei_delivery_delivery_date">                        <span class="input-group-addon add-on">
                                   <i class="fa fa-calendar"></i>
                               </span>
                           </div> 
                        </div>
                    </div> 
                       
                </div> 

            </div> 
        </div> 
        <div class="panel-footer"> 
            <button class="btn btn-sm btn-success   " type="submit" id="searchFunctionStats">
                        <i class="fa fa-search"></i>   
                    </button>
        </div>
    </div>

</form>

<?php if(isset($listNone)): ?> 

<div class="panel panel-default eiPanel" id="EiGeneralStatsFunctionsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_function') ?> Functions ( )        </h2> 
       
    </div>
<div class="panel-body table-responsive" > 
        <i class="fa fa-4x fa-spin   fa-spinner " id="EiGeneralStatsFunctionsLoader"></i>
<!--        <table class="table table-striped table-condensed bootstrap-datatable  dataTable " id="EiGeneralStatsFunctionsTable"  >  
        </table>-->
    </div>
</div>
<?php else: ?>
<?php
$partialParams=$url_tab;
$partialParams['exFunctions']=$exFunctions;
include_partial('statistics/functionStatsList',$partialParams) ?> 
<?php endif; ?>