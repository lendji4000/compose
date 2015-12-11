<?php if (isset($ei_project) && isset($ei_profile) && isset($ei_node) && isset($file) ): ?>
    [
    {
    "result" : "<?php echo 'ok' ; ?>"
    }
    ]<?php else : ?>
    [
    {
    "erreur" : "e"
    }
    ]

<?php endif; ?>
