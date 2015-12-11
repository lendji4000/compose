<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<?php $uri_form=$url_tab; ?>
<?php $uri_form['form'] =$form; ?>
<?php $uri_form['parent_id'] =$parent_id; ?>
<?php $uri_form['campaign_id'] =$campaign_id; ?>
<?php $uri_form['root_folder'] =$root_folder; ?>
<?php $uri_form['ei_nodes'] =$ei_nodes; ?>
<div class="row">   <?php include_partial('form',$uri_form) ?>  </div> 