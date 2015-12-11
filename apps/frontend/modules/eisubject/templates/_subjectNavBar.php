<div id="subjectHeader">
    <input id="subject_id" name="subject_id" type="hidden" value="<?php echo $ei_subject->getId() ;?>" />
    <h5>Subject N° &nbsp; : &nbsp; <?php echo 'S'.$ei_subject->getId() ?></h5>
    <hr/>
    <div class="navbar" >
        <div class="navbar-inner" > 
            <ul class="nav" >
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Properties'): echo 'active' ; endif; ?>">
                    <a id="accessBugProperties" href="<?php
                    echo url_for2('subject_show', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    ))
                    ?>"> <i class="fa fa-wrench "></i> Properties 
                    </a> 
                </li>
                <li class="divider-vertical"></li>
                <!--Details on subject -->
                <li class="subjectItem  <?php if(isset($activeItem) && $activeItem=='Details'): echo 'active' ; endif; ?>">
                    <?php if($ei_subject->getEiSubjectDetails()==null): ?>
                    <a id="accessBugDetails" class=""  href="<?php
                        echo url_for2('subject_details_create', array(
                            'subject_id' => $ei_subject->getId(),
                            'project_id' => $project_id,
                            'project_ref' => $project_ref,
                            'action' => 'new'
                        ))
                        ?>"> <?php echo ei_icon('ei_add') ?> Details  
                    </a>
                    <?php else : ?>
                    <a id="accessBugDetails" class=" " href="<?php
                    echo url_for2('subject_details_edit', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'id' => $ei_subject->getEiSubjectDetails()->getId(),
                        'action' => 'show'
                    ))
                    ?>"><?php echo ei_icon('ei_edit') ?> Details    
                    </a>
                    <?php endif; ?>
                </li>
                <!--Solution for subject -->
                <li class="divider-vertical"></li>
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Solution'): echo 'active' ; endif; ?>">
                    <?php if($ei_subject->getEiSubjectSolution()==null): ?>
                    <a id="accessBugSolution" class=" "  href="<?php
                    echo url_for2('subject_solution_create', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'action' => 'new'
                    ))
                    ?>"> <i class="fa fa-lightbulb-o"></i> Solution  
                    </a>
                    <?php else : ?>
                    <a id="accessBugSolution" class=" "  href="<?php
                    echo url_for2('subject_solution_edit', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'id' => $ei_subject->getEiSubjectSolution()->getId(),
                        'action' => 'show'
                    ))
                    ?>"><i class="fa fa-lightbulb-o"></i> Solution   
                    </a>
                    <?php endif; ?>
                </li>
                <li class="divider-vertical"></li>
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Campaigns'): echo 'active' ; endif; ?>">
                    <a id="accessBugCampaigns" href="<?php
                    echo url_for2('subjectCampaignsList', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    ))
                    ?>">
                        <?php echo ei_icon('ei_campaign', 'lg') ?>  Campaigns  
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Functions'): echo 'active' ; endif; ?>">
                    <a id="accessBugFunctions" href="<?php
                    echo url_for2('subjectFunctionList', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    ))
                    ?>"><?php echo ei_icon('ei_function', 'lg') ?> Functions  
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <!--Migration for subject -->
                <li class="subjectItem <?php if(isset($activeItem) && $activeItem=='Migration'): echo 'active' ; endif; ?>">
                    <?php if($ei_subject->getEiSubjectMigration()==null): ?>
                    <a id="accessBugMigration" class=" "  href="<?php
                    echo url_for2('subject_migration_create', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'action' => 'new'
                    ))
                    ?>"><i class="fa fa-globe"></i> Migration  
                    </a>
                    <?php else : ?>
                    <a id="accessBugMigration" class=""  href="<?php
                    echo url_for2('subject_migration_edit', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'id' => $ei_subject->getEiSubjectMigration()->getId(),
                        'action' => 'show'
                    ))
                    ?>"><i class="fa fa-globe"></i> Migration   
                    </a>
                    <?php endif; ?>
                </li> 
                <li class="divider-vertical"></li>
                
                <li class="<?php if(isset($activeItem) && $activeItem=='Context'): echo 'active' ; endif; ?>">
                    <?php if(isset($ei_context) && $ei_context!=null): ?>
                    <a id="showBugContext"  href="<?php
                    echo url_for2('show_Bug_Context', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref,
                        'id' => $ei_context->getId()
                    ))
                    ?>"><i class="fa fa-ellipsis-h"></i> Context  
                    </a>
                    <?php else : //On crée un contexte vide par défaut pour le bug ?>
                    <a id="createDefaultBugContext"  href="<?php
                    echo url_for2('create_default_bug_context', array(
                        'subject_id' => $ei_subject->getId(),
                        'project_id' => $project_id,
                        'project_ref' => $project_ref
                    ))
                    ?>"><i class="fa fa-ellipsis-h"></i> Context    
                    </a>
                    <?php endif; ?>
                </li> 
            </ul>
        </div>
    </div>
</div>
