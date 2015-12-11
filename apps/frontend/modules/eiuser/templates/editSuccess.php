<?php if($sf_user->hasFlash('msg_success')): ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $sf_user->getFlash('msg_success') ?></strong>
    </div>
<?php endif; ?> 
<div class="row">
    <div class="row"> 
        <?php include_partial('form', array('form' => $form)) ?> 
    </div>
    
</div>



