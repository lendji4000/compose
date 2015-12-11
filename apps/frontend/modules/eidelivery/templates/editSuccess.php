<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<?php 
$partial_params=$url_tab;
$partial_params['form']=$form; 
?>


<div class="row" id="subjectContentEdit"> 
    
    <?php include_partial('form', $partial_params) ?>  
</div>    
        