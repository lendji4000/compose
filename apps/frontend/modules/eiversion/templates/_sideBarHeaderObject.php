<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         'ei_scenario_id' => $ei_scenario_id
     )?>   
<?php if(isset($ei_version) && $ei_version!=null && $ei_version!=$defaultVersion): ?>
        <h2 class="alert-warning"> <strong><i class="fa fa-warning"></i></strong> 
             Current version is not associate to current environment... </h2>
        <?php endif; ?>
<!--  Menu de sous objet ( version d'un scénario )-->
<div class="panel panel-default eiPanel" id="ei_sous_objet_header">
      <!-- Default panel contents -->
      <div class="panel-heading">
            <h2>
                <?php echo html_entity_decode($logoTitle) ?>
                  <a href="#"> <?php echo $objTitle?></a> 
                  <strong><?php echo "    (   ".((isset($ei_scenario_package) && $ei_scenario_package!=null)?"S".$ei_scenario_package['subject_id']:"No Intervention link")."    )   " ?></strong>
            </h2>
            <div class="btn-group" style="padding-left: 10px;">
                <?php  $objMenu=   $objMenu->getRawValue($objMenu); ?>
                  <?php if(is_array($objMenu) && count($objMenu)>0): ?>
                  <?php foreach ($objMenu as $menu): ?> 
                      <a href="<?php echo $menu['uri'] ?>" data-toggle="<?php echo $menu['tab'] ?>" 
                         title="<?php echo $menu['titleAttr'] ?>" id="<?php echo $menu['id'] ?>"
                         class="<?php echo ($menu['active'] ? 'active' : '') ?> btn btn-default btn-sm">
                         <?php echo html_entity_decode($menu['logo'].' ') ?> 
                         <?php echo $menu['title'] ?>
                      </a>   
                  <?php endforeach; ?>
                  <?php endif; ?>  
            </div>
      </div>  
</div>

  
 
