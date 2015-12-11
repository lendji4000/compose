<?php if(isset($ei_version_notice) && $ei_version_notice!=null): ?>
<div id="functionNoticeModal" class="modal " tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content">
            <?php include_partial('eifunctionnotice/functionModalHeader',array(
                'functionNoticeLangs'=>$functionNoticeLangs,
                'ei_version_id'=>$ei_version_id,
                'ei_fonction_id'=> $ei_fonction_id, 
                'profile_id' => $profile_id,
                'profile_ref' => $profile_ref,
                'current_lang'=>$ei_version_notice->getLang() //Langue courante
                    ))
                    ?>
            <div class="modal-body"> 
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Description </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_version_notice->getDescription()) ?>
                    </div>      
                </div>
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Expected </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_version_notice->getExpected()) ?>
                    </div>      
                </div>
                <div class="panel panel-default eiPanel">
                    <div class="panel-heading">
                        <h2> Result </h2> 
                    </div> 
                    <div class="panel-body">
                     <?php echo html_entity_decode($ei_version_notice->getResult()) ?>
                    </div>      
                </div>  
            </div>
            <div class="modal-footer"> 
                <a href="<?php echo url_for2('editDefaultNotice',
                        array('version_notice_id'=>$ei_version_notice->getVersionNoticeId(),
                              'notice_id' => $ei_version_notice->getNoticeId(),
                              'notice_ref' => $ei_version_notice->getNoticeRef(),
                              'lang' => $ei_version_notice->getLang(),
                              'ei_fonction_id' => $ei_fonction_id,
                              'ei_version_id' => $ei_version_id,
                              'profile_id' => $profile_id,
                              'profile_ref' => $profile_ref,
                              'action' => "editDefaultNotice")) ?>"
                    class="btn btn-sm btn-success" id="editDefaultNotice">Edit default
                </a> 
                <a href="<?php echo url_for2('newFunctionNotice',
                        array('version_notice_id'=>$ei_version_notice->getVersionNoticeId(),
                              'notice_id' => $ei_version_notice->getNoticeId(),
                              'notice_ref' => $ei_version_notice->getNoticeRef(),
                              'lang' => $ei_version_notice->getLang(),
                              'ei_fonction_id' => $ei_fonction_id,
                              'ei_version_id' => $ei_version_id,
                              'profile_id' => $profile_id,
                              'profile_ref' => $profile_ref)) ?>"
                    class="btn btn-sm btn-success" id="addFunctionNotice">Edit
                </a> 
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
            </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<?php endif; ?>

 