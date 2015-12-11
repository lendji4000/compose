<?php if ($sf_user->isAuthenticated()): ?>

<div class="sidebar-footer" id="eiSideBar2">
    
        <?php
        if ($project_ref != null && $project_id != null && $profile_ref != null && $profile_id != null && $profile_name != null) : 
            $url_tab = array(
                'project_id' => $project_id,
                'project_ref' => $project_ref,
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'profile_name' => $profile_name );
        /* Recherche d'une ventuelle intervention par défaut */
         $defaultIntervention = $sf_user->getDefaultIntervention($url_tab);   ?>   
    
    <ul class="sidebar-footer-menu  " id="eiSideBar21BgManagement">
        <li> 
            <a href="<?php echo url_for2('delivery_list', $url_tab) ?>#"
                   id="eiProjectDeliveriesMainAccess" class=" " title="Deliveries list"> 
                    <?php echo ei_icon('ei_delivery', 'lg') ?>
            </a>
        </li> 
        <li>
            <a href="<?php echo url_for2('delivery_new', $url_tab) ?>#"
                   id="eiCreateDeliveryByMainAccess" class=" " title="New Delivery"> 
                     <?php echo ei_icon('ei_add','lg','','','ei-add-top') ?>
                    <?php echo ei_icon('ei_delivery', 'lg') ?>
            </a>
        </li>
        <li> 
                <a href="<?php  echo url_for2('subjects_list', $url_tab) ?>#"
                   id="eiProjectSubjectMainAccess" class=" " title="Interventions list"> 
                    <?php echo ei_icon('ei_subject', 'lg') ?>
                </a>
                
        </li> 
        <li>
            <a href="<?php echo url_for2('subject_new', $url_tab)  ?>#"
                   id="eiCreateBugByMainAccess" class=" " title="New intervention"> 
                    <?php echo ei_icon('ei_add','lg','','','ei-add-top') ?>
                    <?php echo ei_icon('ei_subject', 'lg') ?>
                </a>
                 
        </li>
        <li>
            <a id="eiAccessToDoList"   title="My to do list" 
                    href="<?php  echo url_for2('toDoList',$url_tab) ?>#"  > 
                     <i class="fa fa-user-md fa-lg ei-add-top"></i> 
                         <?php echo ei_icon('ei_subject', 'lg') ?>
                 </a>
        </li>
    </ul>     
    <ul class="sidebar-footer-menu" id="eiSideBar22BgManagement"> 
        <?php  $cdi=$sf_user->getAttribute("current_delivery_id" );
        $cdn=$sf_user->getAttribute("current_delivery_name" );
        $cdpr=$sf_user->getAttribute("current_delivery_project_ref"); 
        $cdpi=$sf_user->getAttribute("current_delivery_project_id"); 
        $cdprn=$sf_user->getAttribute("current_delivery_profile_name");  
        $cdpri=$sf_user->getAttribute("current_delivery_profile_id"); 
        $cdprr=$sf_user->getAttribute("current_delivery_profile_ref");   ?>
        <?php if($cdi!=null && $cdpr!=null && $cdpi!=null && $cdprn!=null && $cdpri!=null && $cdprr!=null): ?>
        <li>  
             <a href="<?php  echo url_for2('getDeliverySubjects', array(
                 'project_id' => $cdpi,
                'project_ref' =>$cdpr,
                'profile_id' => $cdpri,
                'profile_ref' => $cdprr,
                'profile_name' => $cdprn,
                 'delivery_id' => $cdi,
                 'action' => 'getDeliverySubjects' )) ?>#"
                   id="eiProjectCurrentDelivery" class="btn btn-link col-lg-5 col-md-5" >
                     <?php echo ei_icon('ei_delivery', 'lg') ?>
                       <?php  echo   'D '.$cdi ?>
                </a>
        </li> 
        <?php endif; ?>  
            <?php    
              $defaultIntLinkParams=$url_tab; $defaultIntLinkParams['defaultIntervention']=isset($defaultIntervention)?$defaultIntervention:null;
              include_partial('eisubject/defaultIntLink',$defaultIntLinkParams) ;  ?> 
    </ul> 
    <?php endif; ?>
</div>	
<div id="supportContact">
    <strong><?php echo ei_icon('ei_mail') ?>&nbsp;support@eisge.com</strong><br/>
    <strong><?php echo ei_icon('ei_phone') ?>&nbsp;+33 9 707 3 4 5 6 7</strong>
</div>
<?php endif; ?>

