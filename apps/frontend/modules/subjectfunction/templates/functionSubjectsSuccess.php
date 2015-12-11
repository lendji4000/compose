<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<div class="row">
    <div class="col-lg-3 col-md-3">   
        <?php $menu = $url_tab; $menu['showFunctionContent']=true; 
            $menu['is_function_context']=false;
            $menu['showFunctionContent']=true;
            $menu['ei_project']=$ei_project;
            include_partial('global/menu', $menu); ?>
    </div>
    <div class="col-lg-9 col-md-9" id="administrateFunctions"> 
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2>  
                    <?php echo ei_icon('ei_subject') ?> Function bugs
                </h2>
                <div class="panel-actions"> 
                </div>
            </div> 
            <div class="panel-body" id="functionCampaignsList"> 
                <?php 
              $eisubject_list=$url_tab; 
              $eisubject_list['ei_subjects']=$ei_function_subjects;
              $eisubject_list['module_context']='EiSubjectFunctions';
              include_partial('eisubject/list',$eisubject_list) ?> 
            </div>
            <div class="panel-footer"> 
            </div>        
        </div>  
              
    </div>
</div>