<div class="navbar navbar-inverse  "> 
    <div class="navbar-inner">
        <ul class="nav">
            <li>
               <?php echo link_to1('<img src="/images/logos/picto_compose.png" alt="" class="alignment_img" />', "@recharger_projet"); ?> 
            </li>
            <li class="divider-vertical"></li>
            <li class="dropdown  active"> 
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Projects <b class="caret"></b></a>  
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><i></i>p1</a></li> 
                    <li><a href="#"><i></i>22</a></li>  
                </ul>
            </li>  
            <li class="divider-vertical"></li>
            <!-- Delivery-->
            <li class="dropdown active">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" >
                    Interventions<b class="caret"></b>
                </a> 
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                        <a href="#">Bugs</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Add</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Search</a></li>  
                        </ul>
                    </li>
                    <li class="divider"></li>
                    <li class="dropdown-submenu">
                        <a href="#">Delivery</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Add</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php  //echo url_for2('delivery_list')?>">Search</a></li> 
                        </ul>
                    </li> 
                </ul>
            </li> 
            <li class="divider-vertical"></li>
            <li class="active">
                <?php echo link_to2("Scenarios", "projet_eiscenario", array(
                   'project_id' => $sf_request->getParameter('project_id'),
                    'project_ref' => $sf_request->getParameter('project_ref'),
                    'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                    'profile_id' => $sf_request->getParameter('profile_id'),
                    'profile_ref' => $sf_request->getParameter('profile_ref'),
                    'action' => 'index'
                ));?>
            </li>
            <li class="divider-vertical"></li>
            <li class="dropdown  active pull-right"> 
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    User <b class="caret"></b></a>  
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><i></i>Log out</a></li> 
                    <li class="dropdown-submenu">
                        <a href="#">Default Environment</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Prof1</a></li> 
                            <li><a href="#">Prof2</a></li> 
                            <li><a href="#">...</a></li> 
                        </ul>
                    </li> 
                </ul>
            </li>
        </ul>
        
    </div> 
    
</div>
<!--comments-->
<?php if($menu): ?>

<?php if(isset($ei_scenario)) :?>
<ul class="nav"> 
        <li>
            <ul class="breadcrumb">
                <?php echo html_entity_decode($chemin); ?>
            </ul> 
        </li>
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
            ?>" id="create_scenario_clone"> <i class="icon-tags"></i> Copy </a>
        </li>
        <li class="divider"></li>
        <li class="pull-right"> <a href="#delete_eiscenario_modal" data-toggle="modal"><i class="icon-trash"></i>  Delete</a>
        </li>
 
</ul> 

<div class="navbar navbar-main">
    <div class="navbar-inner">
        <ul class="nav"> 
            <li class="dropdown  active"> 
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Versions <b class="caret"></b></a>  
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><i></i>V1</a></li> 
                    <li><a href="#"><i></i>V2</a></li> 
                    <li><a href="#"><i class="icon icon-plus-sign"></i>Add</a></li> 
                </ul>
            </li> 
                    
                    <li class="divider-vertical"></li>
                    <li class="dropdown  "> 
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Data sets   <b class="caret"></b></a>  
                        <ul class="dropdown-menu" role="menu"> 
                            <li>
                                <a href="
                                <?php echo url_for2("projet_eiscenario_action", array(
                                        'project_id' => $sf_request->getParameter('project_id'),
                                        'project_ref' => $sf_request->getParameter('project_ref'),
                                        'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                                        'profile_id' => $sf_request->getParameter('profile_id'),
                                        'profile_ref' => $sf_request->getParameter('profile_ref'),
                                        'ei_scenario_id' => $ei_scenario->getId(),
                                        'action' => 'edit'
                                    ));?>"> <i class="icon-wrench"></i> Properties</a>
                            </li>  
                            <li class="divider"></li>
                            <li>
                                <?php echo link_to2("<i class=\"icon-tasks\"></i> Open", "eidataset_index", array(
                                        'project_id' => $sf_request->getParameter('project_id'),
                                        'project_ref' => $sf_request->getParameter('project_ref'),
                                        'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                                        'profile_id' => $sf_request->getParameter('profile_id'),
                                        'profile_ref' => $sf_request->getParameter('profile_ref'),
                                        'ei_scenario_id' => $ei_scenario->getId()
                                ));?>
                            </li> 
                            
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                    <li>
                        <?php echo link_to2("<i class=\"icon-tasks\"></i> Reports", "ei_test_set_index", array(
                                'project_id' => $sf_request->getParameter('project_id'),
                                'project_ref' => $sf_request->getParameter('project_ref'),
                                'profile_name' => EiProfil::slugifyProfileName($sf_request->getParameter('profile_name')),
                                'profile_id' => $sf_request->getParameter('profile_id'),
                                'profile_ref' => $sf_request->getParameter('profile_ref'),
                                'ei_scenario_id' => $ei_scenario->getId()
                        ));?>
                    </li>
                    
            
            
            <?php endif; ?>
        </ul>
        <ul class="nav pull-right">
            <li class="divider-vertical"></li>
            <li class="pull-right col-lg-3 col-md-3 active" id="toolTips"> 
                <?php if ($sf_user->hasFlash('msg_success')): ?>                  
                    <?php echo $sf_user->getFlash('msg_success', ESC_RAW) ?>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</div> 


 <?php endif; ?>

<?php if(isset($ei_scenario)): ?>
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
