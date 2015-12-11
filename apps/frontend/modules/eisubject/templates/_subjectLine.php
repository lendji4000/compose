<?php if (isset($ei_subject) && isset($project_id) && isset($project_ref)): ?>
    <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name,
        'subject_id' => $ei_subject['id'],
        'contextRequest' => (isset($contextRequest) ? $contextRequest : null),
        'is_ajax_request'=> (isset($is_ajax_request) && $is_ajax_request) ?true:false
            )
    ?>
    <tr> 
        <td width="10%">
            <?php if (isset($module_context) && $module_context == 'EiSubject'): $checked = false; ?>
                <!--Si le sujet se trouve dans une livraison close, on n'affiche pas la checkbox-->
                <?php if (isset($ei_subject['EiDelivery']) && count($ei_subject['EiDelivery']) > 0 && isset($ei_subject['EiDelivery']['EiDeliveryState']) && $ei_subject['EiDelivery']['EiDeliveryState']['ds_close_state']): $checked = true
                    ?>
        <?php endif; ?>
                <input type="checkbox" class="check_subject_for_mult_act" <?php if ($checked): ?>disabled="disabled" <?php endif; ?>  
                       title="<?php echo (($checked) ? 'You can\'t edit this intervention because delivery has been closed ...' : '') ?>"
                       value="<?php echo $ei_subject['id'] ?>"  />

    <?php endif; ?>
    <?php echo 'S' . $ei_subject['id'] ?>
        </td>

        <td>  
            <?php echo $ei_subject['EiSubjectType']['t_name'] ?>  
        </td>
        <td >
    <?php if (isset($is_ajax_request) && $is_ajax_request): ?>  
            <?php $interventionLink=$url_tab; $interventionLink['action']="changeInterventionInMigrationLine"  ?>
            <a class=" <?php echo (isset($contextRequest) && $contextRequest=="interventionLink")?"changeInterventionInMigrationLine":"select_sub_for_steps" ?>  "  href="#"
                   itemref="<?php echo (isset($contextRequest) && $contextRequest=="interventionLink")?url_for2('intervention_actions', $interventionLink): url_for2('showSubjectCampaigns', $url_tab) ?>"  
                   title="<?php echo $ei_subject['name'] ?>">
                <?php echo MyFunction::troncatedText($ei_subject['name'], 50) ?> 
                </a>  
                   <?php else : ?> 
                <a class="tooltipUser eiObjName"  data-toggle="tooltip" href="<?php echo url_for2('subject_show', $url_tab) ?>" 
                   data-original-title="<?php echo $ei_subject['name'] ?>" > <?php echo ei_icon("ei_subject")." ".$ei_subject['name'];  ?> 
                </a> 

            <?php endif; ?>
        </td>
        <td> 
    <?php echo $ei_subject['sfGuardUser']['author_username'] ?>  
        </td>

        <!-- Utilisateurs déjà assignés au sujet --> 
        <td> <?php $assigns = $ei_subject['subjectAssignments']; ?>

            <?php if (count($assigns) > 0): ?> 
                <?php $allAssign = ""; ?>
                <?php
                foreach ($assigns as $assign):
                    //$allAssign.=htmlentities('<i class="fa fa-user"></i>' ).$assign['AssignmentUser']['ass_username'].'<br/>'; 
                    echo "<i class='fa fa-user'></i> " . $assign['AssignmentUser']['ass_username'] . '<br/>';
                endforeach;
                ?>  

            <?php endif; ?>   
        </td>
        <td> 
    <?php if (isset($ei_subject['EiSubjectState'])): $state = $ei_subject['EiSubjectState']; ?>
                <span style="background-color:<?php echo $state['st_color_code'] ?> " class="label">  <?php echo $state['st_name']; ?> </span>     
            <?php endif; ?>
        </td> 
        <td><?php echo $ei_subject['EiSubjectPriority']['p_name'] ?>  </td> 
        
        <td>
            <?php if (isset($ei_subject['EiDelivery']) && count($ei_subject['EiDelivery']) > 0)
                echo (($ei_subject['EiDelivery']['delivery_name'] != null) ? ei_icon("ei_delivery")." ".$ei_subject['EiDelivery']['delivery_name'] : '')
                ?>

        </td> 
        <?php if ( !(isset($is_ajax_request) &&   $is_ajax_request)): ?>
        <td> 
            <?php echo $ei_subject['updated_at'] ?> 
        </td>
        <td> 
            <?php
            $now = time();
            $creation_date = $ei_subject['created_at'];
            $diff = $now - strtotime($creation_date);
            $nb_days = floor($diff / (60 * 60 * 24));
            if(($nb_days != 0) && ($nb_days != 1))
            {
                echo $nb_days . ' days ago';
            }
            else
            {
                if($nb_days == 0)
                {
                    $nb_hours = floor($diff / (60 * 60));
                    if($nb_hours != 0 && $nb_hours != 1)
                    {
                        echo $nb_hours . ' hours ago';
                    }
                    else
                    {
                        echo $nb_hours . ' hour ago';
                    }
                }
                else
                {
                    echo $nb_days . ' day ago';
                }
            }
            ?> 
        </td>
        <td> 
            <?php echo $ei_subject['development_time'] ?> 
        </td>
        <td> 
            <?php echo $ei_subject['test_estimation'] ?> 
        </td>
        <td>
            <?php
            if (isset($ei_subject['subjectCampaigns']) && count($ei_subject['subjectCampaigns']) > 0):
                $tnr = $ei_subject['subjectCampaigns'][0]['EiCampaign'];
                if (isset($tnr['tnr_coverage']) && $tnr['tnr_coverage'] != null):
                    echo $tnr['tnr_coverage'] . ' %';
                else : echo '0 %';
                endif;
            else:
                echo '0 %';
            endif;
            ?> 
        </td>
        <td>

            <a class="tooltipUser unclickedLink" data-toggle="tooltip" href="#" 
               data-original-title=" <?php echo $ei_subject['alternative_system_id'] ?>">
    <?php echo MyFunction::troncatedText($ei_subject['alternative_system_id'], 30) ?>
            </a> 
        </td>
        <?php endif; ?>
    </tr>
<?php endif; ?>