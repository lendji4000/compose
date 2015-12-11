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
                        <a href="<?php echo $menu['uri'] ?>" <?php if (isset($menu['tab'])): ?>data-toggle="<?php echo $menu['tab']?>"<?php endif; ?>
                            class="<?php echo $menu["class"] ?>"
                            <?php if (isset($menu['data-id'])): ?>data-id="<?php echo $menu['data-id']?>"<?php endif; ?>
                           title="<?php echo $menu['titleAttr'] ?>" id="<?php echo $menu['id'] ?>" >
                            <?php echo html_entity_decode($menu['logo'].' ') ?>
                            <?php echo $menu['title'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>