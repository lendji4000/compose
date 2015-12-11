<?php
$paramsForUrl = $paramsForUrl->getRawValue();
if ($form->getObject()->isNew()) {
    $paramsForUrl['action'] = 'create';
    $urlForm = url_for2('projet_eiscenario', $paramsForUrl);
} else {

    $paramsForUrl['action'] = 'update';
    $paramsForUrl['ei_scenario_id'] = $ei_scenario->getId();
    $paramsForUrl['ei_version_id'] = $ei_version->getId();
    $urlForm = url_for2('projet_edit_eiversion', $paramsForUrl);

    $paramsUrlUpdate = $paramsForUrl;
    $paramsUrlUpdate['action'] = "update";
    $routeUpdate = url_for2("projet_edit_eiversion", $paramsUrlUpdate);

    $paramsForDelete = $paramsForUrl;
    $paramsForDelete['action'] = "delete";

    $paramsForCreate = $paramsForUrl;
    $paramsForCreate['action'] = "new";

    $paramsForClone = $paramsForUrl;
    unset($paramsForClone['action']);

    unset($paramsForCreate['id'], $paramsForCreate['id_version'], $paramsForClone['id_version']);
    unset($paramsForUrl['id_version']);
}
?>
<!-- Barre de navigation supérieure d'un scénario--> 
 

<div id="corps" class="tabbable row marge-none">
     
    <?php include_partial('global/alertBox' , array(  'flash_string' => 'alert_version_form' )) ?> 

    <?php 
    if (!isset($profile_id) || !isset($profile_ref)):
         $profile_id = 0;$profile_ref = 0;
    endif;
       
    if (!isset($profile_name)):
        $profile_name = "profil";
    endif; ?>



    <ul class="nav "> 
        <li class="col-lg-12 col-md-12 col-sm-12 col-xs-12 active" id="toolTips"> 
            <?php if ($sf_user->hasFlash('msg_success')): ?>                  
                <?php echo $sf_user->getFlash('msg_success', ESC_RAW) ?>
            <?php endif; ?>
        </li>
    </ul>
    <div id="eiversion_content" class="row" >  
        <?php //On réccupère eventuellement le package lié à la version si existant  
        include_component('eiversion','sideBarHeaderObject',array('ei_scenario_package'=> (isset($ei_scenario_package) && $ei_scenario_package!=null)?$ei_scenario_package:null)); ?>
        <div class="tab-content form">
            <div class="tab-pane" id="informations">
                <input type="hidden" name="ei_scenario_id" value="<?php echo $ei_scenario->getId() ?>" class="ei_scenario_id" />
                 
                <div class="panel panel-default eiPanel">
                        <div class="panel-heading">
                            <h2><?php echo ei_icon('ei_profile') ?> Environments</h2>
                        </div>
                        <div class="panel-body">
                            <?php if (isset($ei_version) && $ei_version != null): //Si la version est spécifiée, on la passe au partiel  ?>
                                <?php include_partial('eiscenario/profilOfScenario', array(
                                    'ei_scenario' => $ei_scenario,
                                    'ei_version' => $ei_version,
                                    'ei_profiles' => $ei_profiles,
                                    'actifs_version_profiles' => $actifs_version_profiles)) ?> 
                            <?php endif; ?>
                        </div>

                    </div>  
