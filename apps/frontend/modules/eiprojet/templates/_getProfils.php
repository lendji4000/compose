<!--_getProfils-->
<?php
$tabparam = array(
    'project_id' => $ei_project->getProjectId(),
    'project_ref' => $ei_project->getRefId());

if (isset($ei_scenario)):
    $tabparam['ei_scenario_id'] = $ei_scenario->getId();
    $tabparam['action'] = 'edit';
    $route = 'projet_new_eiversion';
else:
    $tabparam = array(
        'project_id' => $ei_project->getProjectId(),
        'project_ref' => $ei_project->getRefId()
    );
    $route = 'projet_list_show';
endif;

// Style de menu : dropdown ou dropup.
$styleMenu = isset($styleMenu) ? $styleMenu:"dropdown";
$fullMode = isset($fullMode) ? "col-lg-12 col-md-12":"";

?>

<?php if ($ei_profile != null): ?> 
    <?php $profile_id=$ei_profile->getProfileId(); ?>
    <?php $profile_ref=$ei_profile->getProfileRef(); ?>
    <?php $profile_name=$ei_profile->getName(); ?>
    <input type="hidden" id="profile_id" name="profile_id" value="<?php echo $profile_id ?>" />
    <input type="hidden" id="profile_ref" name="profile_ref" value="<?php echo $profile_ref?>" />
    <input type="hidden" id="profile_name" name="profile_name" value="<?php echo $profile_name ?>" />
<?php endif; ?>
        
<!-- Menu de sélection des profils -->
<li class="<?php echo $styleMenu; ?> visible-md visible-lg <?php echo $fullMode ?>">
    <a href="<?php echo $sf_request->getUri() ?>#" id="profilesHeaderLink" class="dropdown-toggle" data-toggle="dropdown" title="<?php echo $ei_profile->getName()?>"> 
        <?php echo ei_icon('ei_profile',null,'profilesHeaderLinkImg') ?>
        <?php
        if (isset($ei_profile) && $ei_profile != null):
            echo MyFunction::troncatedText($ei_profile->getName() , 25);
        else:
            echo 'Erreur interne.';
        endif;
        ?>
        <!--<b class="caret bottom-up pull-right"></b>-->
    </a> 
    <ul class="dropdown-menu <?php echo $fullMode ?>">
        <?php foreach ($profils as $profil): ?>
            <?php
            $tabparam['profile_id'] = $profil->getProfileId();
            $tabparam['profile_ref'] = $profil->getProfileRef();
            $tabparam['profile_name'] = $profil->getName()
            ?>
        <?php if($profile_id!=$tabparam['profile_id'] || $profile_ref!=$tabparam['profile_ref'] ): ?>
            <li class="profile_item <?php echo $fullMode ?>">
                <a href=" <?php echo url_for2("profil_forwardTo", $tabparam); ?>"  title="<?php echo $profil->getName()?>"> 
                    <?php echo ei_icon('ei_profile',null,'alignment_img') ?>
                    <span> 
                        <?php echo MyFunction::troncatedText($profil->getName() , 25); ?>
                    </span>
                </a>
            </li> 
       <?php endif; ?>     
<?php endforeach; ?> 
    </ul>
</li>




