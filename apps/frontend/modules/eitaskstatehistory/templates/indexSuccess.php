<h1>Ei task state historys List</h1>

<table>
  <thead>
    <tr>
      <th>Task</th>
      <th>New state</th>
      <th>Date</th>
      <th>Author of change</th>
      <th>Last state</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_task_state_historys as $ei_task_state_history): ?>
    <tr>
      <td><a href="<?php echo url_for('eitaskstatehistory/edit?task_id='.$ei_task_state_history->getTaskId().'&new_state='.$ei_task_state_history->getNewState().'&date='.$ei_task_state_history->getDate()) ?>"><?php echo $ei_task_state_history->getTaskId() ?></a></td>
      <td><a href="<?php echo url_for('eitaskstatehistory/edit?task_id='.$ei_task_state_history->getTaskId().'&new_state='.$ei_task_state_history->getNewState().'&date='.$ei_task_state_history->getDate()) ?>"><?php echo $ei_task_state_history->getNewState() ?></a></td>
      <td><a href="<?php echo url_for('eitaskstatehistory/edit?task_id='.$ei_task_state_history->getTaskId().'&new_state='.$ei_task_state_history->getNewState().'&date='.$ei_task_state_history->getDate()) ?>"><?php echo $ei_task_state_history->getDate() ?></a></td>
      <td><?php echo $ei_task_state_history->getAuthorOfChange() ?></td>
      <td><?php echo $ei_task_state_history->getLastState() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitaskstatehistory/new') ?>">New</a>
