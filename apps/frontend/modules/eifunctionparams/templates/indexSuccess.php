<?php if(isset($kal_function)  && isset($ei_project) && isset($ei_profile)):?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         "function_id" => $kal_function->getFunctionId(),
         "function_ref" => $kal_function->getFunctionRef());?>
<div id="functionParameters">
   <div class="panel panel-default eiPanel " id="InfunctionParameters"  > 
            <div class="panel-heading">
                <h2><?php echo ei_icon("ei_parameter")?>  IN parameters</h2>
                <div class="panel-actions">  
                </div>
            </div> 
            <div class="panel-body clearfix">
                <table class="table table-striped dataTable">
                    <thead id="inParamsHeader">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Default value</th>
                            <th colspan="2">Actions</th> 
                        </tr>
                    </thead>
                    <tbody id="inParams">
                        <?php if(count($functionParams['IN'])>0) :   ?>
                       <?php foreach($functionParams['IN'] as $key => $param): ?>
                        <?php $paramDatas=$url_tab;  $paramDatas['ei_function_has_param']=$param ?>
                        <?php include_partial('paramLine',$paramDatas) ?>
                        <?php endforeach; ?> 
                        <?php endif; ?>
                    </tbody>
                </table>    
            </div>
            <div class="panel panel-footer">
                <?php $addFunctionParamUri=$url_tab; $addFunctionParamUri['action']="new" ; $addFunctionParamUri['param_type']="IN" ?>
                 <a id="addInParam" class="addFunctionParam btn btn-success" data-toggle="modal" href="#functionParamModal"
                    itemref="<?php echo url_for2("functionParamsActions",$addFunctionParamUri)?> ">
                     <?php echo ei_icon("ei_add") ?>
                 </a>
        </div>
        </div> 
    <div class="panel panel-default eiPanel " id="outFunctionParameters"  > 
            <div class="panel-heading">
                <h2><?php echo ei_icon("ei_parameter")?>  Out parameters</h2>
                <div class="panel-actions">  
                </div>
            </div> 
            <div class="panel-body clearfix"> 
                <table class="table table-striped dataTable">
                    <thead id="outParamsHeader">
                        <tr>
                            <th>Name</th>
                            <th>Description</th> 
                            <th colspan="2">Actions</th> 
                        </tr>
                    </thead>
                    <tbody id="outParams">
                        <?php if(count($functionParams['OUT'])>0) :   ?>
                       <?php foreach($functionParams['OUT'] as $key => $param): ?>
                        <?php $paramDatas=$url_tab;  $paramDatas['ei_function_has_param']=$param ?>
                        <?php include_partial('paramLine',$paramDatas) ?>
                        <?php endforeach; ?> 
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <div class="panel panel-footer">
            <?php $addFunctionParamUri=$url_tab; $addFunctionParamUri['action']="new" ; $addFunctionParamUri['param_type']="OUT" ?>
            <a id="addOutParam" class="addFunctionParam btn btn-success" data-toggle="modal" href="#functionParamModal"
               itemref="<?php echo url_for2("functionParamsActions",$addFunctionParamUri)?> ">
                <?php echo ei_icon("ei_add") ?>
            </a>
        </div>
        </div> 
</div>

<?php endif; ?>


<!--Fenêtre modale d'ajout d'une fonction à partir de compose-->
<div id="functionParamModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="functionParamModal" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="functionParamModalLabel"></h3>  
                <div class="eiLoading">   <i class="fa fa-spinner fa-spin fa-4x" ></i>     </div>
            </div>
            <div class="modal-body " id="functionParamModalBody">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="saveFunctionParam" type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>