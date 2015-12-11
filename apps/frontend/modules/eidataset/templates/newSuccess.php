<div id="corps" class="col-lg-12 col-md-12 marge-none">
    <?php include_partial('root', array(
        'urlParameters' => $urlParameters,
        'ei_scenario' => $ei_scenario,
        'ei_data_set_root_folder'=> $ei_data_set_root_folder, 
        'ei_data_set_children' => $ei_data_set_children,
        'is_edit_step_case' => false)) ?>

    <div  class="col-lg-9 col-md-9"> 

        <div id="ei_data_set_content">
              
                    <?php include_partial('form', array(
                                'urlParameters' => $urlParameters,
                                'ei_scenario' => $ei_scenario,
                                'ei_data_set_root_folder'=> $ei_data_set_root_folder, 
                                'ei_data_set_children' => $ei_data_set_children,
                                'form' => $form,
                                'is_edit_step_case' => false)) ?>
               
        </div>
    </div>
</div>