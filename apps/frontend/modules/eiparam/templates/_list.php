<?php if($ei_params!=null) : ?>
    <?php foreach ($ei_params as $ei_param): ?>
    <?php $form = new EiParamForm(Doctrine_Core::getTable('EiParam')->find(array($ei_param->id))) ?>
    <?php include_partial('eiparam/form', array('form' => $form)) ?>
    <?php endforeach; ?>
<?php else : ?>
<b>Aucun paramètre : ajoutez en si besoin !!</b>
<?php endif; ?>

