<?php if ($reloadProjet == true && $project_id!=null && $project_ref!=null): ?>
    <?php

    $refreshProject=array(
        'project_id'=>$project_id,
        'project_ref' => $project_ref) ?>
    <div id="content_reloader" datasrc="<?php echo url_for2('recharger_fonctions',$refreshProject) ?>">
        <input type="hidden" name="project_ref" value="<?php echo $project_ref; ?>" class="project_ref" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" class="project_id" />
        <p>
            Loading project from central system ...
        </p>
        <p> 
            <i class="fa fa-spinner fa-spin fa-5x" ></i> 
        </p>
    </div> 
<?php endif; ?>