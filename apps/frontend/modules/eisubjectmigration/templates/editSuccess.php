<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'subject_id' => $ei_subject->getId()); 
?>  
<div id="subjectContentMigration">
    <?php $formUri = $url_tab;
    $formUri['form'] = $form ?>
<?php include_partial('form', $formUri) ?> 
</div>
         