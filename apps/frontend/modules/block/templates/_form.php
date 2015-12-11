<?php  
$url_tab=array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

if($form->getObject()->isNew()){
    $class="form-inline new_block";
    $url_tab['ei_block_parent_id']=$ei_block_parent_id;
    $url_tab['ei_scenario_id']=$ei_scenario_id; 
    if(isset($insert_after))
        $url_tab['insert_after'] = $insert_after;
    $url = url_for2("eiblock_create", $url_tab); 
}
else{
    //echo "<h4>".$form->getObject()->getName()."</h4>";
    $class="form-horizontal update_block";
    $url_tab['ei_block_id']=$form->getObject()->getId();
    $url = url_for2("eiblock_update", $url_tab);
}

?>

<form class="blockForm <?php echo $class ?> " action="<?php echo $url ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
        <div class="row">
            <?php echo $form->renderGlobalErrors(); 
            echo $form->renderHiddenFields(); ?>
        </div>  
        <div class="row ">
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
        
        <div class="btn-group col-lg-2 col-md-2">
            <button class="btn btn-sm btn-success submit_block" value="Save">
                <i class="fa fa-check"></i>    Save
            </button>
            <?php if($form->isNew()): ?>
            <a href="#!" class="btn btn-sm btn-danger delete_block_new">
                <?php echo ei_icon('ei_delete') ?>
            </a>
            <?php endif;  ?>
        </div>
        </div>
</form>
 