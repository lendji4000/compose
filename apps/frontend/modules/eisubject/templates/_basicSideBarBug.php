<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  
<li> 
    <a href="<?php echo url_for2('delivery_list', $url_tab) ?>#"  id="deliveryAdvancedSearch" >  
        <?php echo ei_icon('ei_delivery') ?>
        <span class="text">  <small>Delivery List</small></span>
    </a> 
</li>
<li> 
    <a href="<?php echo url_for2('delivery_new', $url_tab) ?>#" id="createDelivery" >
         <?php echo ei_icon('ei_add' ) ?>
        <span class="text">   <small>Add delivery </small></span>
    </a> 
</li> 
<li> 
    <a href="<?php echo url_for2('subjects_list', $url_tab) ?>#" id="bugAdvancedSearch"> 
        <?php echo ei_icon('ei_subject') ?>
        <span class="text">  <small>Interventions List</small>  </span>
    </a>
</li>
<li> 
    <a href="<?php echo url_for2('subject_new', $url_tab) ?>#" id="createBug">
        <?php echo ei_icon('ei_add' ) ?> 
        <span class="text">   <small>Add intervention</small> </span>
    </a>
</li> 