<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="form-horizontal" id="kalViewForm"
      action="<?php echo url_for('view/create?project_id='.$ei_project->getProjectId().'&project_ref='.$ei_project->getRefId().'&parent_id='.$ei_parent_tree->getId()) ?>"
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="row">
            <?php echo $form->renderHiddenFields(true) ?> 
            <?php echo $form->renderGlobalErrors() ?> 
    </div>
    <div class="row"><?php echo $form['name']->renderError() ?></div>
    <div class="row"> 
 
        <table class="table   table-striped ">
            <thead>
                <tr>
                    <th>View Name</th> 
                    <th colspan="2"><?php echo $form['name'] ?></th>
                </tr>
            </thead> 
        </table> 

    </div> 
</form>
