<?php if($ei_scenario!=null && $ei_version!=null) : ?>
<?php $q=doctrine_core::getTable('EiProfilScenario')->getProfilScenarioByCriteria(
        null,$ei_scenario->id,$ei_version->id,null,null);
         ?>
<?php if($q!=null && $q->execute()->getFirst()) :?>
<ul>
   <?php $profils_scenarios=$q->execute(); foreach ($profils_scenarios as $profils_scenario) :?>
    <li><?php echo $profils_scenario->getEiProfil()->getNomProfil() ?></li>
<?php endforeach; ?> 
</ul>
<?php endif; ?>
<?php endif; ?>
<?php echo link_to1('+profil', 'eiprofilscenario/new?ei_scenario_id='.$ei_scenario->id.'&id_version='.$ei_version->id) ?>