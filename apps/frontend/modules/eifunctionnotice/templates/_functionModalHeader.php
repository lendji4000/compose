
<div class="modal-header  "> 
    <div class="row">
        <div class="col-lg-2 col-md-2">
            <h3 id="functionNoticeLang">Notice</h3>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="btn-group">
                <button type="button" class="btn btn-default">
                    <?php echo sfCultureInfo::getInstance()->getLanguage($current_lang) ?>
                </button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span> 
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach ($functionNoticeLangs as $lang): ?>
                        <?php if ($lang->getLang() != $current_lang): ?>  
                            <li>
                                <a href="<?php
                                echo url_for2('showFunctionNotice', array('ei_version_id' => $ei_version_id,
                                    'ei_fonction_id' => $ei_fonction_id,
                                    'lang' => $lang->getLang(),
                                    'profile_id' => $profile_id,
                                    'profile_ref' => $profile_ref))
                                ?>" 
                                   class="showFunctionNotice"><?php echo $lang->printLang() ?>
                                </a>
                            </li> 
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div> 
        </div> 
        <div id="functionNoticeModalAlert" class="col-lg-5 col-md-5"> 
        </div> 
        <div class="col-lg-1 col-md-1"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div>  
    </div>

</div>
