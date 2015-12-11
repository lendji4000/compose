<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>
<div class="row" id="header1">
    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-7" id="header1Part1">
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-3" id="eisge-breadcrumb-navbar-actions">
                <ul class="nav navbar-nav navbar-actions navbar-left">
                    <li class="visible-md visible-lg">
                        <a href="<?php echo $sf_request->getUri(); ?>#" id="main-menu-toggle">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    <li class="visible-xs visible-sm">
                        <a href="<?php echo $sf_request->getUri(); ?>#" id="sidebar-menu">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-11 col-md-10 col-sm-9" id="eisge-breadcrumb">

                <?php
                switch ($sf_request->getParameter('module')):
                    case 'eiprojet' : 
                            include_component('eiprojet', 'breadcrumb'); 
                        break;
                    case 'bugContext':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eicampaign':
                    case 'eicampaignexecution':
                    case 'eicampaigngraph':
                        include_component('eicampaign', 'breadcrumb');
                        break;
                    case 'eidataset':
                        include_component('eiscenario', 'breadcrumb');
                        break;
                    case 'eidelivery':
                        include_component('eidelivery', 'breadcrumb');
                        break;
                    case 'eideliveryCampaign':
                        include_component('eicampaign', 'breadcrumb');
                        break;
                    case 'eiiteration':
                        include_component('eiiteration', 'breadcrumb');
                        break;
                    case 'eifolder':
                        break;
                    case 'eifunctionnotice':
                        break;
                    case 'einode':
                        break;
                    case "eidatasetstructure":
                    case 'eiscenario':
                        include_component('eiscenario', 'breadcrumb');
                        break;
                    case 'eisubject':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eisubjectdetails':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eisubjectsolution':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eisubjectmigration':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eisubjecthascampaign':
                        include_component('eicampaign', 'breadcrumb');
                        break;
                    case 'eiversion':
                        include_component('eiscenario', 'breadcrumb');
                        break;
                    case 'eitestset':
                        include_component('eiscenario', 'breadcrumb');
                        break;
                    case 'kalfonction':
                        include_component('kalfonction', 'breadcrumb');
                        break;
                    case 'eiversionnotice':
                        include_component('kalfonction', 'breadcrumb');
                        break;
                    case 'eifunctionparams':
                        include_component('kalfonction', 'breadcrumb');
                        break;
                    case 'functionCampaigns':
                        include_component('kalfonction', 'breadcrumb');
                        break;
                    case 'subjectfunction':
                        include_component('eisubject', 'breadcrumb');
                        break;
                    case 'eiuser':
                        include_component('eiuser', 'breadcrumb');
                        break;
                    default :
                        break;
                endswitch;
                ?>
                
            </div>
        </div>  
    </div>
    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-5" id="header1Part2">
        <div class="row">
            <ul class="nav navbar-nav navbar-right">  
            <?php $guardUser=$sf_user->getGuardUser() ?>
            <?php if ($guardUser!=null): ?>   
                    <!--<li class="dropdown visible-md visible-lg visible-sm visible-xs">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="eiUserAccess"> 
                            <?php echo ei_icon('ei_life_saver') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu-header  ">
                                <span>Contact kalifast support</span>
                            </li>
                            <li class="supportLi">
                                <?php echo ei_icon('ei_mail') ?>&nbsp;
                                support@eisge.com
                            </li>
                            <li class="supportLi">
                                <?php echo ei_icon('ei_phone') ?>&nbsp;
                                +33 9 707 3 4 5 6 7
                            </li>
                        </ul>
                    </li>-->
                    <li class="dropdown visible-md visible-lg visible-sm visible-xs">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="eiUserAccess"> 
                            <?php echo ei_icon('ei_user') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu-header  ">
                                <small>Welcome &nbsp;</small>
                                <strong><?php echo MyFunction::troncatedText($guardUser->getUsername(), 15) ?>&nbsp;&nbsp;</strong> 
                            </li>
                            <?php  if ($project_ref!=null && $project_id!=null && $profile_ref!=null && $profile_id!=null && $profile_name!=null) : ?>
                             <!--Calcul du nombre de sujets (bugs) assignés à l'utilisateur courant-->
                            <?php 
                            $states=Doctrine_Core::getTable('EiSubjectState')->getSubjectStateForProjectQuery(
                                $project_id, $project_ref)->execute();
                            $stateTab=array();
                            if(count($states)>0): 
                                foreach($states as $i => $state):   
                                    if($state->getDisplayInTodolist()):
                                        $stateTab[]=$state->getId();
                                    endif;
                                endforeach;
                            endif; 
                            $q = Doctrine_Core::getTable('EiSubject')
                                            ->sortSubjectByCriterias(Doctrine_Core::getTable('EiSubject')
                                                    ->getSubjectsAsArray($project_id, $project_ref), 
                                                    array('assignment'=>$guardUser->getUsername(),
                                                          'state' => $stateTab))
                                                ->fetchArray();  
                                 
                            ?> 
                            <li>
                                <?php $subjects_list = $url_tab; $subjects_list['state']=$stateTab; 
                                $subjects_list['assignment']=$guardUser->getUsername() ?>
                                <a href="<?php echo url_for2('subjects_list',$subjects_list ) ?>#" id="eiAccessToDoListUser">
                                    <?php echo ei_icon('ei_subject') ?> My interventions
                                    <span class="label label-info">
                                        <?php echo count($q); //Nombre d'enregistrements?>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <input id="isUserGetDefaultPack" type="hidden" itemref="<?php echo url_for2('setDefaultPackage', array(
                                    'project_id' => $project_id,
                                    'project_ref' => $project_ref,
                                    'action' => 'isUserGetDefaultPack'
                                )) ?>" />
                                 
                                <?php echo link_to2('<i class="fa fa-gears "></i> User Settings', 'default', array('module' => 'eiuser', 'action' => 'index'), 
                                array('id'=>'eiUserSettings')); ?>
                            </li>
                             <?php endif; ?>
                            <li>
                                <?php echo link_to2(' <i class="fa fa-cubes">
                                </i> &nbsp; Resources', 'resources', array('module' => 'eiresources', 'action' => 'index'));
                                ?> 
                            </li>
                            <li>
                                <?php echo link_to1(' <i class="fa fa-power-off">
                                </i> &nbsp; Sign out', '@sf_guard_signout', array('id' => 'eiUserLogOut'))
                                ?> 
                            </li>
                        </ul>
                    </li>
                    <li class=" visible-md visible-lg">
                        <?php echo link_to1(' <i class="fa fa-power-off">
                        </i>', '@sf_guard_signout', array('title' => 'Log out', 'id' => 'eiLogOut'))
                        ?> 
                    </li>
                    <?php endif; ?> 
            </ul>
        </div>  
    </div>
</div> 