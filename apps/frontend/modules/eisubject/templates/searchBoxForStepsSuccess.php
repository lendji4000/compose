<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ,
        'contextRequest' => (isset($contextRequest) ? $contextRequest : null),
        'is_ajax_request' => (isset($is_ajax_request) && $is_ajax_request)?true:false
     )?>
<div class="row">
    <?php
    $searchBox=$url_tab;
    $searchBox['subjectSearchForm'] =$subjectSearchForm;
    $searchBox['subjectsAuthors'] =$subjectsAuthors;
    $searchBox['subjectsTitles'] =$subjectsTitles;
    $searchBox['assignUsers'] =$assignUsers;
    $searchBox['is_ajax_request'] =true; ?>
 <?php  include_partial('searchBox', $searchBox) ?>  
        <hr/>
        <div id="subjectList" class="table-responsive "> 
            <table class="table table-bordered table-condensed table-striped dataTable">
                <thead>
                    <tr>
                        <?php //if(isset($module_context) && $module_context=='EiSubject'): ?>
                        <th >  Id </th>
                        <?php //endif; ?>
                        <th> Type </th>
                        <th> Title </th>
                        <th> Author </th>
                        <th> Assignments </th>
                        <th>  State </th>  
                        <th> Priority</th> 
                        <th>  Delivery  </th> 
                        <?php if ( !(isset($is_ajax_request) &&   $is_ajax_request)): ?>
                        <th>Updated At</th>
                        <th> Creation</th>
                        <th>Dev time</th>
                        <th>Test time</th>
                        <th> Coverage</th>
                        <th>  Ext ID  </th>  
                        <?php endif; ?> 
                        
                    </tr> 
                </thead>
                <tbody>
                    <?php if (count($ei_subjects) > 0): ?>
                        <?php foreach ($ei_subjects as $ei_subject): ?>
                    <?php $subjectLine=$url_tab;
                            $subjectLine['ei_subject'] =$ei_subject;
                            $subjectLine['is_ajax_request'] =true;  ?>
                     <?php  include_partial('eisubject/subjectLine',$subjectLine) ?> 
                        <?php endforeach; ?> 
                    <?php endif; ?> 
                </tbody> 
            </table> 
            <?php $pagerMenu=$url_tab;
                    $pagerMenu['current_page'] =$current_page;
                    $pagerMenu['nb_pages'] =$nb_pages;
                    $pagerMenu['nbEnr'] =$nbEnr;
                    $pagerMenu['offset'] =$offset;
                    $pagerMenu['max_subject_per_page'] =$max_subject_per_page;
                    $pagerMenu['searchSubjectCriteria'] =$searchSubjectCriteria;
                    $pagerMenu['is_ajax_request'] =true;  ?>
            <?php  include_partial('eisubject/pagerMenu',$pagerMenu); ?> 
 
    </div> 
</div> 


