<div class="sortable" ei_block ="<?php echo $ei_block->getId()?>" data-href="<?php echo url_for2("eiblock_move", array('ei_block_id' => $ei_block->getId()))?>">
    <?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);
    $url_tab['ei_block_id']=$ei_block->getId();
    ?>
    <div class="row well well-sm">
        <a href="#block<?php echo $ei_block->getId(); ?>" tata-target="#block<?php echo $ei_block->getId(); ?>" 
           data-toggle="modal" class="pull-right">
            Delete
        </a>
        <div>
            <strong>
                
                <a href="<?php echo url_for2('eiblock_edit', $url_tab)?> " ei_block="<?php echo $ei_block->getId() ?>" class="go_to_block_eiscenario" title="Edit <?php echo  $ei_block->getName() . " panel";?>">
                    <?php echo $ei_block->getName() ?>
               </a>
            </strong>
        <p class="no-margin padding-left"><?php echo $ei_block->getDescription(); ?></p>
        </div>
         
        <!-- Modal -->
        <div class="modal " id="block<?php echo $ei_block->getId(); ?>"
             tabindex="-1" role="dialog"   aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Delete <?php echo $ei_block->getName(); ?>
                </h4>  
              </div>
              <div class="modal-body">
                <?php echo "You are about to delete block <strong>".$ei_block->getName()."</strong>. 
                    All its children will be deleted as well as version's block content.<br/> Do you really want to delete block <strong>" 
                        . $ei_block->getName() . "</strong> ?"; ?>
              </div>
              <div class="modal-footer">  
                <a href="#!" class="btn btn-default " data-dismiss="modal">Close</a>   
                <a href="#!" ei_block="<?php echo $ei_block->getId()?>" 
                   data-href="<?php echo url_for2("eiblock_delete", array('ei_block_id' => $ei_block->getId())) ?>" 
                   class="delete_block btn btn-danger"> Delete </a>
              </div>
            </div>
          </div>
        </div>
        
        
    </div>
<?php 
$insert_after_uri=$url_tab; 
$insert_after_uri['ei_scenario_id']=$ei_block->getEiScenarioId();
$insert_after_uri['ei_block_parent_id']=$ei_block_parent_id;
$insert_after_uri['insert_after']=$ei_block->getId();
?>
    <div class="row"> 
        <div class="col-lg-12 col-md-12 col-sm-12">
            <a href="<?php echo url_for2("eiblock_new", $insert_after_uri)?>" 
               class ="add_block"> 
                <?php echo ei_icon('ei_add','lg') ?>
            </a>
        </div>
    </div>
     


</div>