<!--               Edition du nom et description de la version  -->
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2><?php echo ei_icon('ei_version') ?> Version's information</h2>
                        <div class="panel-actions"> 
                            <a title="Delete current version ?" id="delete_version_modal_opener"
                               href="#delete_eiversion_modal" class ="  btn-danger"> 
                                <?php echo ei_icon('ei_delete') ?>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $routeUpdate ?>" id="donnees_version" class=""   
                              method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?> >
                                  <?php if (!$form->getObject()->isNew()): ?>
                                <input type="hidden" name="sf_method" value="put" />
                            <?php endif; ?>
                                <div class='row hiddenFields'>
                                    <?php echo $form->renderHiddenFields(); ?> 
                                    <?php echo $form->renderGlobalErrors() ?>
                                </div>
                            
                            <div class="form-group"> 
                                <label class="control-label col-lg-3 col-md-3  col-sm-3 col-xs-3" for="text-input">
                                    <?php echo $form['libelle']->renderLabel() ?>
                                </label>
                                <div class="col-lg-9 col-md-9  col-sm-9 col-xs-9">
                                    <?php echo $form['libelle']->renderError() ?>
                                    <?php echo $form['libelle']->render() ?> 
                                    <span class="help-block"> </span>
                                </div>
                            </div>   
                            <div class="form-group"> 
                                <label class=" col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label" for="textarea-input">
                                    <?php echo $form['description']->renderLabel() ?>
                                </label>
                                <div class="col-lg-9 col-md-9  col-sm-9 col-xs-9">
                                    <?php echo $form['description']->renderError() ?>
                                    <?php echo $form['description']->render() ?>
                                </div>
                            </div>   
                        </form>
                    </div>

                </div>  
    
            </div>
            <div class="tab-pane active" id="block">
                <div class="row"> 
                        <?php 
                        //INCLUSION DU MENU DE GAUCHE
//                        include_component('eiscenario', 'navMenu', array('project_id' => $project_id,
//                            'project_ref' => $project_ref,
//                            'ei_scenario' => $ei_scenario,
//                            'is_editable' => ((isset($ei_scenario_package) && $ei_scenario_package!=null && isset($defaultPackage) && $defaultPackage!=null && 
//                               $ei_scenario_package->getPackageId()==$defaultPackage['package_id'] && $ei_scenario_package->getPackageRef()==$defaultPackage['package_ref'])?true:false),
//                            'profile_name' => $profile_name,
//                            'profile_id' => $profile_id,
//                            'profile_ref' => $profile_ref,
//                            'ei_version_id' => $ei_version_id,
//                            'is_version' => true, 
//                            'firefox_path' => $firefoxPath,
//                            'jddScenarioToPlay' => $jddScenarioToPlay,
//                            'ei_current_block' => isset($ei_version_structure) ? $ei_version_structure:null
//                        ));
                        include_component('eiversion', 'navMenu', array('project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'is_editable' => ((isset($ei_scenario_package) && $ei_scenario_package!=null && isset($defaultPackage) && $defaultPackage!=null && 
                               $ei_scenario_package['package_id']==$defaultPackage['package_id'] && $ei_scenario_package['package_ref']==$defaultPackage['package_ref'])?true:false),
                            'profile_name' => $profile_name,
                            'profile_id' => $profile_id,
                            'profile_ref' => $profile_ref,
                            'ei_version_id' => $ei_version_id,
                            'is_version' => true, 
                            'firefox_path' => $firefoxPath,
                            'jddScenarioToPlay' => $jddScenarioToPlay,
                            'ei_current_block' => isset($ei_version_structure) ? $ei_version_structure:null
                        ));
                        ?> 
                    <?php $collec = $fonctionsForms->getRawValue(); ?>
                    <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="panel panel-default eiPanel" >
                            <div class="panel-heading">
                                <h2>
                                     <?php echo ei_icon('ei_bloc','lg','','Block content') ?>
                                    <strong class="panelHeaderTitle">Block content</strong>
                                </h2>   
                                <ul  class="nav nav-tabs">
                                    <li >
                                        <a href="#blockPropertiesParameters" data-toggle="tab" tilte="Block's parameters" id="openBlockPropertiesParameters">
                                            <?php echo ei_icon('ei_bloc_parameter') ?> Parameters
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                            <div class="panel-body" id="scrolled_box_version">
                                <?php include_partial('formContent', array(
                                    'form' => $form,
                                    'children' => $children,
                                    'fonctionsForms' => $collec,
                                    'fonctions' => $fonctions,
                                    'urlForm' => $urlForm,
                                    'paramsForUrl' => $paramsForUrl,
                                    'ei_version_structure_id' => $ei_version_structure->getId(),
                                    'ei_version_id' => $ei_version->getId(),
                                    'ei_block_parameters' => $ei_block_parameters,
                                    'is_editable' => ((isset($ei_scenario_package) && $ei_scenario_package!=null && isset($defaultPackage) && $defaultPackage!=null && 
                                   $ei_scenario_package['package_id']==$defaultPackage['package_id'] && $ei_scenario_package['package_ref']==$defaultPackage['package_ref'])?true:false),
                                    ))  ?>
                            </div>

                        </div>     
                    
                    </div>
                    <!--Partie permettant de rendre l'arbre des fonctions à l'utilisateur-->
                    <?php  include_partial('versionLeftSide',array( 
                            'ei_project' => $ei_project,
                            'paramsForUrl' => $paramsForUrl,
                            'defaultPackage' => (isset($defaultPackage)?$defaultPackage:null), //Package par défaut 
                            'ei_scenario_package' => (isset($ei_scenario_package)?$ei_scenario_package:null), //relation entre la version du scénario et une intervention
                            'form' =>$form
                    )) ;  ?> 
                </div>
            </div>

            <div class="tab-pane" id="blockPropertiesParameters">
                <?php
                include_component("block", "showParams", array(
                    "form" => $formEditBlockParams,
                    'project_id' => $project_id,
                    'project_ref' => $project_ref,
                    'profile_name' => $profile_name,
                    'profile_id' => $profile_id,
                    'profile_ref' => $profile_ref,
                    'ei_version_structure_id' => $ei_version_structure->getId(),
                    'ei_version_id' => $ei_version->getId(),
                    'ei_block_parameters' => $ei_block_parameters,
                    'ei_scenario_id' => $ei_scenario->getId()
                ));
                ?>
            </div>
        </div>


    </div>

