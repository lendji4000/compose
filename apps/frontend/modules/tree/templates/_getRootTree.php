<?php if(isset($ei_project) && $ei_project!=null): ?>
<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
); 
?>
<?php if ($reloadProjet == true): $reload = "reloading";  
        else: $reload= "notReloading";
        endif;
    ?>
<div class="arbre_projet" id="<?php echo $reload; ?> " >
    <div id="reloading_img"><img src="/images/icones/ajax-loader-transparent.gif" alt="loading" tag="loading"/>  </div>
 
    <?php if ($ei_tree != null): ?>
        <ul id="arbo_default">
            <input type="hidden" name="project_ref" value="<?php echo $ei_project->getRefId(); ?>" id="project_ref" />
            <input type="hidden" name="project_id" value="<?php echo $ei_project->getProjectId(); ?>" id="project_id" />

            <li class="no-padding">
                <div id="version_loader_div">
                    <img src="/images/icones/ajax-loader-transparent_small.gif" alt="" id="version_loader"/>
                </div>
                <i class="cus-house img_arbo_pack" title="Vues de l arborescence"> </i> 
                <?php echo $ei_tree->getName() ?>

            </li>
            <li class="no-padding">
                <ul class="arbo_pack ">
                    <?php $arboTree =$urlParams ; $arboTree['ei_tree']=$ei_tree; $arboTree['tree_chids']=$tree_childs;  ?>
                    <?php include_partial('tree/arboTree',$arboTree);
                    ?> 
                </ul>
            </li>
        </ul>
    <?php endif; ?>
</div> 
<?php endif; ?> 