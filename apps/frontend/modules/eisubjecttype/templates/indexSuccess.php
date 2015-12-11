<h1>Ei subject types List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Project</th>
      <th>Project ref</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_types as $ei_subject_type): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjecttype/edit?id='.$ei_subject_type->getId()) ?>"><?php echo $ei_subject_type->getId() ?></a></td>
      <td><?php echo $ei_subject_type->getName() ?></td>
      <td><?php echo $ei_subject_type->getProjectId() ?></td>
      <td><?php echo $ei_subject_type->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjecttype/new') ?>">New</a>
