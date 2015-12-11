<?php if($sf_user->hasFlash('no_result')): ?>
<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>No result! </strong> <?php echo $sf_user->getFlash('no_result') ?>
</div> 
<?php endif; ?>
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'contextRequest' => (isset($contextRequest)?$contextRequest:null),
         'is_ajax_request' => (isset($is_ajax_request) && $is_ajax_request) ? true:false
     )?>
<div class="row">  
        <?php $searchBox =$url_tab;  $searchBox['subjectSearchForm']=$subjectSearchForm ?>
            <?php $searchBox['subjectsAuthors']=$subjectsAuthors ?>
            <?php $searchBox['subjectsTitles']=$subjectsTitles ?>
            <?php $searchBox['assignUsers']=$assignUsers ?> 
            <?php $searchBox['ei_delivery']=(isset($ei_delivery)?$ei_delivery:null) ?> 
            <?php $searchBox['kal_function']=(isset($kal_function)?$kal_function:null) ?>
        <?php include_partial('searchBox', $searchBox) ?>  
        <hr/> 
        <div id="subjectList" class=" table-responsive"> 
            <div class="row">
                <div class=" col-lg-4 col-md-4 col-sm-4 col-xs-4 input-prepend ">
                    <div class="btn-group">  
                         <button class="btn">Actions group</button>
                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                        </button>
                        <ul class="dropdown-menu"> 
<!--                            <li> 
                                <a href="#multipleAssignmentBox" data-toggle="modal" id="openMultipleAssignmentBox">
                                    <i class="fa fa-user"></i> Assignments</a>
                            </li>-->
                            <li>  
                                <a href="#deliverySearchBoxForSteps"    data-toggle="modal">
                                    <?php echo ei_icon('ei_dashboard') ?>&nbsp;Deliveries
                                </a> 
                                <input id="chooseDelForManySub" type="hidden" 
                                       itemref="<?php echo url_for2('chooseDelForManySub',$url_tab)?>" />
                            </li>
                            <li> <a href="#" id="openGroupActionPriorityForm"><i class="fa fa-cog"></i> Priorities</a>  </li>
                            <li> <a href="#" id="openGroupActionTypeForm"><i class="fa fa-cog"></i> Types</a>  </li>
                            <li> <a href="#" id="openGroupActionStateForm"><i class="fa fa-cog"></i> States</a>  </li>
                        </ul>
                    </div> 
                </div> 
                <div class=" col-lg-4 col-md-4 col-sm-4 col-xs-4" id="groupActionForm">
                    <?php $groupStateForm=$url_tab;$groupPriorityForm=$url_tab;$groupTypeForm=$url_tab; ?>
                    <?php $groupStateForm['subjectStates']=$subjectStates;
                            $groupPriorityForm['subjectPriorities']=$subjectPriorities;
                            $groupTypeForm['subjectTypes']=$subjectTypes; ?>
                    <?php include_partial('eisubject/groupActionStateForm',$groupStateForm) ?>
                    <?php include_partial('eisubject/groupActionPriorityForm',$groupPriorityForm) ?>
                    <?php include_partial('eisubject/groupActionTypeForm',$groupTypeForm) ?>
                </div> 
            </div>
            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2><?php echo ei_icon('ei_list') ?> List </h2>
                    <div class="panel-actions"> 
                    </div>
                </div>
                <div class="panel-body">
                    <!--Menu de pagination des bugs-->
                    <?php
                    $pagerMenu=$url_tab ;$pagerMenu['current_page']=$current_page;
                    $pagerMenu['nb_pages']=$nb_pages;
                    $pagerMenu['nbEnr']=$nbEnr;
                    $pagerMenu['offset']=$offset;
                    $pagerMenu['max_subject_per_page']=$max_subject_per_page;
                    $pagerMenu['searchSubjectCriteria']=$searchSubjectCriteria;
                    $pagerMenu['contextRequest']=(isset($contextRequest)?$contextRequest:null);
                    $pagerMenu['ei_delivery']=(isset($ei_delivery)?$ei_delivery:null);
                    $pagerMenu['kal_function']=(isset($kal_function)?$kal_function:null);
                    
                    if($pagerMenu['nbEnr'] >= 10)
                        {
                            include_partial('eisubject/pagerMenu', $pagerMenu);
                        }
                    ?>
                    <!--Fin pagination bugs-->
                </div> 
                <div class="panel-body table-responsive" >
                    <!--Listing des bugs-->
                    <?php $eisubject_list=$url_tab ;$eisubject_list['ei_subjects']=$ei_subjects ?>
                    <?php $eisubject_list['module_context']='EiSubject' ?>
                    <?php include_partial('eisubject/list',$eisubject_list) ?>
                    <!--Fin listing des bugs-->
                </div>
                <div class="panel-footer">
                    <!--Menu de pagination des bugs-->
                    <?php  include_partial('eisubject/pagerMenu', $pagerMenu); ?> 
                    <!--Fin pagination bugs-->
                </div> 
        </div> 
    </div>  
<!-- Box de recherche d'une livraison--> 

<div id="deliverySearchBoxForSteps" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="deliverySearchBoxForStepsTitle">Deliveries advanced search</h4> 
                <input type="hidden" id="deliverySearchBoxForStepsLink"
                       itemref="<?php echo url_for2('delivery_list', $url_tab) ?>" />
            </div>
            <div class="modal-body"
            id="deliverySearchBoxForStepsContent"></div>
            <div class="modal-footer">
                 <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true" id="closeDeliverySearchBoxForSteps">
                     Close
                 </a>
            </div>
        </div>
    </div>
</div> 

<!-- Box de recherche d'assignations d'utilisateurs à plusieurs bugs en même temps --> 

<div id="multipleAssignmentBox" class="modal ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                 <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="multipleAssignmentBoxTitle">Multiple assignments </h4> 
                <input type="hidden" id="multipleAssignmentBoxLink"
                       itemref="<?php echo url_for2('delivery_list', $url_tab) ?>" />
            </div>
            <div class="modal-body" id="multipleAssignmentBoxContent">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                        <h2>Choose</h2> 
                        <?php if (isset($projectUsers) && count($projectUsers) > 0): ?>
                            <select id="ei_project_for_multiple_assign" class="selectpicker col-lg-10 col-md-10 col-sm-10 col-xs-10" 
                                    data-live-search="true" title="Select users to add in list">
                                <?php foreach ($projectUsers as $us): ?>
                                <option  class="assignUserItem" itemprop="<?php echo $us->getUsername()?>" itemid="<?php echo $us->getId() ?>"><?php echo $us->getUsername()?></option> 
                                <?php endforeach; ?>
                            </select> 
                        <?php endif; ?>
                    </div>  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                        <div id="listOfUserToAssign" class="btn-toolbar"> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-sm btn-success" href="<?php echo url_for2('delivery_list', $url_tab) ?>" >
                    <i class="fa fa-check"> Confirm</i>
                </a>
                 <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true" id="closeDeliverySearchBoxForSteps">
                     Close
                 </a>
            </div>
        </div>
    </div>
</div> 
 
