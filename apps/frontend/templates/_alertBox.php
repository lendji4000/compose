<?php if(isset($flash_string)):
    if ($sf_user->hasFlash($flash_string)): $alert_tab = $sf_user->getFlash($flash_string); ?>  
    <div id="alertBox">
        <div class="alert <?php echo $alert_tab['class'] ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong><?php echo $alert_tab['title'] ?> !</strong> <?php echo $alert_tab['text'] ?>
        </div>
    </div>
<?php endif;  
endif; ?>