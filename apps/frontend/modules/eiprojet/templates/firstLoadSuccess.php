<!--comments-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <?php use_helper('jQuery'); ?>
    </head>
    <body>
                <input type="hidden" name="project_id" value="<?php echo $ei_project->getProjectId() ?>" class="project_id" />
                <input type="hidden" name="project_ref" value="<?php echo $ei_project->getRefId() ?>" class="project_ref" />
        <?php 
            include_partial("eiprojet/reloader", array('reloadProjet' => $reloadProjet, 'project_id' => $ei_project->getProjectId() , 'project_ref' => $ei_project->getRefId() )); 
            include_partial("global/footer");
        ?>
    </body>
    
</html>