<!--comments-->
<?php $pair = false; ?>

<?php if (count($ei_projets)>0): ?>
    <tbody id="common_tbody" class="listeProjetsHome">
        <?php foreach ($ei_projets as $ei_project): 
//            $defaultProfil = $ei_project->getDefaultProfil();
//            $userDefaultProfile=$ei_user->getDefaultProfile($ei_project->getRawValue());
            $pair = !$pair; 
            //Définition de l'url de redirection vers le projet (action show ou firstLoad)
            $url_tab=array('project_id'=>$ei_project['project_id'],
                            'project_ref'=>$ei_project['ref_id']);
            if (isset($ei_project['up_profile_ref']) && $ei_project['up_profile_ref']!=null):
                    $url_tab['profile_name'] = $ei_project['up_name'];
                    $url_tab['profile_id'] = $ei_project['up_profile_id'];
                    $url_tab['profile_ref'] = $ei_project['up_profile_ref'];
                else:
                    if(isset($ei_project['pr_profile_ref']) && $ei_project['pr_profile_ref']!=null):
                        $url_tab['profile_name'] = $ei_project['pr_name'];
                        $url_tab['profile_id'] = $ei_project['pr_profile_id'];
                        $url_tab['profile_ref'] = $ei_project['pr_profile_ref'];
                        else:
                            $url_tab['profile_name'] = 'profil';
                            $url_tab['profile_id'] = 0;
                            $url_tab['profile_ref'] = 0;
                    endif;
                    
                endif;
           ?>
            <tr class="common_tr <?php if($pair) echo 'pair'; ?> ">
                <td>
                    <i class="fa fa-desktop fa-lg"> </i>
                    <?php if ($ei_project['version_courante']!= $ei_project['version_kalifast']):
                        $link=url_for2('recharger_fonctions',   array(
                                'project_id' => $ei_project['project_id'],
                                'project_ref' => $ei_project['ref_id'],
                                'redirect' => 'homepage'),
                            array('id' => "updateProject_".$ei_project['project_id'].'_'.$ei_project['ref_id'],
                                'class' => 'label label-warning update_project'));
                    else: $link=url_for2('projet_show', $url_tab) ; endif; ?>
                    <a id="<?php echo'linkToProject_'.$ei_project['project_id'].'_'.$ei_project['ref_id']?>" href="<?php echo $link ?>"
                         class="tooltipObjTitle" data-toggle="tooltip" title="<?php echo $ei_project['name']  ?>"> 
                        <?php echo $ei_project['name']  ?>
                        
                    </a> 
                </td>

                <td>  
                    <a  class='popoverDesc' title='Description' data-trigger="focus" data-placement='top' data-toggle='popover' href="#"
                        data-content="<?php echo trim(strip_tags(html_entity_decode($ei_project['description'],ENT_QUOTES,"UTF-8")))   ?>" >
                       <?php  echo  trim(strip_tags(html_entity_decode($ei_project['description'],ENT_QUOTES,"UTF-8")))  ?> 
                   </a>
                    
                </td>
                <td class="date">  
                    <?php echo substr ($ei_project['checked_at'], 0,10); ?>
                </td>
                <td class="date"> <?php
                        if ($ei_project['updated_at'] != null) 
                            echo substr ($ei_project['updated_at'], 0,10);
                        else
                            echo "Jamais";
                        ?> 
                </td>
                <?php if ($ei_project['obsolete']): ?>
                    <td class="projet_obsolet">  Obsolet ! </td>
                <?php elseif ($ei_project['version_courante'] != $ei_project['version_kalifast']): ?>
                    <td class="recharger_projet">
                        <?php
                        echo link_to2('Update', "recharger_fonctions", 
                                array('project_id' => $ei_project['project_id'], 
                                      'project_ref' => $ei_project['ref_id'], 
                                      'redirect' => 'homepage',
                                      ),
                                array('id' => "updateProject_".$ei_project['project_id'].'_'.$ei_project['ref_id'],
                                      'class' => 'label label-warning update_project'));
                        ?>
                    </td>
                <?php else: ?>
                    <td class="projet_a_jour">
                        <span class="label label-success">Up to date</span> 
                     </td>
                <?php endif; ?>
            </td>
          
        </tr>
    <?php endforeach; ?>

    </tbody>
<?php else : ?>
    <?php if ($sf_user->hasFlash('no_project')): ?>
        <p class="flash_msg"><b><i><?php echo $sf_user->getFlash('no_project') ?></i></b></p>
    <?php endif; ?>
<?php endif; ?>

