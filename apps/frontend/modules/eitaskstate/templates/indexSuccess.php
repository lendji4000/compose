<h1>Ei task states List</h1>

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
    <?php foreach ($ei_task_states as $ei_task_state): ?>
    <tr>
      <td><a href="<?php echo url_for('eitaskstate/edit?id='.$ei_task_state->getId()) ?>"><?php echo $ei_task_state->getId() ?></a></td>
      <td><?php echo $ei_task_state->getName() ?></td>
      <td><?php echo $ei_task_state->getProjectId() ?></td>
      <td><?php echo $ei_task_state->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitaskstate/new') ?>">New</a>
