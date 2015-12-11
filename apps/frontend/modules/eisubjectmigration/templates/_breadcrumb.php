<!-- Chemin de navigation-->
<?php if (isset($chemin)): ?>
    <ol class="breadcrumb" >
        <?php echo html_entity_decode($chemin); ?>
    </ol> 
<?php endif; ?>