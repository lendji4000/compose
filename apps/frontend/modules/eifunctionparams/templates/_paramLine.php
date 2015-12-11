<?php if(isset($ei_function_has_param)) : ?>
<?php 
    $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         "function_id" => $ei_function_has_param['function_id'],
         "function_ref" => $ei_function_has_param['function_ref']);?> 
<tr class="<?php echo "param_line_".$ei_function_has_param['param_id'] ?>">
    <td> <?php echo $ei_function_has_param['name'] ?></td>
    <td> <?php echo $ei_function_has_param['description'] ?></td>
    <?php if(isset($ei_function_has_param['param_type']) && $ei_function_has_param['param_type']=="IN"): ?>
    <td> <?php echo $ei_function_has_param['default_value'] ?></td>
    <?php endif; ?>
    <td>
        <?php   $urlEdit=$url_tab; $urlEdit['param_id']=$ei_function_has_param['param_id']; $urlEdit['action']="edit"; ?>
        <a class="btn btn-sm btn-success editFunctionParam" itemref="<?php echo url_for2("detailsParamActions",$urlEdit) ?>" data-toggle="modal" href="#functionParamModal">
                <?php echo ei_icon('ei_edit') ?>
        </a>
    </td>
    <td>
          
        <?php   $urlDelete=$url_tab; $urlDelete['param_id']=$ei_function_has_param['param_id']; $urlDelete['action']="delete"; ?>
        <i class="saveLoader fa fa-spinner fa-spin " style="display:none" >
        <a class="btn btn-sm btn-danger deleteFunctionParam" itemref="<?php echo url_for2("detailsParamActions",$urlDelete) ?>">
            </i> <?php echo ei_icon('ei_delete') ?>
        </a>
    </td>
</tr>
<?php endif; ?>


