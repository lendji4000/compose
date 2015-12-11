<?php if(isset($ei_project)):?>
<!-- Menu de sélection des projets --> 
<li class="dropdown  active"> 
    <a href="#" id="projectsHeaderLink" class="dropdown-toggle" data-toggle="dropdown" >
                    <i class="fa fa-desktop fa-lg">  </i> Project: &nbsp;
                    <?php echo '<b>' . $ei_project->getTroncatedName(13) .'</b>'; ?>
                    <b class="caret bottom-up pull-right"></b>
                </a>  
                <ul class="dropdown-menu" role="menu">
                    <?php foreach ($ei_projets as $projet): ?>

                        <li>
                            <a href="<?php
                            echo url_for2('projet_list_show', array('project_id' => $projet->getProjectId(),
                                'project_ref' => $projet->getRefId())); ?> ">  
<!--                                <img src="/images/boutons/small_engrenage.png" alt="" />-->
                                <span><i class="fa fa-desktop fa-lg">  </i>
                                    <?php echo $projet->getTroncatedName(25) ?>
                                </span>
                            </a>
                        </li> 
                    <?php endforeach; ?>
                </ul>
            </li>  
<?php endif; ?>

