<h1>Ei task assignment historys List</h1>

<table>
  <thead>
    <tr>
      <th>Task</th>
      <th>Author of assignment</th>
      <th>Assign to</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_task_assignment_historys as $ei_task_assignment_history): ?>
    <tr>
      <td><a href="<?php echo url_for('eitaskassignmenthistory/edit?task_id='.$ei_task_assignment_history->getTaskId().'&author_of_assignment='.$ei_task_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_task_assignment_history->getAssignTo().'&date='.$ei_task_assignment_history->getDate()) ?>"><?php echo $ei_task_assignment_history->getTaskId() ?></a></td>
      <td><a href="<?php echo url_for('eitaskassignmenthistory/edit?task_id='.$ei_task_assignment_history->getTaskId().'&author_of_assignment='.$ei_task_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_task_assignment_history->getAssignTo().'&date='.$ei_task_assignment_history->getDate()) ?>"><?php echo $ei_task_assignment_history->getAuthorOfAssignment() ?></a></td>
      <td><a href="<?php echo url_for('eitaskassignmenthistory/edit?task_id='.$ei_task_assignment_history->getTaskId().'&author_of_assignment='.$ei_task_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_task_assignment_history->getAssignTo().'&date='.$ei_task_assignment_history->getDate()) ?>"><?php echo $ei_task_assignment_history->getAssignTo() ?></a></td>
      <td><a href="<?php echo url_for('eitaskassignmenthistory/edit?task_id='.$ei_task_assignment_history->getTaskId().'&author_of_assignment='.$ei_task_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_task_assignment_history->getAssignTo().'&date='.$ei_task_assignment_history->getDate()) ?>"><?php echo $ei_task_assignment_history->getDate() ?></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitaskassignmenthistory/new') ?>">New</a>
