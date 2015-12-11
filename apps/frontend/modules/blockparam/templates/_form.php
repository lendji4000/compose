<?php  
if($form->isNew()){
    $params = array('ei_block_parent_id' => $ei_block_parent_id);
    if(isset($insert_after))
        $params['insert_after'] = $insert_after;
    $url = url_for2("eiblockparam_create", $params);
}
else{
    $url = url_for2("eiblockparam_update", array('ei_block_param_id' => $form->getObject()->getId()));
}

?>
<form class="blockParamForm form-inline padding-left row" action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>

    <?php echo $form->renderGlobalErrors(); 
            echo $form->renderHiddenFields();
            ?> 
     
    <div class="row">
        <?php echo $form['name']->renderError() ?>
        <?php echo $form['description']->renderError() ?>
    </div>    
    <div class="row"> 
        <div class="col-lg-5 col-md-5"> 
             <div class=" form-group">  
                 <div class="input-group">
                    <span class="input-group-addon">Name</span>
                    <?php echo $form['name'] ?> 
                </div>
             </div> 
        </div>    
        <div class="col-lg-5 col-md-5"> 
            <div class=" form-group"> 
                 <div class="input-group">
                    <span class="input-group-addon">Description</span>
                    <?php echo $form['description'] ?>
                </div>
             </div>
        </div>
        <?php 
    if($form->isNew()){
        $class = "new";
        $url_delete= "#!";
    }
    else{
        $class="old";
        $url_delete = url_for2('eiblockparam_delete', array('ei_block_param_id' => $form->getObject()->getId()));
    }
    ?>
        <div class="btn-group  col-lg-2 col-md-2 ">
            <button class="btn btn-sm btn-success submit_block_param" value="Save">
                <i class="fa fa-check"></i>Save
            </button>
            <a href="<?php echo $url_delete ?>" class="btn btn-sm btn-danger delete-btn delete_block_param_<?php echo $class; ?>">
                <?php echo ei_icon('ei_delete') ?>
            </a>
        </div>
           
    </div>    
       
</form>


 
