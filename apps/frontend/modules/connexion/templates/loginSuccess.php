<form method="post" action="<?php echo url_for('@connexion') ?>" id="connexion-form" class="col-lg-12 col-md-12">
    <?php if($sf_user->hasFlash('error_connexion') || $sf_user->hasFlash('valid_connexion')): ?>
        <div class="alert alert-error pagination-centered">
            <strong> Error ! </strong>
            <a class="close" data-dismiss="alert" > x</a>
                    <?php if($sf_user->hasFlash('error_connexion')): ?>
                    <?php echo $sf_user->getFlash('error_connexion', ESC_RAW) ?>
                    <?php endif; ?>
                    <?php if($sf_user->hasFlash('valid_connexion')): ?>
                    <?php echo $sf_user->getFlash('valid_connexion', ESC_RAW) ?>
                    <?php endif; ?>
        </div>
    <?php endif;?>

    <div  class="row">
        <div class="form-group col-md-12 col-lg-12">
            <label class="control-label col-md-4" for="inputLogin">Username</label>
            <div  class="col-md-8">
                <input id="login" type="text" name="login" class="input-large form-control"/>
            </div>
        </div>
        <div class="form-group col-md-12 col-lg-12">
            <label class="control-label col-md-4" for="inputPassword">Password</label>
            <div  class="col-md-8">
                <input id="pwd" type="password" name="pwd" class="input-large form-control "/>
            </div>
        </div>
    </div>

    <div class="form-group col-md-4 col-md-offset-5">
        <div class="controls">
            <button class="btn btn-success btn-primary" type="submit" id="eiLogin">
                Login  <i class="fa fa-check"> </i>
            </button>
        </div>
    </div>
</form>