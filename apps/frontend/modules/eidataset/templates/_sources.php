<div class="panel panel-default eiPanel">
    <div class="panel-heading" data-original-title>
        <h2 class="title_project">
            <?php echo ei_icon('ei_version') ?>
            <span class="break"></span>
            Data Set Reference Sources
        </h2>
        <div class="panel-actions"> 
        </div>
    </div>
    <div class="panel-body table-responsive">
        <?php echo $treeDisplay != null ? html_entity_decode($treeDisplay->render(), ENT_QUOTES, "UTF-8"):"Impossible to display your tree. Maybe it's too large."; ?>
    </div>
</div>