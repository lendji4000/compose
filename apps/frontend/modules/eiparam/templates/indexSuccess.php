<h1>Liste des paramètres </h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Id fonction</th>
      <th>Id version</th>
      <th>Id scenario</th>
      <th>Nom param</th>
      <th>Valeur</th>
      <th>Observation</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_params as $ei_param): ?>
    <tr>
      <td><a href="<?php echo url_for('eiparam/edit?id='.$ei_param->getId()) ?>"><?php echo $ei_param->getId() ?></a></td>
      <td><?php echo $ei_param->getIdFonction() ?></td>
      <td><?php echo $ei_param->getEiVersionId() ?></td>
      <td><?php echo $ei_param->getEiScenarioId() ?></td>
      <td><?php echo $ei_param->getKalParam()->nom_param ?></td>
      <td><?php echo $ei_param->getValeur() ?></td>
      <td><?php echo $ei_param->getObservation() ?></td>
      <td><?php echo $ei_param->getCreatedAt() ?></td>
      <td><?php echo $ei_param->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eiparam/new') ?>">New</a>
