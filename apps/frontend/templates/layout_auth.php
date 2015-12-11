<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="Kalifast icon" href="/images/logos/picto_compose_2.png" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <?php //use_helper('jQuery'); ?>
    </head>
    <body>
        <div class="logo_signin">
            <img src="/images/logos/banniere-Kalifast.png" alt="" width="600"/>
        </div>

        <div class="container background_form">
            <div id="corps_bg">
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2><i class="fa fa-key"></i>Sign In</h2>
                        <div class="panel-actions"></div>
                    </div>
                    <div class="panel-body">
                        <?php echo $sf_content; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
