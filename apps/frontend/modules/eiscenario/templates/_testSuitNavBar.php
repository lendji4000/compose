<?php if (isset($ei_scenario) && isset($ei_project) && isset($ei_profile)): $node=$ei_scenario->getNode();  ?> 
    <a class="navbar-brand navbar-brand-width rename_node" href="#" title="Click to rename"> <?php echo $ei_scenario; ?></a>
    <input type="hidden" name="node_id" value="<?php echo $node->getId(); ?>" class="node_id" />
    <input type="hidden" name="obj_id" value="<?php echo $node->getObjId(); ?>" class="obj_id" />
    <input type="hidden" name="node_type" value="<?php echo $node->getType(); ?>" class="node_type" />
<div class="navbar-collapse collapse">

    <ul class="nav marge-none">
        <li class="divider-vertical"> </li>
        <li class="dropdown">

            <button id="scenarios_menu" class="btn dropdown-toggle" data-toggle="dropdown" role="button" data-target="#"> 
                <img src="/images/boutons/engrenage_small.png" alt="" class="pull-left" title="Scenarios menu"/>&nbsp;Test Suites <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">  
                    <li class="save_test_suit" ><a href="#" alt="Save"><img src="/images/boutons/save_scenery.png" alt="Save" /> Save </a></li>
                    <li class="create_scenario_clone">
                        <a href="#" alt="Save as" title="Copy the scenario"> <img src="/images/boutons/save_scenery.png" alt="Save as"/> Save as </a>
                    </li>

                    <li> 
                        <a href="<?php
                echo url_for2('projet_eiscenario', array(
                    'project_id' => $ei_project->getProjectId(),
                    'project_ref' => $ei_project->getRefId(),
                    'action' => 'delete',
                    'profile_id' => $ei_profile->getProfileId(),
                    'profile_ref' => $ei_profile->getProfileRef(),
                    'profile_name' => EiProfil::slugifyProfileName($ei_profile->getName()),
                    'id' => $ei_scenario->getId()), array(
                    'confirm' => "Sure about deleting test suite? this involve deleting all versions and functions "));
                    ?>" alt="Delete" title="Delete the scenario"> 
                            <img src="/images/boutons/delete_test_suite.png" alt="Delete" /> Delete</a>

                    </li> 

                <?php if (isset($ei_version) && $ei_version != null): ?>
                <?php else : ?>    
                  <?php  $profil_scenario=Doctrine_Core::getTable("EiProfilScenario")
                            ->findOneByEiScenarioIdAndProfileIdAndProfileRef(
                                    $ei_scenario->getId(),$ei_profile->getProfileId(),$ei_profile->getProfileRef());
                        $ei_version=Doctrine_Core::getTable("EiVersion")
                                ->findOneById($profil_scenario->getEiVersionId()); ?>
                    <?php endif; ?>

                    <li class="dropdown-submenu"> 
                        <a href="#" alt="Get Notice" title="Consult the notice of scenario by language">
                            <img src="/images/boutons/apercu.png"  alt="" /> View notice </a>
                        <?php $langs = $ei_project->getProjectLangs();
                        if ($langs->getFirst()): ?>
                            <ul class="dropdown-menu">
        <?php foreach ($langs as $lang): ?>

                                    <li>
                                        <a target ='_blank' href="<?php
            echo url_for1('@show_notice?project_id=' . $ei_project->getProjectId() . '&project_ref=' . $ei_project->getRefId() .
                    '&ei_scenario_id=' . $ei_scenario->getId() . '&profile_id=' . $ei_profile->getProfileId() . '&profile_ref=' . $ei_profile->getProfileRef().
                    '&id_version=' . $ei_version->getId() . '&lang=' . $lang->getLang())
            ?>" > 
                                            <img src="/images/boutons/apercu.png" alt="View notice" title="Consult the notice of scenario"/>
                                    <?php echo $lang ?>
                                        </a>
                                    </li> 
        <?php endforeach; ?> 
                            </ul>
    <?php endif; ?>
                    </li>




                    <li class=" downLoad_JDT ">

                        <a href="
                        <?php
                        echo url_for2('projet_eiscenario_downloadJDT', array('id_version' => $ei_version->getId(),
                            'ei_scenario_id' => $ei_scenario->getId(),
                            'filename' => $ei_scenario->getNomScenario() . '_' . $ei_version->getLibelle() . '_JDT',
                            'sf_format' => 'xml'));
                        ?>" alt="Download test suite XML file " title="Delete an exemple of XML to use for test suites."> 
                            <img src="/images/boutons/save_scenery.png" alt="" class="alignment_img" /> Download testsuite XML file </a>
                    </li> 
            </ul>
        </li>
    </ul>

<?php if (isset($ei_version) && $ei_version != null): ?>
        <ul class="pull-right">
            <li> 
                <div class="btn-group">
                    <input type="hidden" class="id_version_courante" name="id_version_courante" value="<?php echo $ei_version->getId() ?>" />  
                    <button class="create_version_clone btn" title="Copy version"> <img src="/images/boutons/duplicate_version.png" /></button>
                    <button class="add_version btn" title="New version"> <img src="/images/boutons/new_version.png" alt="" /></button>
                    <button class="btn" title="Insert block" id="ajouter_sous_version"><i class="icon-black icon-inbox" ></i></button>
                    <button class="btn save_test_suit" title="Save"> <i class="icon-black icon-ok" ></i> </button>
                </div>
            </li>
        </ul>
<?php endif; ?>
</div>
<?php endif; ?>