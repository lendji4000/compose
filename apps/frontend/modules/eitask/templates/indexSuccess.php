<h1>Ei tasks List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Author</th>
      <th>Task state</th>
      <th>Project</th>
      <th>Project ref</th>
      <th>Name</th>
      <th>Description</th>
      <th>Expected start date</th>
      <th>Expected end date</th>
      <th>Expected delay</th>
      <th>Expected duration</th>
      <th>To plan</th>
      <th>Plan start date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_tasks as $ei_task): ?>
    <tr>
      <td><a href="<?php echo url_for('eitask/edit?id='.$ei_task->getId()) ?>"><?php echo $ei_task->getId() ?></a></td>
      <td><?php echo $ei_task->getAuthorId() ?></td>
      <td><?php echo $ei_task->getTaskStateId() ?></td>
      <td><?php echo $ei_task->getProjectId() ?></td>
      <td><?php echo $ei_task->getProjectRef() ?></td>
      <td><?php echo $ei_task->getName() ?></td>
      <td><?php echo $ei_task->getDescription() ?></td>
      <td><?php echo $ei_task->getExpectedStartDate() ?></td>
      <td><?php echo $ei_task->getExpectedEndDate() ?></td>
      <td><?php echo $ei_task->getExpectedDelay() ?></td>
      <td><?php echo $ei_task->getExpectedDuration() ?></td>
      <td><?php echo $ei_task->getToPlan() ?></td>
      <td><?php echo $ei_task->getPlanStartDate() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitask/new') ?>">New</a>
