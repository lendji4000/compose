<h1>Ei folders List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Project</th>
      <th>Project ref</th>
      <th>Name</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_folders as $ei_folder): ?>
    <tr>
      <td><a href="<?php echo url_for('eifolder/edit?id='.$ei_folder->getId()) ?>"><?php echo $ei_folder->getId() ?></a></td>
      <td><?php echo $ei_folder->getProjectId() ?></td>
      <td><?php echo $ei_folder->getProjectRef() ?></td>
      <td><?php echo $ei_folder->getName() ?></td>
      <td><?php echo $ei_folder->getCreatedAt() ?></td>
      <td><?php echo $ei_folder->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eifolder/new') ?>">New</a>
