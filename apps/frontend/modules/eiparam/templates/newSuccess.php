<h3>Nouveau paramètre</h3>
<?php if(isset($module_depart) && $module_depart!=null) :?>
<?php include_partial('form', array('form' => $form , 'module_depart'=>$module_depart)) ?>
<?php else : ?>
<?php include_partial('form', array('form' => $form )) ?>
<?php endif;?>