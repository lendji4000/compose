<h1>Ei subject migrations List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Migration</th>
      <th>Subject</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_migrations as $ei_subject_migration): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjectmigration/show?id='.$ei_subject_migration->getId()) ?>"><?php echo $ei_subject_migration->getId() ?></a></td>
      <td><?php echo $ei_subject_migration->getMigration() ?></td>
      <td><?php echo $ei_subject_migration->getSubjectId() ?></td>
      <td><?php echo $ei_subject_migration->getCreatedAt() ?></td>
      <td><?php echo $ei_subject_migration->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjectmigration/new') ?>">New</a>
