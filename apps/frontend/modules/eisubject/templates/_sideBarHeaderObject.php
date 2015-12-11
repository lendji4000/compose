<?php if (isset($ei_subject)): ?>
<input id="subject_id" name="subject_id" type="hidden" value="<?php echo $ei_subject->getId() ;?>" />
<?php endif;?>
<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref); 
    ?>
    <div class="row" id="eisge-object">
        <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)--> 
        <h2>
            <?php echo ei_icon('ei_subject') ?> 
            <?php if (isset($ei_subject)):  $url_tab['subject_id']=$ei_subject->getId(); ?> 
                <strong><?php echo 'S'.$ei_subject->getId().'/'  ?></strong>
                <span class="text" title="<?php echo  $ei_subject ?>">   
                     <?php  echo   $ei_subject  ?> 
                </span> 
            <?php else: ?> 
                <span class="text" title="Administrate interventions">   
                    <strong>Administrate interventions</strong> 
                </span> 
             <?php endif; ?>
        </h2>

    </div>
 

        <div class="row" id="eisge-object-actions">
            <!-- Si on est dans un contexte d'objet (Projet,Campagne,Scenario, etc ...)
                 On vérifie que des actions principales ont été définies pour cet objet
            --> 
            <ul class="nav nav-tabs" role="tablist">
                <?php if (isset($ei_subject)): ?>
                <li class="subjectItem <?php if(isset($activeItem) && ($activeItem=='Show' || $activeItem=='Edit')): echo 'active' ; endif; ?>">
                    <a class="btn btn-sm" id="accessBugProperties" 
                       href="<?php  echo url_for2('subject_show', $url_tab) ?>"> 
                        <i class="fa fa-wrench "></i> <span class="text"> Properties</span> 
                    </a> 
                </li>   
                <!--Details on subject -->
                <li class="subjectItem  <?php if(isset($activeItem) && $activeItem=='Details'): echo 'active' ; endif; ?>">
                    <?php if(($subjDetails=$ei_subject->getSubjectDetails())==null): ?>
                    
                    <?php $subject_details_create= $url_tab ; $subject_details_create['action']='new' ?>
                    <a class="btn btn-sm" id="accessBugDetails"   
                       href="<?php echo url_for2('subject_details_create', $subject_details_create) ?>">
                        <?php echo ei_icon('ei_list') ?><span class="text">   Details  </span>  
                    </a>
                    <?php else :  ?> 
                    <?php $subject_details_edit= $url_tab ; $subject_details_edit['action']='show' ?>
                    <?php $subject_details_edit['id']=$subjDetails->getId();  ?>
                    <a id="accessBugDetails" class="btn btn-sm" 
                       href="<?php  echo url_for2('subject_details_edit', $subject_details_edit)?>">
                        <?php echo ei_icon('ei_list') ?> <span class="text"> Details </span> 
                    </a>
                    <?php endif; ?>
                </li>
                <!--Solution for subject --> 
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Solution'): echo 'active' ; endif; ?>">
                    <?php if(($subjSolution=$ei_subject->getSubjectSolution())==null): ?>
                    <?php $subject_solution_create= $url_tab ; $subject_solution_create['action']='new' ?>
                     
                    <a id="accessBugSolution" class="btn btn-sm"  
                       href="<?php  echo url_for2('subject_solution_create', $subject_solution_create) ?>"> 
                        <i class="fa fa-lightbulb-o"></i> <span class="text">   Solution </span>  
                    </a>
                    <?php else : ?>
                    <?php $subject_solution_edit= $url_tab ; $subject_solution_edit['action']='show' ?>
                    <?php $subject_solution_edit['id']=$subjSolution->getId()?>
                    <a id="accessBugSolution" class="btn btn-sm" 
                       href="<?php  echo url_for2('subject_solution_edit', $subject_solution_edit) ?>">
                        <i class="fa fa-lightbulb-o"></i> <span class="text"> Solution </span>   
                    </a>
                    <?php endif; ?>
                </li> 
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Campaigns'): echo 'active' ; endif; ?>">
                    <a class="btn btn-sm" id="accessBugCampaigns" 
                       href="<?php  echo url_for2('subjectCampaignsList', $url_tab) ?>">
                        <?php echo ei_icon('ei_campaign', 'lg') ?> <span class="text"> Campaigns </span>  
                    </a>
                </li> 
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Functions'): echo 'active' ; endif; ?>">
                    <a id="accessBugFunctions" class="btn btn-sm" 
                       href="<?php echo url_for2('subjectFunctionList', $url_tab)  ?>">
                        <i class="fa fa-bomb"></i><span class="text"> Impacts </span>  
                    </a>
                </li> 
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='adminMigration'): echo 'active' ; endif; ?>">
                    <a id="accessBugAdminMigration" class="btn btn-sm" 
                       href="<?php echo url_for2('subjectAdminMigration', $url_tab)  ?>">
                        <i class="fa fa-send"></i> <span class="text"> Migrate tests</span>  
                    </a>
                </li> 
                <!--Migration for subject -->
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Migration'): echo 'active' ; endif; ?>">
                    <?php if(($subjMigration=$ei_subject->getSubjectMigration())==null): ?>
                    <?php $subject_migration_create= $url_tab ; $subject_migration_create['action']='new' ?>
                     <a id="accessBugMigration" class="btn btn-sm"
                       href="<?php echo url_for2('subject_migration_create', $subject_migration_create) ?>">
                        <i class="fa fa-share-square-o"></i>  <span class="text">    Delivery process  </span> 
                    </a>
                    <?php else : ?>
                    <?php $subject_migration_edit= $url_tab ; $subject_migration_edit['action']='show' ?>
                    <?php $subject_migration_edit['id']=$subjMigration->getId() ?>
                    <a id="accessBugMigration" class="btn btn-sm"  
                       href="<?php  echo url_for2('subject_migration_edit', $subject_migration_edit) ?>">
                        <i class="fa fa-globe"></i> <span class="text"> Delivery process</span>   
                    </a>
                    <?php endif; ?>
                </li>  
                
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Context'): echo 'active' ; endif; ?>">
                    <?php if(isset($ei_context) && $ei_context!=null): ?>
                    <?php $show_Bug_Context= $url_tab ; $show_Bug_Context['id']=$ei_context->getId() ?>
                     <a id="showBugContext"  class="btn btn-sm" 
                        href="<?php echo url_for2('show_Bug_Context',$show_Bug_Context) ?>">
                         <i class="fa fa-ellipsis-h"></i><span class="text"> Context</span> 
                    </a>
                    <?php else : //On crée un contexte vide par défaut pour le bug ?>
                    <a id="createDefaultBugContext" class="btn btn-sm"  
                       href="<?php echo url_for2('create_default_bug_context', $url_tab) ?>">
                        <i class="fa fa-ellipsis-h"></i> <span class="text">   Context </span>   
                    </a>
                    <?php endif; ?>
                </li>  
                <?php else:   ?> 
                <li class=" subjectItem <?php if(isset($activeItem) && $activeItem=='bugsList'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php echo url_for2('subjects_list', $url_tab) ?>#"
                       title="Interventions list" id="AccessBugsListOnHeader">
                        <?php echo ei_icon('ei_subject') ?> List
                    </a>
                </li>
                <li class=" subjectItem <?php if(isset($activeItem) && $activeItem=='New'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php echo url_for2('subject_new', $url_tab) ?>#"
                       title="New intervention" id="AccessBugsListOnHeader">
                        <?php echo ei_icon('ei_subject') ?> New intervention
                    </a>
                </li>
<!--                <li class="<?php //if(isset($activeItem) && $activeItem=='stateList'  ): echo 'active' ; endif; ?>">
                    <a class=" btn btn-sm " href="<?php //echo url_for2('bug_state', $url_tab) ?>#" id="adminBugssStates">
                        <i class="fa fa-genderless"></i> States
                    </a>
                </li>-->
              <?php  endif; ?> 
                
            </ul>
        </div>     
 