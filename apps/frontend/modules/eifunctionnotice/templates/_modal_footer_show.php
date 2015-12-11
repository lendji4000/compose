<!-- Menu du footer de la "modal box" après la surcharge d'une notice de fonction.
Elle permet de ne pas recharger toute la notice et donc de gagner en performances
-->
<div class="modal-footer"> 
    <a href="<?php
    echo url_for2('editFunctionNotice', array('lang' => $ei_function_notice->getLang(),
        'ei_fonction_id' => $ei_function_notice->getEiFonctionId(),
        'ei_version_id' => $ei_function_notice->getEiVersionId(),
        'profile_id' => $ei_profile->getProfileId(),
        'profile_ref' => $ei_profile->getProfileRef()))
    ?>"
       class="btn btn-sm btn-success" id="editFunctionNotice">Edit
    </a> 
    <a href="<?php
    echo url_for2('restoreDefaultFunctionNotice', array('lang' => $ei_function_notice->getLang(),
        'ei_fonction_id' => $ei_function_notice->getEiFonctionId(),
        'ei_version_id' => $ei_function_notice->getEiVersionId(),
        'profile_id' => $ei_profile->getProfileId(),
        'profile_ref' => $ei_profile->getProfileRef()))
    ?>"
       class="btn btn-sm btn-danger" id="restoreDefaultFunctionNotice">Restore default
    </a> 
    <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</a>
</div>
