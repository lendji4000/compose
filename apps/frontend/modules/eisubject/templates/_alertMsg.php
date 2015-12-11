<?php if (isset($msg) && isset($msgClass) && isset($msgTitle)): ?>
    <div  class="alert <?php echo $msgClass ?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $msgTitle ?> </strong> <?php echo $msg ?>
    </div> 
<?php endif; ?>