<?php

    $fleche = isset($fleche) ? html_entity_decode($fleche, ENT_QUOTES, "UTF-8"):'<i class="fa fa-arrow-left"></i>';
    $type = isset($type) ? $type:EiBlockDataSetMapping::$TYPE_IN;
    /** @var EiBlockParam $param */
    $param = isset($param) ? $param:new EiBlockParam();
    $urlToSelect = url_for2("eiblockdatasetmapping_select_mapping", array(
        'ei_block_param_id' => $param->getId(),
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'ei_scenario_id' => $ei_scenario_id
    ));
?>

<tr data-id="<?php echo $param->getId() ?>">
    <td>
        <?php echo $param->getName() ?>
    </td>
    <td>
        <?php echo $fleche; ?>
    </td>
    <td>
        <span class="mapping-name-slot">
        <?php
        /** @var EiBlockDataSetMapping $mapping */
        if( ($mapping = $param->getMapping($type)) != null ){
            echo $mapping->getEiDataSetStructureMapping()->getPath();
        }
        ?>
        </span>

        <?php echo html_entity_decode($tree->render($key), ENT_QUOTES, "UTF-8"); ?>
    </td>
</tr>