<form id="searchCampaignForm" class="form-horizontal" method="POST"
      action="<?php echo url_for2('campaign_list',array('project_id'=>$project_id,'project_ref'=> $project_ref) ) ?>">
    <div class="row"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class=" form-group">
                 <label class="control-label" for="search_campaign_by_title">Title</label>
                 <div class="controls">
                    <?php echo $campaignSearchForm['title']->render() ?>    
                 </div>
             </div>
            <div class=" form-group">
                 <label class="control-label" for="search_campaign_by_author">Author</label>
                 <div class="controls"> 
                    <?php echo $campaignSearchForm['author']->render() ?>    
                   <script>   
                       var subjects = <?php print_r(json_encode($campaignAuthors->getRawValue())); ?>   
                       $('#search_campaign_by_author').typeahead({source: subjects , items: 10})  
                   </script>
                 </div>
             </div> 
                <button  class="btn btn-success btn-sm pull-left" type="submit">
                     Search
                </button> 
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
            <div class=" form-group">
                 <label class="control-label" for="search_campaign_by_delivery">Delivery</label>
                 <div class="controls"> 
                    <?php echo $campaignSearchForm['delivery']->render() ?>
                 </div>
             </div> 
        </div>  
         
    <hr/>  

</form> 

              
            