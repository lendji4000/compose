<!--Box de recherche des itérations pour les statistiques de livraison-->
<?php if(isset($form)): ?>

<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
);   
$uriForm=$url_params; $uriForm['action']="searchItForDelStats";
?>
<form id="iterationSearchBoxForDelStatsForm" class="form-horizontal" method="POST"
      action="<?php echo url_for2("ei_iteration_common",$uriForm ) ?>">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_search') ?> Search </h2>
            <div class="panel-actions"> 
                <a class="btn-minimize" href="#">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>

        <div class="panel-body"  >
            <div class="row"> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                    <div id="subjectDeliverySearchBlock" class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Delivery
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $form['delivery']->renderError() ?>
                            <?php echo $form['delivery']->render() ?> 
                        </div>  
                    </div>   
                    <div class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Author
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $form['author']->render() ?>    
                           <script>   
                               ei_iterations_authors = <?php print_r(json_encode($iterationsAuthors->getRawValue())); ?> 
                           </script> 
                        </div>  
                    </div>   
                    <div id="environmentIterationSearchBlock" class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Environment
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $form['environment']->renderError() ?>
                            <?php echo $form['environment']->render() ?> 
                        </div>  
                    </div> 
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
                    <div id="isActiveIterationSearchBlock" class="form-group"> 
                        <label class="control-label col-lg-3 col-md-3 col-sm- col-xs-3" for="text-input">
                            Is active ?
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $form['is_active']->renderError() ?>
                            <?php echo $form['is_active']->render() ?> 
                        </div>  
                    </div>  
                </div>  

            </div> 
        </div> 
        <div class="panel-footer"  > 
            <button  class="btn btn-success btn-sm "  type="submit"  id="searchIterationForDelStats">
                <?php echo ei_icon('ei_search') ?> Search 
            </button>
        </div>
    </div>
    
    
</form> 

              
            
 <?php endif; ?>

