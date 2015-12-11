<?php if(isset($ei_function) && isset($ei_project) && isset($ei_profile) && isset($ei_tree)): ?>
<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' =>  EiProfil::slugifyProfileName($profile_name),
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    "function_id" => $ei_function->getFunctionId(),
    "function_ref" => $ei_function->getFunctionRef() ); 
?>

<table class="table table-bordered table-striped dataTable"> 
    <tbody> 
        <tr>
            <th>Title</th>
            <td><?php echo $ei_tree->getName() ?></td>
        </tr>
        <tr>
            <th>Criticity</th>
            <td>
                <?php   $criticity_tab = $urlParams; $criticity_tab['action'] = 'changeCriticity'; ?> 
                <?php $criticity = (($ei_function->getCriticity() == null) ? "Blank" : $ei_function->getCriticity() ); ?>  
                <a class="<?php echo 'btn  criticity  criticity-' . $criticity ?> " id="changeFunctionCriticity"  title="Change function criticity ?"    
                                itemref="<?php echo url_for2('changeFunctionCriticity', $criticity_tab) ?>"> 
                                 <?php echo $criticity ?>
                </a>   
            </td>
        </tr>
        <tr>
            <th>Description</th>
            <td> <?php echo html_entity_decode($ei_function->getDescription(), ENT_QUOTES, "UTF-8") ?>  </td>
        </tr> 
    </tbody>
</table> 
<?php endif; ?>