<h1>Ei subject priority historys List</h1>

<table>
  <thead>
    <tr>
      <th>Subject</th>
      <th>New priority</th>
      <th>Date</th>
      <th>Author of change</th>
      <th>Last priority</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_priority_historys as $ei_subject_priority_history): ?>
    <tr>
      <td><a href="<?php echo url_for('subjectpriorityhistory/edit?subject_id='.$ei_subject_priority_history->getSubjectId().'&new_priority='.$ei_subject_priority_history->getNewPriority().'&date='.$ei_subject_priority_history->getDate()) ?>"><?php echo $ei_subject_priority_history->getSubjectId() ?></a></td>
      <td><a href="<?php echo url_for('subjectpriorityhistory/edit?subject_id='.$ei_subject_priority_history->getSubjectId().'&new_priority='.$ei_subject_priority_history->getNewPriority().'&date='.$ei_subject_priority_history->getDate()) ?>"><?php echo $ei_subject_priority_history->getNewPriority() ?></a></td>
      <td><a href="<?php echo url_for('subjectpriorityhistory/edit?subject_id='.$ei_subject_priority_history->getSubjectId().'&new_priority='.$ei_subject_priority_history->getNewPriority().'&date='.$ei_subject_priority_history->getDate()) ?>"><?php echo $ei_subject_priority_history->getDate() ?></a></td>
      <td><?php echo $ei_subject_priority_history->getAuthorOfChange() ?></td>
      <td><?php echo $ei_subject_priority_history->getLastPriority() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('subjectpriorityhistory/new') ?>">New</a>
