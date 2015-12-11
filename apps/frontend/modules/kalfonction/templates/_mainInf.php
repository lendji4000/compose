<?php if(isset($kal_function)): ?>
<?php 
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref); 
?>
<div class="row" id="administrateFunctions">  

    <?php
    $criticity_tab = $urlParams;
    $criticity_tab['function_id'] = $kal_function->getFunctionId();
    $criticity_tab['function_ref'] = $kal_function->getFunctionRef();
    $criticity_tab['action'] = 'changeCriticity';
    ?>     
    <div class="panel panel-default eiPanel  "  > 
        <div class="panel-heading">
            <h2><i class="fa fa-wrench"></i> Properties</h2>
            <div class="panel-actions"> 
                <?php
                $tab_properties = $urlParams;
                $tab_properties['action'] = 'edit';
                $tab_properties['function_id'] = $kal_function->getFunctionId();
                $tab_properties['function_ref'] = $kal_function->getFunctionRef();
                $url_properties = url_for2('showFunctionContent', $tab_properties);
                ?>
                <a  class=" btn-default " id="editKalFunction" href="#editKalFunctionModal" data-toggle="modal" itemref="<?php echo $url_properties ?>"> 
                    <?php echo ei_icon('ei_edit') ?> 
                </a>   
            </div>
        </div> 
        <div class="panel-body clearfix">    
               
        </div>  
        <div class="panel-body"> 
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading"> 
                        <h2><strong><i class="fa fa-info"></i>Main informations </strong>   </h2> 
                    </div> 
                    <div class="panel panel-body">
                       <table class="table table-bordered table-striped dataTable"> 
                        <tbody> 
                            <tr>
                                <th>Criticity</th>
                                <td> 
                                        <?php $criticity = (($kal_function->getCriticity() == null) ? "Blank" : $kal_function->getCriticity() ); ?>  
                                    <a class="<?php echo 'btn criticity  criticity-' . $criticity ?> " id="changeFunctionCriticity" title="Change function criticity?"     
                                       itemref="<?php echo url_for2('changeFunctionCriticity', $criticity_tab) ?>">
                                        <?php echo $criticity ?>
                                    </a> 
                                </td>
                            </tr> 
                            <tr>
                                <th>Created At</th>
                                <td><?php echo $kal_function->getCreatedAt(); //date('Y-m-d', strtotime($kal_function->getCreatedAt())); ?></td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td><?php echo $kal_function->getUpdatedAt() ; // date('Y-m-d', strtotime($kal_function->getUpdatedAt())); ?></td>
                            </tr> 
                        </tbody>
                    </table> 
                    </div> 
                </div> 
                <div id="functionContentDescription" class="panel panel-default eiPanel ">
                    <div class="panel-heading">
                        <h2>
                            <i class="fa fa-text-width "></i>
                            <span class="break"></span>  Description
                        </h2>
                        <div class="panel-actions">  
                        </div>
                    </div>
                    <div class="panel-body">  
                        <?php echo html_entity_decode($kal_function->getDescription(), ENT_QUOTES, "UTF-8") ?> 
                    </div> 
                </div>   
            </div>          
    </div>   
     
</div>  
<?php endif;  ?>