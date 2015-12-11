<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<div class="panel panel-default  eiPanel">
    <div class="panel-heading">
        <h2>  
            <?php echo ei_icon('ei_add') ?> New Delivery campaign
        </h2>
        <div class="panel-actions"> 
        </div>
    </div> 
    <div class="panel-body"> 
        <?php 
                $delivery_form=$url_tab; 
                $delivery_form['form']=$form;
                $delivery_form['delivery_id']=$delivery_id;
                ?>
        <?php include_partial('form', $delivery_form) ?>  
        
    </div>        
</div>  