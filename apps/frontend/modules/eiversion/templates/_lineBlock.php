<?php
    /** @var EiBlock $blockTravelled */
    $blockTravelled = $child->getRawValue();

    $urlBlockEdit = url_for2("projet_edit_eiversion", array(
        "project_id" => $project_id,
        "project_ref" => $project_ref,
        "profile_name" => $profile_name,
        "profile_id" => $profile_id,
        "profile_ref" => $profile_ref,
        "ei_scenario_id" => $ei_scenario_id,
        "ei_version_id" => $ei_version_id,
        "ei_block_root_id" => $blockTravelled->getId(),
        "action" => "edit"
    ));

?>
<div class="panel panel-default eiPanel selectedColorBlock">
    <div class="panel-heading ">
        <h2>
            <?php echo ei_icon('ei_bloc' ) ?>
            <a itemref="<?php echo $urlBlockEdit ?>" ei_block="<?php echo $blockTravelled->getId() ?>" class="go_to_block_eiversion" title="Edit <?php echo $blockTravelled->getName() . " panel"; ?>">
                <?php echo $blockTravelled->getName() ?> 
                <?php

                /** @var EiBlockForeach $blockTravelled */
                if( $blockTravelled instanceof EiBlockForeach ){
                    echo "(FOREACH ".$blockTravelled->getIteratorMapping()->getEiDataSetStructureMapping()->getPath().")";
                }

                ?>
            </a>
        </h2>
        <div class="panel-actions">
            <?php if(isset($is_editable) && $is_editable): ?>
            <a class="btn-link block_delete" href="#block<?php echo $blockTravelled->getId(); ?>" data-toggle="modal">
                <?php echo ei_icon('ei_delete') ?>
            </a>
            <?php endif; ?>
            <a class="btn-minimize" href="#">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="panel-body ">
        <p class="mute"><?php echo $blockTravelled->getDescription(); ?></p>
    </div>

    <div id="block<?php echo $blockTravelled->getId(); ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Delete <?php echo $blockTravelled->getName(); ?></h3>
                </div>
                <div class="modal-body modal-body-visible-overflow">
                    <?php echo "You are about to delete block <strong>".$blockTravelled->getName()."</strong>.
                    All its children will be deleted as well as version's block content.<br/> Do you really want to delete block <strong>"
                        . $blockTravelled->getName() . "</strong> ?"; ?>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="btn" data-dismiss="modal">Cancel</a>
                    <a href="#!" ei_block="<?php echo $blockTravelled->getId()?>"
                       data-href="<?php echo url_for2("eiblock_delete", array('ei_block_id' => $blockTravelled->getId())) ?>"
                       class="delete_block btn btn-danger"> Delete </a>
                </div>
            </div>
        </div>
    </div>
</div>