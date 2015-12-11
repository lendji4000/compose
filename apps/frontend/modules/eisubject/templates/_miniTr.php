<?php if(isset($ei_subject) && count($ei_subject)>0): ?>
<?php  $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'subject_id' => $ei_subject['subject_id']);
    ?> 
<tr>
        <td>
            <?php $subject_show_uri = $url_tab; ?>
            <a href="<?php echo url_for2('subject_show', $subject_show_uri) ?>" target="_blank" >
                <?php echo ei_icon("ei_subject") ?> <?php echo "S" . $ei_subject['subject_id']; ?>
            </a>
        </td>
        <td>    <?php echo $ei_subject['subject_type_name']; ?>  </td>
        <td>
            <a href="<?php echo url_for2('subject_show', $subject_show_uri) ?>" target="_blank">
                <?php echo $ei_subject['subject_name']; ?>
            </a>
        </td>
        <td>    
            <?php if(isset($ei_subject['assignments']) && count($ei_subject['assignments'])>0):?>
            <?php foreach($ei_subject['assignments'] as $guard_id => $username): ?>
            <i class="fa fa-user"></i> <?php echo $username ?> <br/>
            <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td>    
            <span style="background-color:<?php echo $ei_subject['subject_state_color'] ?> " class="label ">
                <?php echo $ei_subject['subject_state_name']; ?>
            </span> 
        </td>
        <td>    <?php echo $ei_subject['subject_priority_name']; ?>  </td> 
    </tr>  
<?php endif; ?>