<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'ei_scenario_id' => $ei_scenario_id
     );
     //var_dump($ei_versions);
?>   
<?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_version_form' )) ?>
<div id="corps" class="col-lg-12 col-md-12 col-sm-12 marge-none"> 

    <div class="panel panel-default eiPanel">
        <div class="panel-heading" data-original-title>
            <h2 class="title_project"> 
                <?php echo ei_icon('ei_version') ?>
                <span class="break"></span> 
                Versions (<?php echo (isset($ei_versions) &&(count($ei_versions)>0)?count($ei_versions):0) ?>) 
            </h2>
            <div class="panel-actions">   
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped small-font bootstrap-datatable dataTable" id="EiPaginateList'">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Package</th>
                        <th>Linked intervention</th>
                        <th>Delivery </th>
                        <th>Description</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php foreach ($ei_versions as $i => $ei_version): ?> 
                        <tr>
                            <td>
                                <?php echo ei_icon('ei_version') ?>
                                <?php
                                $projet_edit_eiversion = $url_tab;
                                $projet_edit_eiversion['ei_version_id'] = $ei_version['v_id'];
                                $projet_edit_eiversion['action'] = 'edit';
                                ?>
                                <?php
                                echo link_to2($ei_version['v_libelle'], 'projet_edit_eiversion', $projet_edit_eiversion)
                                ?>
                            </td>
                            <td>
                                <?php if(isset($ei_version['sp_package_id']) && $ei_version['sp_package_id']!=null && isset($ei_version['sp_package_ref']) && $ei_version['sp_package_ref']!=null ): 
                                    echo ($ei_version['t_name']);
                                endif;?>
                            </td>
                            <td>
                                <?php if(isset($ei_version['subject_id'])): ?>
                                <?php $subject_show_uri=$url_tab;  unset($subject_show_uri['ei_scenario_id']); $subject_show_uri['subject_id']=$ei_version['subject_id']; ?>
                                <a  href="<?php echo url_for2('subject_show', $subject_show_uri)   ?>"  >  
                                    <?php echo ei_icon("ei_subject")." ".$ei_version['subject_id']." / " ?> <?php echo $ei_version['subject_name'];  ?>  </a> 
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(isset($ei_version['delivery_id'])): ?>
                                <?php $delivery_show_uri=$url_tab;  unset($delivery_show_uri['ei_scenario_id']); $delivery_show_uri['delivery_id']=$ei_version['delivery_id']; ?>
                                <a  href="<?php echo url_for2('getDeliverySubjects', $delivery_show_uri)   ?>"  >  
                                    <?php echo ei_icon("ei_delivery") ?> <?php echo $ei_version['delivery_name'];  ?>  </a> 
                                <?php endif; ?>
                            </td>
                            <td> <?php echo MyFunction::troncatedText($ei_version['v_description'], 100) ?>  </td>
                            <td><?php echo date('Y-m-d', strtotime($ei_version['v_created_at'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($ei_version['v_updated_at'])); ?></td>
                        </tr>      
                        <?php endforeach; ?>
                </tbody>  
            </table>            
        </div>
    </div> 	  

</div> 
