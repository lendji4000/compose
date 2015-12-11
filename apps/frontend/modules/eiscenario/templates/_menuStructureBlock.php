<?php if(isset($ei_scenario_id) && isset($ei_version_id)):
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'ei_scenario_id' =>  $ei_scenario_id,
        'ei_version_id' => $ei_version_id,
    );
  ?>
<ul class="ul_menu_version nav nav-list col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <li class="divider"></li>
    <li id="scenario_structure">
        <?php
        $html = '';
        $current_ei_block = $ei_blocks->getFirst();
        $current_ei_block_level = $ei_blocks->getFirst()->getLevel();
        $active_ei_block = isset($active_ei_block) ? $active_ei_block:$current_ei_block;
        $active_ei_block_level = $active_ei_block->getLevel();
        $counter = 0;
        $found = false;
        $nextSibling = false;

        foreach ($ei_blocks as $i => $ei_block):

            if ($current_ei_block !== false) {
                if ($ei_block['level'] == 0) {

                    if ($ei_block->getLft() <= $current_ei_block->getLft() && $ei_block->getRgt() >= $current_ei_block->getRgt()) {
                        // selected root item
                        $root = $ei_block;
                    }
                } else if (!isset($root)) {
                    // skip all items that are not under the selected root
                    continue;
                } else {
                    // when selected root is found

                    $isInRange = ($root->getLft() <= $ei_block->getLft() && $root->getRgt() >= $ei_block->getRgt());
                    if (!$isInRange) {
                        // skip all of the items that are not in range of the selected root
                        continue;
                    } else if ($current_ei_block->getLft() && $ei_block->getLft() == $current_ei_block->getLft()) {
                        // selected item reached
                        $found = true;
                        $current_ei_block = $ei_block;
                    } else if ($nextSibling !== false && $nextSibling->getLevel() < $ei_block->getLevel()) {

                        // if we have siblings after the selected item
                        // skip any other childerns in the same range or the selected root item
                        continue;
                    } else if ($found && $ei_block['level'] == $ei_block->getLevel()) {
                        // siblings after the selected item
                        $nextSibling = $ei_block;
                    }
                }
            } else if ($ei_block['level'] > $current_ei_block_level) {
                // show root items only if no childern is selected
                continue;
            }


            if ($ei_block['level'] == $current_ei_block_level) {
                if ($counter > 0)
                    $html .= '</li>';
            }
            elseif ($ei_block['level'] > $current_ei_block_level) {

                if($current_ei_block_level > $active_ei_block_level){
                    $open =  "hidden";
                }
                else {
                    $open =  "opened";
                }
                $html .= '<ul class="'. $open.'">';
                $current_ei_block_level = $current_ei_block_level + ($ei_block['level'] - $current_ei_block_level);
            } elseif ($ei_block['level'] < $current_ei_block_level) {
                $html .= str_repeat('</li></ul>', $current_ei_block_level - $ei_block['level']) . '</li>';
                $current_ei_block_level = $current_ei_block_level - ($current_ei_block_level - $ei_block['level']);
            }

            if($ei_block->getNode()->hasChildren()){
                $hidden= ' ';
                $padding= "";
            }
            else{
                $hidden ="hidden";
                $padding = "padding-left";
            }

            $html .= html_entity_decode($ei_block->createLiElem($url_tab, $block_redirect_class,($active_ei_block->getId() == $ei_block->getId()) ));
            ++$counter;
        endforeach;

        $html .= str_repeat('</li></ul>', $ei_block['level']) . '</li>';
        $html = '<ul class="sortable">' . $html . '</ul>';

        echo $html;
        ?>
    </li>

    <?php // PARTIE PERMETTANT D'AJOUTER DIRECTEMENT UN BLOCK SUR LA PAGE D'EDITION DE LA VERSION. ?>
    <?php if ($is_version && isset($is_editable) && $is_editable): ?>
        <li class="divider"></li>
        <li class="toUpperAndBold">
            <?php $eiblock_new_uri=$url_tab;  $eiblock_new_uri['ei_block_parent_id']=$current_ei_block->getId() ?>
            <a href="<?php echo url_for2("eiblock_new",$eiblock_new_uri) ?>" class="addBlockToVersion" title="Add a block into scenario.">
                 <?php echo ei_icon('ei_add','','','Node image' ) ?> Add Block
            </a>
        </li>
        <li class="toUpperAndBold">
            <?php $eiblock_new_uri2=$url_tab; 
            $eiblock_new_uri2['ei_block_parent_id']=$current_ei_block->getId();
             $eiblock_new_uri2['type']=EiVersionStructure::$TYPE_FOREACH ?>
            <a href="<?php echo url_for2("eiblock_new",$eiblock_new_uri2) ?>" class="addBlockToVersion" title="Add a block foreach into scenario.">
                 <?php echo ei_icon('ei_add','','','Node image' ) ?> Add Foreach
            </a>
        </li>
    <?php endif; ?>
</ul>
<?php endif; ?>