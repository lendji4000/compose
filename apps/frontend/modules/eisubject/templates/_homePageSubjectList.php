<?php
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref);
?>
<table class="table table-striped bootstrap-datatable dataTable   small-font" id="<?php echo $datatableId ?>">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title  </th>
            <th>Author</th>
            <th>State</th>
            <th >Created At</th>
            <th>Updated At</th> 
        </tr>
    </thead>   
    <tbody>
<?php if (isset($bugList) && count($bugList) > 0): ?>
    <?php foreach ($bugList as $ei_subject): ?>
        <tr>
            <td> 
                <a href="#" class=" popoverObjDesc" title="<?php echo 'S' . $ei_subject['id'] ?>"
                   data-trigger="focus"  data-placement="bottom" data-toggle="popover" data-html="true"
                   data-content="<div>  <p><small>ID : </small> <?php echo 'S' . $ei_subject['id'] ?> </p>
                   <p><small>BUGS : </small>  <?php echo $ei_subject['id'] ?> </p>
                   <p><small>CLOSE BUGS  :</small> <?php echo $ei_subject['id'] ?>  </div>"  > 
                    <span class="text">   
                        <strong><?php echo 'S' . $ei_subject['id'] ?></strong> 
                    </span>
                </a>
            </td>
            <td>
                <?php
                $subject_show = $url_tab;
                $subject_show['subject_id'] = $ei_subject['id']; 
                ?>
                <a href="<?php echo url_for2('subject_show', $subject_show) ?>"
                   class="tooltipObjTitle"   data-placement="right" data-toggle="tooltip"
                   data-original-title="<?php echo $ei_subject['name'] ?>">
                    <?php echo MyFunction::troncatedText($ei_subject['name'], 50) ?>
                </a>
            </td>
            <td>
                <a class="tooltipUser"  data-toggle="tooltip" href="#" 
                   data-original-title="<?php echo $ei_subject['sfGuardUser']['author_email'] ?>">
                <?php echo $ei_subject['sfGuardUser']['author_username'] ?> 
                </a> 
            </td>
            <td> 
                <?php if(isset($ei_subject['EiSubjectState'])): $state=$ei_subject['EiSubjectState']; ?>
                <span style="background-color:<?php echo $state['st_color_code'] ?> " class="label">  <?php echo $state['st_name']; ?> </span>     
                <?php endif; ?>  
            </td>
            <td class=" text-info"> 
                <?php  $created_at= new DateTime($ei_subject['created_at']); echo $created_at->format('d/m/Y') ?>    
            </td>
            <td class="  text-info"> 
            <?php  $updated_at= new DateTime($ei_subject['updated_at']); echo $updated_at->format('d/m/Y') ?>    
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?> 
    </tbody>
</table>
