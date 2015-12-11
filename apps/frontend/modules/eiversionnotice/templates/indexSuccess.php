<?php if(isset($kal_function)  && isset($ei_project) && isset($ei_profile) && isset($current_notice_version)):?>
<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
         "function_id" => $kal_function->getFunctionId(),
         "function_ref" => $kal_function->getFunctionRef());
$cv=$current_notice_version;
?>

<div id="functionNotices">
    <div class="row"><i id="noticeLoaderNotice" ></i></div>
    <!--En tête de l'édition de la notice-->
    <?php  $versionNoticeHeaderparams = $url_tab;
        $versionNoticeHeaderparams['ei_version_notice'] = $ei_version_notice;
        $versionNoticeHeaderparams['noticeVersions'] = $noticeVersionsForDropdownList;
        $versionNoticeHeaderparams['project_langs'] = $project_langs;
        $versionNoticeHeaderparams['activesProfilesForNoticeVersion'] = $activesProfilesForNoticeVersion;
        $versionNoticeHeaderparams['default_notice_lang'] = $ei_project->getDefaultNoticeLang();
        include_partial("versionNoticeHeader", $versionNoticeHeaderparams)
        ?>
        <!--Formulaire d'edition des différents champs de la notice-->
        <?php
        $url_form = $url_tab;
        $url_form['version_notice_id'] = $cv['version_notice_id'];
        $url_form['notice_id'] = $cv['notice_id'];
        $url_form['notice_ref'] = $cv['notice_ref'];
        $url_form['lang'] = $cv['lang'];
        $url_form['action'] = "update";
        include_partial('blockForm', array('form' => $form,
            'url_form' => $url_form,
            'inParameters' => $inTabParameters,
            'outParameters' => $outTabParameters));
        ?>
</div>

<?php endif; ?>