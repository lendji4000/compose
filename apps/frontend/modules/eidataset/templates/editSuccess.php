<!-- Contenu d'une campagne (bloc principal) lors de l'édition du contenu d'une campagne -->
<?php $url_tab=array(
    'project_id' => $project_id,
    'project_ref' =>$project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
)?>
<?php //include_partial('form', array('form' => $form, 'urlParameters' => $urlParameters)) ?>

<div id="corps" class="col-lg-12 col-md-12 marge-none">
    <?php
    include_partial('root', array(
        'urlParameters' => $urlParameters,
        'ei_scenario' => $ei_scenario,
        'ei_data_set_root_folder' => $ei_data_set_root_folder,
        'ei_data_set_children' => $ei_data_set_children))
    ?>


    <div  class="col-lg-9 col-md-9" id="ei_data_set_content"> 
                <?php
                include_partial('form', array(
                    'ei_scenario' => $ei_scenario,
                    'form' => $form,
                    'urlParameters' => $urlParameters));
                ?>
            </div>      
        </div>  
    </div>

</div>