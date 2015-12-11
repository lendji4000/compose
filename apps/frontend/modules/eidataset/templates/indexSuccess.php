<!-- Contenu d'une campagne (bloc principal) lors de l'édition du contenu d'une campagne -->
<?php $url_tab=array(
    'project_id' => $project_id,
    'project_ref' =>$project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
)?>
<div id="corps" class="marge-none">
    <?php include_partial('root', array(
        'urlParameters' => $urlParameters,
        'ei_scenario' => $ei_scenario,
        'ei_data_set_root_folder'=> $ei_data_set_root_folder, 
        'ei_data_set_children' => $ei_data_set_children,
        'is_edit_step_case' => false
    ))
    ?>

    <div  class="col-lg-9 col-md-9 col-sm-9 col-xs-9"> 

        <div id="ei_data_set_content">
            
            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2>  </h2>
                    <div class="panel-actions"> 
                    <?php   $projet_new_version = $urlParameters->getRawValue();
                            $projet_new_version['ei_scenario_id'] = $ei_scenario->getId();
                            $projet_new_version['action'] = 'editVersionWithoutId';
                            unset($projet_new_version['ei_data_set_id'] );
                            ?>
                            <a href="<?php echo url_for2('projet_new_eiversion', $projet_new_version); ?>" class="close">
                                <i class="fa fa-times"></i>
                            </a>
                    </div>
                </div> 
                <div class="panel-body" >  
                </div>  
            </div>   
        </div>
    </div>
</div>
 