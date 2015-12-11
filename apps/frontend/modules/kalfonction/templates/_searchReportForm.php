<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'function_id'=> $function_id,
    'function_ref' => $function_ref
); 
$uriSearch = $url_params; $uriSearch['action']='statistics'; 
?>

<form id="funtionReportForm" class="form-horizontal" method="POST"
      action="<?php echo url_for2('functionActions',$uriSearch  ) ?>">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_search') ?> Search Box</h2>
            <div class="panel-actions"> 
                <a class="btn-minimize" href="#">
                    <i class="fa <?php echo(isset($searchBoxChev) && $searchBoxChev)?'fa-chevron-down':'fa-chevron-up' ?>"></i>
                </a>
            </div>
        </div>

        <div class="panel-body" style="display:<?php echo(isset($searchBoxChev) && $searchBoxChev)?'none':'block' ?>">
            <div class="row"> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Execution ID
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $functionReportForm['execution_id']->renderError() ?>
                            <?php echo $functionReportForm['execution_id']->render() ?> 
                        </div>
                    </div>   
                </div>    
            </div> 
            <div class="row"> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3"  >
                            Iteration ID
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $functionReportForm['iteration_id']->renderError() ?>
                            <?php echo $functionReportForm['iteration_id']->render() ?> 
                        </div>
                    </div>   
                </div>    
            </div> 
        </div>
        <div class="panel-footer" style="display:<?php echo(isset($searchBoxChev) && $searchBoxChev)?'none':'block' ?>">
            <button  class="btn btn-success btn-sm " type="submit"
             id="<?php echo ((isset($is_ajax_request) && $is_ajax_request)? 'loadSubForStepsForm':'') ?>">
                <?php echo ei_icon('ei_search') ?> Search 
            </button>
        </div>
    </div>
    
    
</form> 

              
            