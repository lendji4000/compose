<!-- Barre de navigation supérieure d'un scénario-->
<?php

$chemin = !isset($chemin) ? "":$chemin;


/*****     INCLUSION MENU STRUCTURE
/**********************************************************************************************************************/

if (!isset($profile_id) || !isset($profile_ref)) {
    $profile_id = 0;
    $profile_ref = 0;
}

if (!isset($nom_profil) || $nom_profil == '')
    $nom_profil = "profil";
if (isset($ei_scenario))
    $projet = $ei_scenario->getEiProjet();

/*****     INCLUSION NAVIGATION ET FORMULAIRE
/**********************************************************************************************************************/

?>

<div class="span12 no-margin">
    <div class="form">
        <?php echo html_entity_decode($treeDisplay->render(), ENT_QUOTES, "UTF-8"); ?>
    </div>
</div>

<?php
/**
 * Box de recherche d'un jeu de données pour le player
 *
 * TODO: [editDataSetStepBox] Seconde occurrence
 */
?>
<div id="editDataSetStepBox" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="editDataSetStepBoxTitle" >Select data set for step</h3>
                <input type="hidden" id="editDataSetStepBoxLink" itemref="<?php echo "";//$urlToChooseDataSet ?>" />
            </div>
            <div class="modal-body" id="editDataSetStepBoxContent">

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">
                    Close
                </a>
                <input id="step_scenario_id" type="hidden" value="" />
            </div>
        </div>
    </div>
</div>