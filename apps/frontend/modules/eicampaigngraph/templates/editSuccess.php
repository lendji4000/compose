<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?> 
<?php $uri_form=$url_tab;
        $uri_form['form']=$form;
        $uri_form['root_folder']=$root_folder;
        $uri_form['ei_nodes']=$ei_nodes;   ?>
<div class="row">  <?php include_partial('form',$uri_form) ?>   </div>


  
