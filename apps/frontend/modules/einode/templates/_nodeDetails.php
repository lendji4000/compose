
<?php if( isset($ei_project) && isset($ei_profile) && isset($ei_node) && isset($node_parent)): ?>
<div class=" draggable " draggable="true">
    <input type="hidden" name="node_id" value="<?php echo $ei_node->getId(); ?>" class="node_id" />
    <input type="hidden" name="node_type" value="<?php echo $ei_node->getType(); ?>" class="node_type" />
    <div class="folder_detail bordered">
        <h6 class=" entete_fonction">
        <?php
            switch ($ei_node->getType()) {
                case "EiFolder":
                    echo ei_icon('ei_folder');
                    break;
                case "EiScenario":
                    echo ei_icon('ei_scenario');
                    break;
                default:
                    break;
            }
            echo '&nbsp;&nbsp;';
            echo $ei_node->getName();
        ?>
        </h6>
    </div>
</div>

<?php endif;?>