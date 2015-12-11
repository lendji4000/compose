<h1>Kal params List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Code fonction</th>
      <th>Type param</th>
      <th>Nom param</th>
      <th>Desc param</th>
      <th>Valeur defaut</th>
      <th>Est obligatoire</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($kal_params as $kal_param): ?>
    <tr>
      <td><a href="<?php echo url_for('kalparam/show?id='.$kal_param->getId()) ?>"><?php echo $kal_param->getId() ?></a></td>
      <td><?php echo $kal_param->getCodeFonction() ?></td>
      <td><?php echo $kal_param->getTypeParam() ?></td>
      <td><?php echo $kal_param->getNomParam() ?></td>
      <td><?php echo $kal_param->getDescParam() ?></td>
      <td><?php echo $kal_param->getValeurDefaut() ?></td>
      <td><?php echo $kal_param->getEstObligatoire() ?></td>
      <td><?php echo $kal_param->getCreatedAt() ?></td>
      <td><?php echo $kal_param->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('kalparam/new') ?>">New</a>
