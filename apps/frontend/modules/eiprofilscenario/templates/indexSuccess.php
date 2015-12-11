<h1>Ei profil scenarios List</h1>

<table>
  <thead>
    <tr>
      <th>Id profil</th>
      <th>Id scenario</th>
      <th>Id version</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_profil_scenarios as $ei_profil_scenario): ?>
    <tr>
      <td><a href="<?php echo url_for('eiprofilscenario/edit?id_profil='.$ei_profil_scenario->getIdProfil().'&ei_scenario_id='.$ei_profil_scenario->getEiScenarioId()) ?>"><?php echo $ei_profil_scenario->getIdProfil() ?></a></td>
      <td><a href="<?php echo url_for('eiprofilscenario/edit?id_profil='.$ei_profil_scenario->getIdProfil().'&ei_scenario_id='.$ei_profil_scenario->getEiScenarioId()) ?>"><?php echo $ei_profil_scenario->getEiScenarioId() ?></a></td>
      <td><?php echo $ei_profil_scenario->getEiVersionId() ?></td>
      <td><?php echo $ei_profil_scenario->getCreatedAt() ?></td>
      <td><?php echo $ei_profil_scenario->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eiprofilscenario/new') ?>">New</a>
