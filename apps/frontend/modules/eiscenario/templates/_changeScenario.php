<form  >
    <?php echo link_to2('Scénarios', 'projet_eiscenario', array('id_projet' => $sf_request->getParameter('id_projet'), 'action' => 'index')); ?>
    <select id="liste_scenario_choice">
        <?php if (isset($id_projet) && $id_projet!=null): ?>
        <?php $scenarios = Doctrine_Core::getTable('EiScenario')->findBy('id_projet', $id_projet) ?>
        <?php foreach ($scenarios as $scenario): ?>
            <option value="<?php echo $scenario->getId() ?>"
                    <?php if ($ei_scenario_id && $scenario->id == $ei_scenario_id): ?> selected="" <?php endif; ?> >
                        <?php echo $scenario->getNomScenario() ?>
            </option>
        <?php endforeach; ?>
            <?php endif; ?>
    </select>
</form>
