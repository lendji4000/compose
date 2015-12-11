<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
    'contextRequest' => (isset($contextRequest)?$contextRequest:null),
     'is_ajax_request'=> (isset($is_ajax_request) && $is_ajax_request) ?true:false
);  
$routeToCall="searchSubjects";
if((isset($is_ajax_request) && $is_ajax_request) || (isset($contextRequest) && $contextRequest!='EiSubject')):
    $searchBoxChev=true; /* Cette variable permet de masquer la search box suivant le contexte d'affichage*/
endif;
/* On génère l'url de recherche suivant le contexte de navigation (delivery, subject, functions ,etc...) */
if(isset($contextRequest) && $contextRequest=="EiDelivery" && isset($ei_delivery) && $ei_delivery!=null):
    $url_params['delivery_id']=$ei_delivery->getId();
    $routeToCall="searchDeliverySubjects";
    $searchBoxChev=true; /* Cette variable permet de masquer la search box suivant le contexte d'affichage*/
endif;
if(isset($contextRequest) && $contextRequest=="EiFunction" && isset($kal_function) && $kal_function!=null):
    $url_params['function_id']=$kal_function->getFunctionId();
    $url_params['function_ref']=$kal_function->getFunctionRef();
    $routeToCall="subjectFunction";
    $url_params['action']='searchFunctionSubjects';
    $searchBoxChev=true; /* Cette variable permet de masquer la search box suivant le contexte d'affichage*/
endif;
?>
<form id="searchSubjectForm" class="form-horizontal" method="POST"
      action="<?php echo url_for2($routeToCall,$url_params ) ?>">
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
                            External ID
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $subjectSearchForm['external_id']->renderError() ?>
                            <?php echo $subjectSearchForm['external_id']->render() ?> 
                        </div>
                    </div>  
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Title
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $subjectSearchForm['title']->renderError() ?>
                            <?php echo $subjectSearchForm['title']->render() ?> 
                        </div>
                    </div>  
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Assignment
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $subjectSearchForm['assignment']->renderError() ?>
                            <?php echo $subjectSearchForm['assignment']->render() ?> 
                        </div>
                        <script>  
                            ei_subjects_assignments = <?php print_r(json_encode($assignUsers->getRawValue())); ?>   
                        </script> 
                    </div>   
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Author
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $subjectSearchForm['author']->render() ?>    
                           <script>   
                               ei_subjects_authors = <?php print_r(json_encode($subjectsAuthors->getRawValue())); ?> 
                           </script> 
                        </div>  
                    </div>   
                    <div id="subjectDeliverySearchBlock" class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Delivery
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $subjectSearchForm['delivery']->renderError() ?>
                            <?php echo $subjectSearchForm['delivery']->render() ?> 
                        </div>  
                    </div>   
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
                    <div class='row'>
                        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding'>
                            <span>State</span>
                            <div class="form-group"> 
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <?php echo $subjectSearchForm['state']->renderError() ?>
                                    <?php echo $subjectSearchForm['state']->render() ?> 
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding'>
                            <span>Priority</span>
                            <div class="form-group"> 
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <?php echo $subjectSearchForm['priority']->renderError() ?>
                                    <?php echo $subjectSearchForm['priority']->render() ?> 
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding'>
                            <span>Type</span>
                            <div class="form-group">  
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <?php echo $subjectSearchForm['type']->renderError() ?>
                                    <?php echo $subjectSearchForm['type']->render() ?> 
                                </div>
                            </div> 
                        </div>
                    </div> 

                </div>  

            </div> 
        </div> 
        <div class="panel-footer" style="display:<?php echo(isset($searchBoxChev) && $searchBoxChev)?'none':'block' ?>">
            <?php $btn_id=(isset($contextRequest) && $contextRequest=="interventionLink")?"loadIntForMigration":"loadSubForStepsForm" ?> 
            <button  class="btn btn-success btn-sm " 
                     <?php echo (isset($is_ajax_request) && $is_ajax_request)?'':'type="submit"' ?> 
             id="<?php echo ((isset($is_ajax_request) && $is_ajax_request)? $btn_id:'') ?>">
                <?php echo ei_icon('ei_search') ?> Search 
            </button>
        </div>
    </div>
    
    
</form> 

              
            