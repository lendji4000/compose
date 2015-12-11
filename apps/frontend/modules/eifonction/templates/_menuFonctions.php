<ul class="elt_nav">
            <?php if(!is_null($ei_version)) :?>
    <li> <?php echo link_to1('Télécharger le scénario', 'eiscenario/download?ei_scenario_id='.$ei_scenario->id.'&id_version='.$ei_version->id)?></li>
            <li> <?php echo link_to1('Version des scénarios', 'eiversion/show?id='.$ei_version->id)  ?></li>
            <?php endif; ?>
            <?php if(!is_null($ei_scenario)) :?>
            <li> <?php echo link_to1('Scénario des fonctions', 'eiscenario/show?id='.$ei_scenario->id)  ?></li>
            <input type="hidden" name="ei_scenario_id" value="<?php echo $ei_scenario->id ?>" class="ei_scenario_id" />
            <?php endif; ?>
            <?php if(!is_null($ei_fonctions)) :?>
            <li> <?php echo link_to1('xml exemple php', '@ei_scenario_xml?sf_format=html&ei_scenario_id='.$ei_scenario->id.'&id_version='.$ei_version->id)  ?></li>
            <li> <?php echo link_to1(' xml exemple ', '@ei_scenario_xml?sf_format=xml&ei_scenario_id='.$ei_scenario->id.'&id_version='.$ei_version->id)  ?></li>
            <li> <?php echo link_to1('Jouer le scénario dans un robot ', 'eiscenario/playOnRobot?ei_scenario_id='.$ei_scenario->id.'&id_version='.$ei_version->id)  ?></li>
            <?php endif; ?>
        </ul>
        <div class="search_version_name">
                <b>Filtre des fonctions</b>
                <input type="text" name="recherche_version"  id="recherche_version" />
         </div>
        <div id="resultat_recherche_version">

        </div>
        <div class="list_versions">
            
            <span>
                <form>
                    <b>Versions </b>
                    <select id="liste_version_choice">
                        <option value=""></option>
                        <?php $versions=Doctrine_Core::getTable('EiVersion')->findBy('ei_scenario_id', $ei_scenario->id)?>
                        <?php foreach($versions as $version): ?>
                        <option value="<?php echo $version->getId() ?>"><?php echo $version->getLibelle()  ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </span>
        </div>
        <div class="profils">
            <b>Profils</b>
            
            <?php $profils_scenarios=Doctrine_Core::getTable('EiProfilScenario')->findByEiScenarioIdAndEiVersionId(
                    $ei_scenario->id,$ei_version->id); ?>
            <?php if($profils_scenarios!=null) :?>
            <?php foreach ($profils_scenarios as $profils_scenario) :   $profil=$profils_scenario->getEiProfil() ?>
                <b><?php echo $profil->profile_name; ?></b><br/>
                <?php endforeach; ?>
            <?php endif; ?>
                
            <?php
            $q=Doctrine_Query::create()->from('EiProfil p')
                    ->where('p.id NOT IN (SELECT ps.id_profil FROM EiProfilScenario as ps
                        WHERE ps.id_version= ? AND ps.ei_scenario_id= ?)',
                    array($ei_scenario->id,$ei_version->id));
            ?>
            <?php if($q->execute()->getFirst()) :?>

                <?php $profils=$q->execute();
                    if($profils_scenarios==null) :?>
            <form action="<?php echo url_for('eiprofilscenario/newProfilScenario?id_version='. $ei_version->id.'&ei_scenario_id='.$ei_scenario->id) ?>" method="POST">
            <?php else : ?>
            <form action="<?php echo url_for('eiprofilscenario/updateProfilScenario?id_version='. $ei_version->id.'&ei_scenario_id='.$ei_scenario->id) ?>" method="POST">
            <?php endif; ?>
                
                <?php foreach ($profils as $profil) :?>
                <?php echo $profil->profile_name; ?>
                <input type="checkbox" name="profils[]" value="<?php echo $profil->id  ?>" onclick="updateProfilScenario(this,<?php echo $ei_scenario->id?>,<?php echo $ei_version->id?>)" /><br/>
                <?php endforeach; ?>
                <input type="submit" value="Sauver" />
            </form>
            <?php endif; ?>

        </div>
