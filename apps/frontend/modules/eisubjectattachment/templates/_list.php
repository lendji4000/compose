<?php 
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name, 
);  
?> 

<table class="table table-striped bootstrap-datatable dataTable  small-font">
    <thead> 
        <tr>
            <th> Preview </th>
            <th> Title </th>
            <th> Description </th>
            <th  width="8%"> Actions </th>
        </tr> 
    </thead>
    <tbody>
    <?php if (isset($subjectAttachments) && count($subjectAttachments) > 0): ?>  
        <?php foreach ($subjectAttachments as $attach): ?>
            <?php
                $eisubjectattachment_attachementLine=$url_params;
                $eisubjectattachment_attachementLine['attach'] = $attach;
                include_partial('eisubjectattachment/attachementLine', $eisubjectattachment_attachementLine);
            ?>
        <?php endforeach ?> 
     <?php endif; ?>
    </tbody> 
</table> 

    <!-- Erreurs sur le fichier envoyé au cas ou le formulaire n'est pas valide-->
    
            <?php if ($form->hasGlobalErrors()): ?>  
            <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong> <?php echo $form->renderGlobalErrors() ?> </strong> 
            </div>
            <?php endif; ?>
    <?php if ($form->hasErrors()): ?> 
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>   
                <?php echo $form['path']->renderError() ?> 
                <?php echo $form['author_id']->renderError() ?> 
                <?php echo $form['filename']->renderError() ?> 
            </strong>  
        </div>
    <?php endif; ?> 
        <?php if (isset($form)): ?>
        <?php $eisubjectattachment_form=$url_params;
        $eisubjectattachment_form['form']=$form;
        $eisubjectattachment_form['subject_id']=$subject_id;  
        include_partial('eisubjectattachment/form',$eisubjectattachment_form) ?>

        <?php endif; ?> 