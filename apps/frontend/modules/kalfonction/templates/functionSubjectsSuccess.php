<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2>  
                    <?php echo ei_icon('ei_subject') ?> 
                    Function bugs (<?php echo (isset($ei_function_subjects) &&(count($ei_function_subjects)>0)?count($ei_function_subjects):0) ?>)
                </h2>
                <div class="panel-actions"> 
                </div>
            </div> 
            <div class="panel-body table-responsive" id="functionBugsList"> 
                <?php 
              $eisubject_list=$url_tab; 
              $eisubject_list['ei_subjects']=$ei_function_subjects;
              $eisubject_list['module_context']='EiSubjectFunctions';
              $eisubject_list['paginateList']=true;
              include_partial('eisubject/list',$eisubject_list) ?> 
            </div>        
        </div>    