<h1>Ei subject prioritys List</h1>

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
    <?php foreach ($ei_subject_prioritys as $ei_subject_priority): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjectpriority/edit?id='.$ei_subject_priority->getId()) ?>"><?php echo $ei_subject_priority->getId() ?></a></td>
      <td><?php echo $ei_subject_priority->getName() ?></td>
      <td><?php echo $ei_subject_priority->getProjectId() ?></td>
      <td><?php echo $ei_subject_priority->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjectpriority/new') ?>">New</a>
