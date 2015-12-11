<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php 
if($form->getObject()->isNew()):
    $uriForm=url_for('kalfonction/create?project_id='.$ei_project->getProjectId().'&project_ref='.$ei_project->getRefId().
            '&profile_id='.$ei_profile->getProfileId().'&profile_ref='.$ei_profile->getProfileRef().'&parent_id='.$ei_parent_tree->getId());
    else:
    $showFunctionContentUri=array(
        'project_id'=>$ei_project->getProjectId(),
        'project_ref' =>$ei_project->getRefId(),
        'profile_id'=> $ei_profile->getProfileId(),
        'profile_ref' => $ei_profile->getProfileRef(),
        'profile_name' => $ei_project->getName(),
        'function_id' => $form->getObject()->getFunctionId(),
        'function_ref' =>$form->getObject()->getFunctionRef()
    );
    $showFunctionContentUri['action']='update';
    $uriForm=url_for2('showFunctionContent',$showFunctionContentUri);
 
endif;  
?>
<form class="form-horizontal" id="kalFunctionForm"
      action="<?php echo $uriForm ?>"
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
                    <th>Function Name</th> 
                    <th>Description</th>
                </tr>
                <tr>
                    <td><?php echo $form['name'] ?></td> 
                    <td colspan="2"><?php echo $form['description'] ?></td>
                </tr>
            </thead> 
        </table> 

    </div>
    <?php  if($form->getObject()->isNew()): //On permet d'ajouter les paramètres uniquement à la création de la fonction ?>
    <div class="row"> 
        <h6>In parameters</h6>
        <table class="table table-bordered table-striped listKalParams">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th colspan="2">Default value</th>
                </tr>
            </thead>
            <tbody> 
                <?php if (isset($form['kalParams'])): ?>  
                <?php foreach($form['kalParams'] as $key => $fieldSchema): ?>
                    <?php if($fieldSchema['param_type']->getValue()=='IN'): ?>
                    <?php include_partial('kalparam/newParamField',array(
                        'form' => $form, 'number' => $key)) ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <a href="<?php echo url_for2('addParamField', array('paramType' => 'IN')) ?>"
                           class="btn btn-xs btn-success addKalParamField">
                             <?php echo ei_icon('ei_add','lg' ) ?>
                        </a>
                    </td> 
                </tr>
            </tfoot>
        </table> 
    </div>
    
    <div class="row"> 
        <h6>Out parameters</h6>
        <table class="table table-bordered table-striped listKalParams">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th colspan="2">Default value</th>
                </tr>
            </thead>
            <tbody> 
                <?php if (isset($form['kalParams'])): ?>  
                <?php foreach($form['kalParams'] as $key => $fieldSchema): ?>
                    <?php if($fieldSchema['param_type']->getValue()=='OUT'): ?>
                    <?php include_partial('kalparam/newParamField',array(
                        'form' => $form, 'number' => $key)) ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <a href="<?php echo url_for2('addParamField', array('paramType' => 'OUT')) ?>"
                           class="btn btn-xs btn-success addKalParamField">
                            <?php echo ei_icon('ei_add','lg' ) ?>
                        </a>
                    </td> 
                </tr>
            </tfoot>
        </table> 
    </div>
    <?php endif; ?>
</form>
