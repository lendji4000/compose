<?php if(isset($ei_function_notice) && $ei_function_notice!=null): ?> 
<div id="functionNoticeModal" class="modal " tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content" >
            <?php include_partial('eifunctionnotice/functionModalHeader',array(
                'functionNoticeLangs'=>$functionNoticeLangs,
                'ei_version_id'=>$ei_version_id,
                'ei_fonction_id'=> $ei_fonction_id, 
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'current_lang'=>$ei_function_notice->getLang() //Langue courante
                ))
                    ?>
            <div class="modal-body"> 
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Description </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_function_notice->getDescription()) ?>
                    </div>      
                </div>
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Expected </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_function_notice->getExpected()) ?>
                    </div>      
                </div>
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Result </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_function_notice->getResult()) ?>
                    </div>      
                </div> 
            </div>
            <div class="modal-footer"> 
                <a href="<?php echo url_for2('editFunctionNotice',
                        array('lang' => $ei_function_notice->getLang(),
                              'ei_fonction_id' => $ei_function_notice->getEiFonctionId(),
                              'ei_version_id' => $ei_function_notice->getEiVersionId(),
                              'profile_id' => $profile_id,
                              'profile_ref' => $profile_ref)) ?>"
                    class="btn btn-sm btn-success" id="editFunctionNotice">Edit
                </a> 
                <a href="<?php echo url_for2('restoreDefaultFunctionNotice',
                        array('lang' => $ei_function_notice->getLang(),
                              'ei_fonction_id' => $ei_function_notice->getEiFonctionId(),
                              'ei_version_id' => $ei_function_notice->getEiVersionId(),
                              'profile_id'  => $profile_id,
                              'profile_ref' => $profile_ref)) ?>"
                    class="btn btn-sm btn-danger" id="restoreDefaultFunctionNotice">Restore default
                </a> 
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
            </div>
        </div>
</div>
</div>
<?php endif; ?>