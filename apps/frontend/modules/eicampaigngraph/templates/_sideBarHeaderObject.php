<?php
$url_tab=array(
    'project_id' => $project_id,
    'project_ref' =>$project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name,
    'campaign_id' => $campaign_id
)
?>

<!--  Menu de sous objet ( version d'un scénario )-->
<div class="panel panel-default eiPanel" id="ei_sous_objet_header">
      <!-- Default panel contents -->
      <div class="panel-heading">
          <?php echo html_entity_decode($logoTitle) ?>
          <a href="#"> <?php echo $objTitle?></a>
           
          <ul class="nav nav-tabs">
              <?php  $objMenu=   $objMenu->getRawValue($objMenu); ?>

              <?php if(is_array($objMenu) && count($objMenu)>0): ?>
                  <?php foreach ($objMenu as $menu): ?>
                      <li class="<?php echo ($menu['active'] ? 'active' : '') ?>">
                          <a href="<?php echo $menu['uri'] ?>" data-toggle="<?php echo $menu['tab'] ?>" title="<?php echo $menu['titleAttr'] ?>" id="<?php echo $menu['id'] ?>" >
                              <?php echo html_entity_decode($menu['logo'].' ') ?>
                              <?php echo $menu['title'] ?>
                          </a>
                      </li>
                  <?php endforeach; ?>
              <?php endif; ?>
          </ul>
      </div>  
</div>

  
 
