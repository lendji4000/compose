<?php
$position = 0;
$position_sous_version = 0;

if(isset($chemin) && count($chemin) > 0)
    $cheminContent= $chemin->getRawValue();
else
    $cheminContent = array();

$cheminStr = EiVersionForm::getFormattedPathForUrl($cheminContent);

if (isset($form['fonctions']))
    $position = count($form['fonctions']);
if (isset($form['sous_versions']))
    $position_sous_version = count($form['sous_versions']);
?>

<form action="<?php echo url_for('version/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>

    <fieldset class="donnees_version">
        <?php echo $form->renderGlobalErrors(); ?>
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form['ei_scenario_id']->renderRow(); ?>
        <?php echo $form['libelle']->renderRow(); ?>
        <?php echo $form['description']->renderRow(); ?>
    </fieldset>
    <fieldset class="content">
        
        <?php
        echo 'Fonctions';
        if (isset($form['fonctions']))
            foreach ($form['fonctions'] as $key => $value) {
                echo '<fieldset class="fonction">';

                echo $value['libelle']->render();
                echo $value->renderHiddenFields();
                echo $value->renderError();

                echo link_to2('Add Param', 'ajouterParam', array('module' => 'fonction', 'position_fonction' => $key,
                    'position' => count($value['params'])));
                echo '<br/>';
                foreach ($value['params'] as $p => $param) {
                    echo $param['valeur']->render();
                    echo '<br/>';
                }
                echo '</fieldset>';
            }
        ?>
        
        <?php
        echo 'Versions';
        if (isset($form['sous_versions']))
            foreach ($form['sous_versions'] as $sv => $sous_version) {
                $cheminContent[] = $sv;
                include_partial('formContent', array('form' => $sous_version,
                                                    'version' => $form->getEmbeddedVersionForm($sv),
                                                    'chemin'=> $cheminContent));
               $cheminContent = array();
            }
        ?>
        
    </fieldset>
        
    <div>
        <a href="<?php echo url_for('@ajouterSousVersion?'.$cheminStr.'&position=' . $position_sous_version ); ?>" class="ajouter_sous_version" />
        <a href="<?php echo url_for('@ajouterFonction?'.$cheminStr.'&position=' . $position); ?>" class="ajouter_fonction_dans_scenario" />
        <input type="submit" value="Save" id="saveScenarioVersion" />
    </div>
    
</form>



