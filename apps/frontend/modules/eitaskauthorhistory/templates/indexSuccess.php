<h1>Ei task author historys List</h1>

<table>
  <thead>
    <tr>
      <th>Task</th>
      <th>New author</th>
      <th>Date</th>
      <th>Author of change</th>
      <th>Last author</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_task_author_historys as $ei_task_author_history): ?>
    <tr>
      <td><a href="<?php echo url_for('eitaskauthorhistory/edit?task_id='.$ei_task_author_history->getTaskId().'&new_author='.$ei_task_author_history->getNewAuthor().'&date='.$ei_task_author_history->getDate()) ?>"><?php echo $ei_task_author_history->getTaskId() ?></a></td>
      <td><a href="<?php echo url_for('eitaskauthorhistory/edit?task_id='.$ei_task_author_history->getTaskId().'&new_author='.$ei_task_author_history->getNewAuthor().'&date='.$ei_task_author_history->getDate()) ?>"><?php echo $ei_task_author_history->getNewAuthor() ?></a></td>
      <td><a href="<?php echo url_for('eitaskauthorhistory/edit?task_id='.$ei_task_author_history->getTaskId().'&new_author='.$ei_task_author_history->getNewAuthor().'&date='.$ei_task_author_history->getDate()) ?>"><?php echo $ei_task_author_history->getDate() ?></a></td>
      <td><?php echo $ei_task_author_history->getAuthorOfChange() ?></td>
      <td><?php echo $ei_task_author_history->getLastAuthor() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eitaskauthorhistory/new') ?>">New</a>
