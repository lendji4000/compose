<?php
/**
 * Template d'affichage de l'édition d'un block.
 * 
 * @param $ei_block_root_form Le formulaire du block à afficher
 * @param $ei_block_parameters Les pramètres du block
 * @param $ei_block_children Les block enfants du block.
 * 
 */
?>
<?php $ei_block = $ei_block_root_form->getObject();  
 
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'ei_scenario_id' => $ei_scenario->getId());
?>
<?php
$projet_new_eiversion=$url_tab; 
$projet_new_eiversion['action']='edit';
?>
<?php $eiblockparam_new=$url_tab;
$eiblockparam_new['ei_block_id']= $ei_block->getId();
?>
<?php
$eiblock_new=$url_tab; 
$eiblock_new['ei_block_parent_id']=$ei_block->getId() ;

?>
<div class="panel panel-default eiPanel">
    <div class="panel-heading">

        <h2>Current Block / <strong><?php echo $ei_block ?></strong> </h2>
        <div class="panel-actions">

            <a href="<?php echo url_for2('projet_new_eiversion', $projet_new_eiversion);
                        ?>"title="Back to content ?">
                <i class="fa fa-times"></i>
            </a> 
        </div>
    </div>
    <div class="panel-body" id="current_block_form" >

        <?php
            $uri_form=$url_tab; $uri_form['form']=$ei_block_root_form;
            include_partial('block/form', $uri_form)
        ?>
    </div>
</div>

<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2>Parameters</h2>
        <div class="panel-actions">
            <a class="add_block_param" href="<?php echo url_for2('eiblockparam_new', $eiblockparam_new) ?>">
                <?php echo ei_icon('ei_add') ?>
            </a> 
        </div>
    </div>
    <div class="panel-body" id="block_parameters" >
        <?php
            if($ei_block_parameters):
                foreach($ei_block_parameters as $ei_block_param):
                    $uri_param=$url_tab;
                    $uri_param['form']=$ei_block_param;
                    include_partial('blockparam/form', $uri_param);
                endforeach;
            endif;
            ?>
    </div>
</div>
<!-- Block part-->
<div class="panel panel-default eiPanel">

    <div class="panel-heading">
        <h2>Blocks</h2>
        <div class="panel-actions"> 
        </div>
    </div>
    <div class="panel-body" id="children_blocks">
        <?php $url = url_for2("eiblock_new", $eiblock_new) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="<?php echo $url; ?>" class ="add_block">
                    <?php echo ei_icon('ei_add','lg') ?>
                </a>
            </div>
        </div>
        <?php
        if ($ei_block_children):
            foreach ($ei_block_children as $block):
                $uri_block = $url_tab;
                $uri_block['ei_block'] = $block;
                $uri_block['ei_block_parent_id'] = $ei_block->getId();
                include_partial('block/block', $uri_block);
            endforeach;
        endif;
            ?>
    </div>
</div>