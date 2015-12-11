<!--Liste des itérations pour la search Box (surtout utilisée dans le cadre de la recherche des itérations pour les statistiques d'une livraison)-->
<tbody>
 <?php if (isset($ei_iterations) && count($ei_iterations)>0):  ?>
     <?php
     $url_params = array(
         'project_id' => $project_id,
         'project_ref' => $project_ref,
         'profile_name' => $profile_name,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref);
     ?>   
<?php foreach ($ei_iterations as $ei_iteration):
    $lineForSearchBoxParams=$url_params;
    $lineForSearchBoxParams['ei_iteration']=$ei_iteration;
    $lineForSearchBoxParams['display_check_box']=(isset($display_check_box) && !$display_check_box)?false:true;
    
    include_partial("eiiteration/lineForSearchBox",$lineForSearchBoxParams); 
endforeach; ?>
 <?php endif; ?>
</tbody>