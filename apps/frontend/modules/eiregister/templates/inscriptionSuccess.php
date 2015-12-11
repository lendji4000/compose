
<?php use_helper('I18N') ?>
<h2><?php echo __('S\'enregistrer', null, 'sf_guard') ?></h2>

<?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>