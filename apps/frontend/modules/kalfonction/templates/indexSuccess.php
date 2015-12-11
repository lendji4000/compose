<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<div class="row">
    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">  
        <?php $menu=$urlParams; 
                        $menu['ei_project']=$ei_project; 
                        $menu['showFunctionContent']=true;   
                        include_partial('global/menu', $menu); ?>  
    </div>
    <div class="col-lg-8 col-md-7 col-sm-6 col-xs-6" id="administrateFunctions">
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <h2 style="text-align:center">Choose a function on left side</h2>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="row">
            <h2 style="text-align:center">
                <i class="fa fa-arrow-circle-left fa-5x"></i>
            </h2> 
        </div>
    </div>
</div>