</div>

 
<!--Fenêtre modale de suppression d'une version de scénario  -->
<div id="delete_eiversion_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete_eiversion_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <!--<h3>Delete <?php //echo $ei_scenario->getNomScenario(); ?></h3>--> 
            </div>
            <div class="modal-body "><!--modal-body-visible-overflow-->
                <?php echo "You are about to delete version <strong>".$ei_version->getLibelle()."</strong>.<br/> Do you really want to delete " . $ei_version->getLibelle()." ?"; ?>
            </div>
            <div class="modal-footer">
                <a href="#!" class="btn btn-default" data-dismiss="modal">
                   <i class="fa fa-times-circle-o"></i>  Cancel
                </a>   
                <a  id="delete_version" href="<?php echo url_for2("projet_edit_eiversion", $paramsForDelete); ?>" class="btn btn-danger"> 
                    <?php echo ei_icon('ei_delete') ?> Delete
                </a>
            </div>
        </div>
    </div>
</div>
<div id="create_version_clone_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="create_version_clone_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><i class="fa fa-copy"></i> Copy scénario version</h3> 
            </div>
            <div class="modal-body ">
               <input type='text' name='new_name_version' id='new_name_version'  />
            </div>
            <div class="modal-footer">
                <a href="#!" class="btn btn-default" data-dismiss="modal">
                   <i class="fa fa-times-circle-o"></i>  Cancel
                </a>   
                <a  id="create_version_clone" class="btn btn-success" href="<?php echo url_for2('projet_copy_eiversion',array(
                                    "package_id" => (isset($defaultPackage) && $defaultPackage!=null)?$defaultPackage['package_id']:null,
                                    "package_ref" =>(isset($defaultPackage) && $defaultPackage!=null)?$defaultPackage['package_ref']:null,
                                   "project_id" => $project_id,
                                    "project_ref" => $project_ref,
                                    "profile_name" => $profile_name,
                                    "profile_id" => $profile_id,
                                    "profile_ref" => $profile_ref,
                                    "ei_scenario_id" => $ei_scenario_id,
                                    "ei_version_id" => $ei_version->getId(),
                                    "default_notice_lang" => "en"
                               ))."#" ?>" > 
                    <i class="fa fa-copy"></i> Copy
                </a>
            </div>
        </div>
    </div>
</div>