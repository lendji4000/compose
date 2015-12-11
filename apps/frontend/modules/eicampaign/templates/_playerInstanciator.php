<script type="application/javascript">
    var EiServiceManager = new EiService(
        '<?php echo $systeme; ?>',
        '<?php echo $firefoxPath === null ? "C:\\Kalifast\\Firefox\\FirefoxPortable.exe" : str_replace("\\", "\\\\", $firefoxPath); ?>',
        '<?php echo $sf_user->getEiUser()->getTokenApi() ?>',
        '<?php echo $projetRef; ?>',
        '<?php echo $project_id; ?>',
        '<?php echo $ei_project->getName(); ?>',
        '<?php echo $profilRef; ?>',
        '<?php echo $profile_id; ?>',
        '<?php echo $profile_name; ?>'
    );
</script>