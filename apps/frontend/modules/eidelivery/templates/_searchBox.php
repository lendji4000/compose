<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);  
?> 
<form id="searchDeliveryForm" class="form-horizontal" method="POST"
      action="<?php echo url_for2('searchDeliveries',$url_params ) ?>">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_search') ?> Search Box</h2> 
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2" for="text-input">
                            Title
                        </label>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <?php echo $deliverySearchForm['title']->renderError() ?>
                            <?php echo $deliverySearchForm['title']->render() ?>
                            <script>
                                var ei_delivery_titles = <?php print_r(json_encode($deliveryTitles->getRawValue())); ?>
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2" for="text-input">
                            Author
                        </label>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <?php echo $deliverySearchForm['author']->renderError() ?>
                            <?php echo $deliverySearchForm['author']->render() ?>
                            <script>
                                var ei_delivery_authors = <?php print_r(json_encode($deliveryAuthors->getRawValue())); ?>
                            </script>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3" for="text-input">
                            State
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <?php echo $deliverySearchForm['state']->renderError() ?>
                            <?php echo $deliverySearchForm['state']->render() ?>
                            <script>
                                var ei_delivery_authors = <?php print_r(json_encode($deliveryAuthors->getRawValue())); ?>
                            </script>
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3"  >
                            Start date
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <div class="input-group input-append  date" id="datetimepickerDeliveryStartDate"   >
                                <?php echo $deliverySearchForm['start_date'] ?>
                                <span class="input-group-addon add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3"  >
                            Effective Date
                        </label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            <div class="input-group input-append  date" id="datetimepickerDeliveryEndDate"   >
                                <?php echo $deliverySearchForm['end_date'] ?>
                                <span class="input-group-addon add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button  class="btn btn-success btn-sm eiBtnSearch" type="submit"
                     id="<?php echo ((isset($is_ajax_request) && $is_ajax_request)? 'loadDelForStepsForm':'eiSearchDeliveries') ?>">
                <?php echo ei_icon('ei_search') ?> Search
            </button>
        </div>
    </div>
</form> 

              
            