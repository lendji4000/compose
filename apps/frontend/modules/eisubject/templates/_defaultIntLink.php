<li id="eiProjectCurrentSubjectLi">
<?php
if (isset($defaultIntervention) && $defaultIntervention != null) :
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name);

    $subject_show_uri = $url_tab;
    $subject_show_uri['subject_id'] = $defaultIntervention['subject_id']
    ?>
    <a href="<?php echo url_for2('subject_show', $subject_show_uri) ?>#" id="eiProjectCurrentSubject" class="btn btn-link col-lg-5 col-md-5" >
    <?php echo ei_icon('ei_subject', 'lg') ?>   <?php echo 'S ' . $defaultIntervention['subject_id'] ?>
    </a>
<?php endif; ?>
</li>
