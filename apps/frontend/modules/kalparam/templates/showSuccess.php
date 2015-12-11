<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $kal_param->getId() ?></td>
    </tr>
    <tr>
      <th>Code fonction:</th>
      <td><?php echo $kal_param->getCodeFonction() ?></td>
    </tr>
    <tr>
      <th>Type param:</th>
      <td><?php echo $kal_param->getTypeParam() ?></td>
    </tr>
    <tr>
      <th>Nom param:</th>
      <td><?php echo $kal_param->getNomParam() ?></td>
    </tr>
    <tr>
      <th>Desc param:</th>
      <td><?php echo $kal_param->getDescParam() ?></td>
    </tr>
    <tr>
      <th>Valeur defaut:</th>
      <td><?php echo $kal_param->getValeurDefaut() ?></td>
    </tr>
    <tr>
      <th>Est obligatoire:</th>
      <td><?php echo $kal_param->getEstObligatoire() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $kal_param->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $kal_param->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('kalparam/edit?id='.$kal_param->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('kalparam/index') ?>">List</a>
