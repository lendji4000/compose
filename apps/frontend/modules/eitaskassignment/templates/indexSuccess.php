<h1>Ei task assignments List</h1>

<table>
  <thead>
    <tr>
      <th>Task</th>
      <th>Author</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_task_assignments as $ei_task_assignment): ?>
    <tr>
      <td><a href="<?php echo url_for('eitaskassignment/edit?task_id='.$ei_task_assignment->getTaskId().'&author_id='.$ei_task_assignment->getAuthorId()) ?>"><?php echo $ei_task_assignment->getTaskId() ?></a></td>
      <td><a href="<?php echo url_for('eitaskassignment/edit?task_id='.$ei_task_assignment->getTaskId().'&author_id='.$ei_task_assignment->getAuthorId()) ?>"><?php echo $ei_task_assignment->getAuthorId() ?></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitaskassignment/new') ?>">New</a>
