 <?php if(isset($ei_scenario)) :?> 
<!-- Chemin du scénario -->
            <ul class="breadcrumb">
                <?php echo html_entity_decode($chemin); ?>
                <li class="pull-right">
                    <a href="
                    <?php
                    echo url_for2("projet_eiscenario_action", array(
                        'project_id' => $sf_request->getParameter('project_id'),
                        'project_ref' => $sf_request->getParameter('project_ref'),
                        'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                        'profile_id' => $sf_request->getParameter('profile_id'),
                        'profile_ref' => $sf_request->getParameter('profile_ref'),
                        'ei_scenario_id' => $ei_scenario->getId(),
                        'action' => 'createClone'
                    ));
                    ?>" id="create_scenario_clone"> &nbsp;&nbsp;<i class="icon-tags"></i> Copy </a>
                </li>
                <li class="divider-vertical"></li> 
                <li class="pull-right">
                    <a href="#delete_eiscenario_modal" data-toggle="modal">
                        &nbsp;&nbsp;<i class="icon-trash"></i>  Delete
                    </a>
                </li>
            </ul>  
 
 
 
    <div id="delete_eiscenario_modal" class="modal hide" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Delete <?php echo $ei_scenario->getNomScenario(); ?></h3>
        </div>
        <div class="modal-body modal-body-visible-overflow">
            <?php echo "You are about to delete scenario <strong>".$ei_scenario->getNomScenario()."</strong>. All its versions will be deleted as well as data sets.<br/> Do you really want to delete " . $ei_scenario->getNomScenario()." ?"; ?>
        </div>
        <div class="modal-footer">
            <a href="#!" class="btn" data-dismiss="modal">Cancel</a>   
            <a  id="delete_scenario" href="<?php echo url_for2("projet_eiscenario_action", array(
                                'project_id' => $sf_request->getParameter('project_id'),
                                'project_ref' => $sf_request->getParameter('project_ref'),
                                'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                                'profile_id' => $sf_request->getParameter('profile_id'),
                                'profile_ref' => $sf_request->getParameter('profile_ref'),
                                'ei_scenario_id' => $ei_scenario->getId(),
                                'action' => 'delete'
                        ));?>" class="btn btn-danger"> Delete </a>
        </div>
    </div>
<?php endif; ?>