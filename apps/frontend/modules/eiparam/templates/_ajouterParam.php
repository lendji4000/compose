<fieldset>
    <?php
    $sousVersionForm = $form->getSousVersionForm($index);
    echo $sousVersionForm['versionWidgets']['params'][$position]['nom_param'].' = ';
    echo $sousVersionForm['versionWidgets']['params'][$position]['valeur'];
    echo $sousVersionForm['versionWidgets']['params'][$position]['observation'];
    echo $sousVersionForm['versionWidgets']['params'][$position]['delete'];
    echo '<img src="/images/boutons/delete.png" alt="" class="param_delete" />';
    ?>
</fieldset>
