<?php if(isset($kal_function)): ?>
<?php 
 $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'function_id' => $kal_function['t_obj_id'],
            'function_ref' => $kal_function['t_ref_obj']);  
?>
<tr>
        <?php $showFctProp = $url_tab; 
        $showFctProp['action'] = 'statistics'; ?>
    <td>
        <?php if($kal_function['t_path'] !=null):   ?>
            <?php $arrayPath=  json_decode(html_entity_decode($kal_function['t_path']) ,true);  ?>
             
            <?php if(count($arrayPath)>0):     ?>
            <ol class="breadcrumb">
                    <?php foreach ($arrayPath as $path): ?>
                <li>
                    <?php if($path['type']=="View"):?>
                        <i class="fa fa-folder ei-folder"></i>
                        <?php  echo  $path['name'] ?> 
                    <?php else: ?>
                    <?php $funcUri = $url_tab; $funcUri['function_id']=$path['obj_id'];
                        $funcUri['function_ref']=$path['ref_obj']; $funcUri['action']='statistics'; ?>
                    <a href="<?php echo url_for2('functionActions',$funcUri  ) ?>">
                        <?php echo ei_icon("ei_function")?> <?php echo  $path['name'] ?> 
                    </a>
                    <?php endif; ?>
                </li>
                    <?php endforeach; ?>
            </ol>         
            <?php endif; ?>
        <?php endif;?>
    </td>
        <td> 
            <?php if(isset($kal_function['iteration_id'])): $showFctProp['iteration_id']=$kal_function['iteration_id']; endif;?>
            <a href="<?php echo url_for2('functionActions', $showFctProp) ?>" target="_blank" >
            <?php echo ei_icon("ei_function") ?> <?php echo $kal_function['t_name']; ?>
            </a>
        </td> 
        <td> 
            <?php  $criticity_tab = $url_tab;   $criticity_tab['action'] = 'changeCriticity'; ?>
            <?php $criticity = (($kal_function['f_criticity'] == null) ? "Blank" : $kal_function['f_criticity'] ); ?>
            <a class="<?php echo 'btn btn-sm criticity  criticity-' . $criticity ?> " id="changeFunctionCriticity" title="Change function criticity?"      
                itemref="<?php echo url_for2('changeFunctionCriticity', $criticity_tab) ?>">
                    <?php echo $criticity ?>
            </a>  
        </td>  
    </tr>

<?php endif; ?>
