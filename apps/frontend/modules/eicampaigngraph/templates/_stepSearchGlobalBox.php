<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<!--Box de recherche globale  lors de l'édition du contenu d'une campagne -->
<div class="row" id="stepSearchGlobalBox"> 
    <div>
        <!--<h4 class=""> <i class="icon icon-search"></i> Search By </h4>-->
    </div>
    <div>
        <ul class="nav nav-tabs">
            <li class="<?php echo(!(isset($ei_delivery) || isset($ei_subject))? 'active col-lg-4 col-md-4' : 'col-lg-4 col-md-4' ) ?>">
                <a href="#campaignSearchBoxForSteps"  data-toggle="modal" id="openCampaignSearchBoxForSteps"> 
                    <?php echo   ei_icon('ei_campaign').'&nbsp; '.(!(isset($ei_delivery) || isset($ei_subject)|| isset($ei_campaign))?
                          'Lonely Campaigns' : '' ) ?>
                    <?php  echo '&nbsp; '.((isset($ei_campaign) )?
                          MyFunction::troncatedText($ei_campaign, 10) : '' ) ?>
                </a>
            </li>
            <li class="<?php echo((isset($ei_delivery) && $ei_delivery!=null)? 'active col-lg-4 col-md-4' : 'col-lg-4 col-md-4' ) ?>">
                 
                <a href="#deliverySearchBoxForSteps"    data-toggle="modal" id="openDeliverySearchBoxForSteps">
                    <?php echo   ei_icon('ei_delivery').'&nbsp; '.((isset($ei_delivery) && $ei_delivery!=null)?
                          MyFunction::troncatedText($ei_delivery, 10) : '' ) ?>  
                </a>  
            </li>
            <li class="<?php echo((isset($ei_subject) && $ei_subject!=null)? 'active col-lg-4 col-md-4' : 'col-lg-4 col-md-4' ) ?>">
                <a href="#subjectSearchBoxForSteps"  data-toggle="modal" id="openSubjectSearchBoxForSteps">
                    <?php echo    ei_icon('ei_subject').'&nbsp;' .((isset($ei_subject) && $ei_subject!=null)?
                          MyFunction::troncatedText($ei_subject, 10) : '' ) ?> 
                    
                </a> 
            </li>
        </ul>  
    </div>




<!-- Box de recherche d'une livraison-->
<div id="deliverySearchBoxForSteps" class="modal  ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="deliverySearchBoxForStepsTitle">Deliveries advanced search</h4>  
                <input type="hidden" id="deliverySearchBoxForStepsLink" 
                       itemref="<?php echo url_for2('delivery_list',$url_tab) ?>" />
                <div  class="reloading_img_tree">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-body"  id="deliverySearchBoxForStepsContent">
                
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true" id="closeDeliverySearchBoxForSteps"> 
                Close 
            </a>
            </div>
        </div>
    </div>
</div>


<!-- Box de recherche d'un sujet -->
 <div id="subjectSearchBoxForSteps" class="modal  ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="subjectSearchBoxForStepsTitle">Intervention advanced search</h4>  
                <input type="hidden" id="subjectSearchBoxForStepsLink" 
                       itemref="<?php echo url_for2('subjects_list',$url_tab) ?>" />
                <div  class="reloading_img_tree">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-body"  id="subjectSearchBoxForStepsContent">
                <div  class="reloading_img_tree">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-footer">
                 <a href="#" id="closeSubjectSearchBoxForSteps" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
            </div>
        </div>
    </div>
</div>
<!-- Box de recherche d'une campagne-->
<div id="campaignSearchBoxForSteps" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="campaignSearchBoxForStepsTitle">Campaign advanced search</h4>  
                <input type="hidden" id="campaignSearchBoxForStepsLink"
                       itemref="<?php echo url_for2('campaign_list', $url_tab) ?>" />
                
            </div>
            <div class="modal-body" id="campaignSearchBoxForStepsContent">
                <div   class="reloading_img_tree">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"
                   id="closeCampaignSearchBoxForSteps"> 
                    Close
                </a>
            </div>
        </div>
    </div>
</div>

</div>