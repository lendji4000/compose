<?php $url_params=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name ); 
        ?>
<!-- Actions globales dans l'édition du contenu des steps d'une campagne (Add selection , Add manual Action -->
 
<div class="row" id="editContentGlobalActions"> 
    <?php //if(isset($ei_project) && $ei_project!=null && isset($ei_profile) && $ei_profile!=null): ?>
    <div class="col-lg-4 col-md-4">
        <div class="btn-group pull-left "> 
            <a class="btn btn-xs btn-info" id="addManyStepInContent"
               href="<?php echo url_for2("addManyStepInContent",$url_params) ?>">
                <?php echo ei_icon('ei_add') ?> Selection
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="btn-group pull-left"> 
            <a class="btn btn-xs btn-info" id="addManualStepInContent"
               href="<?php echo url_for2("addManualStepInContent",$url_params) ?>">
              <?php echo ei_icon('ei_add') ?>  Manual Action
            </a>
        </div>
    </div>
    <?php //endif; ?>
</div>