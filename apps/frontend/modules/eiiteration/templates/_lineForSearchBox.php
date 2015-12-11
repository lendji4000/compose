<!--Ligne d'itération pour la search Box (surtout utilisée dans le cadre de la recherche des itérations pour les statistiques d'une livraison)-->
<tr>
 <?php if (isset($ei_iteration)): ?>
     <?php
     $url_params = array(
         'project_id' => $project_id,
         'project_ref' => $project_ref,
         'profile_name' => $profile_name,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref);
     ?>  
    <?php if(isset($display_check_box) && $display_check_box): ?>
    <td>
        <!--Icone permettant de choisir une itération pour ramener les stats dans une livraison-->
        <input class="selectItForDelStats" type="checkbox"/>
        <input type="hidden" class="iteration_id" name="iteration_id" value="<?php echo $ei_iteration['id'] ?>" />
    </td> 
    <?php endif;  ?>
    <td>
        <?php echo ei_icon("ei_iteration")?> <?php echo $ei_iteration['id'] ?>
    </td>
    <td>
        <?php if(isset($ei_iteration['ai_iteration_id']) && $ei_iteration['ai_iteration_id']!=null): ?>
        <span class="label ei-label label-success activeIteration" title="Active iteration"   >
        <i class="fa fa-check"></i>   Active iteration  </span>
        <?php endif; ?>
    </td>
    <td>
            <?php $getDeliverySubjects = $url_params;
            $getDeliverySubjects['delivery_id'] = $ei_iteration['delivery_id'] ?>
        <a class="accessDeliveryBugs" target="_blank" title="Delivery interventions"   href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>">
            <?php echo ei_icon('ei_subject') ?> <span class="text">    <?php echo $ei_iteration['delivery_name'] ?> </span>   
        </a>
    </td>
    <td>
        <?php echo ei_icon("ei_profile")?> <?php echo $ei_iteration['profile_name'] ?>
    </td>
    <td>
        <?php echo ei_icon("ei_user")?> <?php echo $ei_iteration['username'] ?>
    </td>
    
    <td> <?php echo $ei_iteration['description'] ?></td>
    <td> <?php echo $ei_iteration['created_at'] ?></td>
 <?php endif; ?>
</tr>