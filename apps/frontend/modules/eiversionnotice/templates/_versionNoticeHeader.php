<?php if(isset($ei_version_notice) && isset($noticeVersions) && isset($activesProfilesForNoticeVersion) && isset($project_langs)&& isset($default_notice_lang) ): ?>
        <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref,
        'profile_name' => $profile_name,
        "function_id" => $function_id,
        "function_ref" => $function_ref);
    ?>
<div class="panel panel-default eiPanel " id="versionNoticeHeader" > 
            <div class="panel-heading">

                <div class="form-group col-md-2 col-lg-2 col-sm-3"> 
                    <div class="input-group">
                        <div class="input-group-btn">
                            <h2><?php echo ei_icon('ei_notice') ?> <?php echo $ei_version_notice->getName() ?></h2>
                        </div> 
                        <?php if(isset($noticeVersions) && count($noticeVersions)>0):  ?>
                        <div class="input-group-btn">
                            <a data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button"><span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach($noticeVersions as $v): ?>
                                <?php if($ei_version_notice->getVersionNoticeId().$ei_version_notice->getNoticeId().$ei_version_notice->getNoticeRef()!=$v['version_notice_id'].$v['notice_id'].$v['notice_ref']): ?> 
                                <?php $itemuri=$url_tab; $itemuri['lang']=$default_notice_lang;  $itemuri['version_notice_id']=$v['version_notice_id'];
                                    $itemuri['notice_id']=$v['notice_id'];  $itemuri['notice_ref']=$v['notice_ref']; $itemuri['action']="edit";  ?>
                                <li>
                                    <a href="#" itemref="<?php echo url_for2("detailsNoticeActions",$itemuri) ?>" class="editVersionNotice">
                                    <?php echo ei_icon('ei_notice') ?> <?php echo $v['name'] ?>
                                    </a>
                                </li> 
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div> 
                </div>
                <ul id="versionNoticeLangs" class="nav nav-tabs"> 
                    <li>
                        <a aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle">
                            <?php echo ei_icon('ei_language') ?> <?php echo sfCultureInfo::getInstance()->getLanguage($ei_version_notice->getLang()) ?>
                            <span class="caret"></span>
                        </a>
                        <?php if(isset($project_langs) && count($project_langs)>1): ?>
                        <ul aria-labelledby="dropdownMenu4" class="dropdown-menu">
                            <?php foreach($project_langs as $lang): ?>
                            <?php if($ei_version_notice->getLang()!=$lang->getLang()): ?> 
                            <?php $itemuri=$url_tab; $itemuri['lang']=$lang->getLang();  $itemuri['version_notice_id']=$ei_version_notice->getVersionNoticeId();
                                    $itemuri['notice_id']=$ei_version_notice->getNoticeId();  $itemuri['notice_ref']=$ei_version_notice->getNoticeRef(); $itemuri['action']="edit";  ?>
                            <li>
                                <a href="#" itemref="<?php echo url_for2("detailsNoticeActions",$itemuri) ?>" class="editVersionNoticeLang">
                                    <?php echo ei_icon('ei_language') ?> <?php echo $lang ?>
                                </a>
                            </li> 
                            <?php endif; ?>
                            <?php endforeach; ?> 
                        </ul>
                        <?php endif; ?>
                    </li> 
                </ul> 
            </div> 
        <div class="panel-body clearfix">  
            <?php if(isset($activesProfilesForNoticeVersion) && count($activesProfilesForNoticeVersion)>0): ?>
            <?php foreach($activesProfilesForNoticeVersion as $actProfile):  ?> 
            <span class="label <?php echo((isset($actProfile['version_notice_id']) && $actProfile['version_notice_id']!=null)?'label-success':'label-default') ?>"
                  style="margin-left:5px; margin-right:5px;padding:3px;">
               <?php echo ei_icon('ei_profile') ?> <?php echo $actProfile['name'] ?>
            </span> 
            <?php endforeach; ?>
            <?php endif; ?>
        </div>	 
    </div>
<?php endif;  ?>