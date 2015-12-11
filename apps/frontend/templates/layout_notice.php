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
        <?php echo $sf_content; ?>

        <!--[if !IE]>-->
        <script src="/js/jquery-2.1.1.min.js"></script>
        <!--<![endif]-->

        <!--[if IE]>
        <script src="/js/jquery-1.11.1.min.js"></script>
        <![endif]-->

        <script src="/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="/js/global/eicorps.js"></script>
    </body>
</html>