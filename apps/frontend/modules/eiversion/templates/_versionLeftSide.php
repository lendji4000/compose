<?php  $url_tab=$paramsForUrl->getRawValue();   
$ei_version=$form->getObject();?> 
<div id="menu" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 margin-none">
    <!--On vérifie dans un premier temps que le package par défaut est définit et correspond au package--> 
    <?php if (isset($defaultPackage) && $defaultPackage != null && isset($ei_scenario_package) && $ei_scenario_package != null && $defaultPackage['sp_ei_version_id']!=null
            && ($defaultPackage['sp_ei_version_id']==$ei_scenario_package['ei_version_id']) && 
    ($ei_scenario_package['package_id'] ==$defaultPackage['package_id'] && $ei_scenario_package['package_ref'] == $defaultPackage['package_ref'])):   ?>
    <?php
    $menu = $url_tab;
    $menu['ei_project'] = $ei_project;
    $menu['ei_version'] = $form->getObject();
    $menu['showFunctionContent'] = ((isset($showFunctionContent) && $showFunctionContent) ? true : false);
    $menu['is_function_context'] = ((isset($is_function_context) && $is_function_context) ? true : false);
    $menu['is_step_context'] = ((isset($is_step_context) && $is_step_context) ? true : false);
    include_partial('global/menu', $menu);  ?>
<?php else: ?>
    <div class="panel panel-default eiPanel">
        <div class="panel-heading" data-original-title>
            <h2 class="title_project"> 
                    <?php echo ei_icon('ei_edit') ?>
                <span class="break"></span> 
                Edit structure
            </h2>
            <div class="panel-actions">   
            </div>
        </div>
        <div class="panel-body table-responsive">
            <ul  class="nav nav-pills"> 
                <!--Si le package par défaut n'est pas définit, on le notifie et on ne va pas plus loin-->
    <?php if (isset($defaultPackage) && $defaultPackage != null): ?>
        <!--On affiche l'arbre uniquement si la version est associée à un package  et si le package de la version est la même que celle du package par défaut-->
        <?php if (isset($ei_scenario_package) && $ei_scenario_package != null): ?> 
                <li>
                    <?php $setPackageAsDefaultUri=$url_tab;  $setPackageAsDefaultUri['package_id']=$ei_scenario_package['package_id'];
                    $setPackageAsDefaultUri['package_ref']=$ei_scenario_package['package_ref'];
                    $setPackageAsDefaultUri['action']="setPackageAsDefault" ?>
                    <div>
                        <a class ="btn btn-default" title="Switch to this package and reload scenario version?" id="switchToDefaultPackageAndReload" 
                       itemref="<?php echo url_for2("setBugPackageAsDefault",$setPackageAsDefaultUri) ?>">
                       <?php echo ei_icon('ei_subject') ?></a>
                        <span>Load intervention <?php echo ei_icon('ei_subject') ?> <?php echo 'S ' . $ei_scenario_package['subject_id'];?> and refresh page</span>
                    </div>
                </li> 
            <?php if (!($ei_scenario_package['package_id']== $defaultPackage['package_id'] && $ei_scenario_package['package_ref']== $defaultPackage['package_ref'])): ?>
                <!--On duplique la version et l'associe au package par défaut  -->  
                <li>
                    <div>
                        <a class ="btn btn-default" title="Duplicate current version ?" id=""
                            data-toggle="modal" data-target="#create_version_clone_modal" >
                            <i class="fa fa-copy"></i> 
                        </a>
                        <span>Copy the <?php echo ei_icon('ei_version') ?> version of <?php echo ei_icon('ei_subject') ?> <?php echo 'S ' . $ei_scenario_package['subject_id'];?> into <?php echo ei_icon('ei_version') ?> version of <?php echo ei_icon('ei_subject') ?> <?php echo 'S ' . $defaultPackage['subject_id']; ?></span>
                    </div>
                </li> 

                <?php if (isset($defaultPackage['sp_ei_version_id']) && $defaultPackage['sp_ei_version_id'] != null):?>
 
                        <?php  
                        $edit_def_pack_version_uri = $url_tab;
                        $edit_def_pack_version_uri['action'] = 'edit';
                        unset($edit_def_pack_version_uri['default_notice_lang']);
                        $edit_def_pack_version_uri['ei_version_id'] = $defaultPackage['sp_ei_version_id']
                        ?> 
                        <li>
                            <div>
                                <a  href="<?php echo url_for2("projet_edit_eiversion", $edit_def_pack_version_uri) ?>" class ="btn btn-default">
                                    <i class="fa fa-toggle-right"></i> 
                                </a>
                                <span>Open <?php echo ei_icon('ei_subject') ?> <?php echo 'S ' . $defaultPackage['subject_id']; ?> <?php echo ei_icon('ei_version') ?> version</span>
                            </div>
                        </li>  
                <?php else : ?>
                    <li>
                        <div class="alert alert-warning " role="alert"> 
                            <strong>Warning!</strong> Default package has no version associated for this scenario...
                        </div> 
                    </li>

                <?php endif; ?> 
            <?php endif; ?>
            <?php else: //On lève une alerte pour signaler à l'utilisateur que la version n'est associé à aucun package   ?>
                    <li>
                        <div class="alert alert-warning " role="alert">
                            <strong>Warning!</strong> There is no intervention linked to this version. You can't use function's tree...
                        </div>
                    </li> 
            <?php
            $link_version_to_def_pack_uri = $url_tab;
            $link_version_to_def_pack_uri['action'] = 'linkVersionToDefaultPackage';
            unset($link_version_to_def_pack_uri['default_notice_lang']);
            $link_version_to_def_pack_uri['ei_version_id'] = $ei_version->getId()
            ?> 
            <?php if (isset($defaultPackage['sp_ei_version_id']) && $defaultPackage['sp_ei_version_id'] != null): ?>
                <!--Le package par défaut est lié à une version -->
                <li>
                    <div>
                        <span class='text-warning'>Default Intervention is associate to a version : </span> 
                         <a href="<?php echo url_for2("projet_edit_eiversion", $link_version_to_def_pack_uri) ?>" class ="btn btn-default" >
                             <i class="fa fa-link"></i> 
                         </a>
                        <span>Link and delete association?</span>
                    </div>
                </li> 
                    <?php
                    $edit_def_pack_version_uri = $url_tab;
                    $edit_def_pack_version_uri['action'] = 'edit';
                    unset($edit_def_pack_version_uri['default_notice_lang']);
                    $edit_def_pack_version_uri['ei_version_id'] = $defaultPackage['sp_ei_version_id']
                    ?>
                <li>
                    <div>
                        <a  href="<?php echo url_for2("projet_edit_eiversion", $edit_def_pack_version_uri) ?>" class ="btn btn-link">
                            <i class="fa fa-toggle-right"></i> 
                        </a>
                        <span>Load default intervention version </span>
                    </div>
                </li>  
            <?php else : ?>
                <!--Le package par défaut n'est associé à aucune version du scénario: La version courante n'est associé à aucun package également: on procède à l'association simple-->
                <li>    
                    <div>
                        <a href="<?php echo url_for2("projet_edit_eiversion", $link_version_to_def_pack_uri) ?>">
                            <i class="fa fa-link"></i> <span>Simply Link to current intervention</span>
                        </a>
                        
                    </div>
                </li>
            <?php endif; ?>

    <?php endif; ?>
<?php else: ?>
                <div class="alert alert-warning " role="alert"> 
                        <strong>Warning!</strong> There is no current intervention. Set one ... 
                    </div>
        
<?php endif; ?> 
            </ul>
        </div> 
    </div>
    <?php endif; ?>

</div>