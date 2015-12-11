<div id="functionNoticeModal" class="modal " tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content">
            <div class="modal-header  "> 
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <h3 id="functionNoticeLang">Notice Language</h3>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">
                                <?php echo sfCultureInfo::getInstance()->getLanguage($ei_version_notice->getLang()) ?>
                            </button> 
                        </div> 
                    </div> 
                    <div id="functionNoticeModalAlert" class="col-lg-5 col-md-5"> 
                    </div> 
                    <div class="col-lg-1 col-md-1"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div>  
                </div>

            </div>
            <?php  
            $url_form=  url_for2("editDefaultNotice", array(
                "profile_id" => $profile_id,
                "profile_ref" => $profile_ref,
                "notice_id" => $ei_version_notice->getNoticeId(),
                "notice_ref" => $ei_version_notice->getNoticeRef(),
                "version_notice_id" => $ei_version_notice->getVersionNoticeId(),
                "lang" => $ei_version_notice->getLang(),
                "ei_version_id" => $ei_version_id,
                "ei_fonction_id" => $ei_version_id,
                "action" => "updateDefaultNotice"
                ));
                    ?> 
            <div class="modal-body">  
                <?php include_partial('eifunctionnotice/form', 
                         array('form' => $form ,
                               'url_form' => $url_form
                             ))
                        ?>
            </div>
            <div class="modal-footer"> 
                <a href="#" class="btn btn-sm btn-success" id="saveDefaultNotice">Save default  </a> <i id="noticeLoader"  ></i>
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
            </div>
        </div>
    </div>
</div>

