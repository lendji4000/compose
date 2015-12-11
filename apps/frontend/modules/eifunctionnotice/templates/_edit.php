<div id="functionNoticeModal" class="modal " tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content">
            <?php include_partial('eifunctionnotice/functionModalHeader',array(
                'functionNoticeLangs'=>$functionNoticeLangs,
                'ei_version_id'=>$ei_version_id,
                'ei_fonction_id'=> $ei_fonction_id, 
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'current_lang'=>$form->getObject()->getLang() //Langue courante
                    ))
                    ?>
            <div class="modal-body">  
                <?php include_partial('eifunctionnotice/form', 
                         array('form' => $form ,
                               'url_form' => $url_form
                             ))
                        ?>
            </div>
            <div class="modal-footer"> 
                <a href="#" class="btn btn-sm btn-success" id="saveFunctionNotice">Save </a>
                <a href="<?php echo url_for2('restoreDefaultFunctionNotice',
                        array('lang' => $form->getObject()->getLang(),
                              'ei_fonction_id' => $ei_fonction_id,
                              'ei_version_id' => $ei_version_id,
                              'profile_id'  => $profile_id,
                              'profile_ref' => $profile_ref)) ?>"
                    class="btn btn-sm btn-danger" id="restoreDefaultFunctionNotice">Restore default
                </a> 
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
            </div>
        </div>
    </div>
</div>