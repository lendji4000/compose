<ul class="elt_nav">
            <?php if(!is_null($ei_fonction)) :?>
            <li> <?php echo link_to1('Editer', 'eifonction/edit?id='.$ei_fonction->id)  ?></li>
            <li> <?php echo link_to1('Supprimer', 'eifonction/delete?id='.$ei_fonction->id)  ?></li>
            <li> <?php echo link_to1('Générer le code robot', 'eifonction/generateRobotCode?id='.$ei_fonction->id)  ?></li>
            <li> <?php echo link_to1(' jouer sur le robot', 'eifonction/playOnRobot?id='.$ei_fonction->id)  ?></li>
            <li> <?php echo link_to1(' Vers le scénario', 'eiscenario/show?id='.$ei_fonction->getEiScenario()->id)  ?></li>
            <li> <?php echo link_to1(' Retour à la liste', 'eifonction/index?ei_scenario_id='.$ei_fonction->ei_scenario_id.'&id_version='.$ei_fonction->id_version)  ?></li>
            <?php endif; ?>
        </ul>