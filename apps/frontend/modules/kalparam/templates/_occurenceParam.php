<table>
    <tr class="display_hidden"><td colspan="2"><?php echo $form->renderHiddenFields(true)  ?></td> </tr>
    <?php $kal_param=Doctrine_Core::getTable('KalParam')->findOneById($form['kal_param']->getValue())?>
    <tr>
        <td colspan="2"><?php echo $form->renderError()  ?></td> </tr>
    <?php $kal_param=Doctrine_Core::getTable('KalParam')->findOneById($form['kal_param']->getValue())?>
    <tr>
        <td class="nom_parametre">
            <?php if($kal_param!=null):?>
            <?php echo $kal_param ?>
            <?php endif;?>
        </td>
        <td><?php echo $form['valeur'] ?></td>
        <td class="details_param_observation"><?php echo $form['observation'] ?></td>
    </tr>
</table>
