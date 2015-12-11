<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref ); 
$menu=$url_tab; 
$menu['ei_project']=$ei_project;
$menu['showFunctionContent']=false;
$menu['is_function_context']=true; 
?>   
        <div id="subjectContent"> 
            <div id="subjectFunctions" class="row"> 
                <div class="col-lg-4 col-md-4"> 
                    <?php include_partial('global/menu', $menu); ?>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="row" id="subjectFunctionsList">
                        <?php if(isset($ei_subject_functions) && count($ei_subject_functions)>0): ?>
                        <?php foreach($ei_subject_functions as $ei_subject_function):
                            $subjectFunctionLine=$url_tab; 
                            //$subjectFunctionLine['ei_project']=$ei_project;
                            $subjectFunctionLine['item']=$ei_subject_function;
                            //$subjectFunctionLine['ei_subject']=$ei_subject;
                            //$subjectFunctionLine['ei_subject_functions_as_array']=$ei_subject_functions_as_array->getRawValue();
                            include_partial('subjectfunction/subjectFunctionLine',$subjectFunctionLine) ?> 
                        <?php  endforeach; ?>
                        <?php  else: ?>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div> 
        </div> 

<?php  //var_dump($ei_subject_functions_as_array->getRawValue()); ?>