<h1>Ei subject state historys List</h1>

<table>
  <thead>
    <tr>
      <th>Subject</th>
      <th>New state</th>
      <th>Date</th>
      <th>Author of change</th>
      <th>Last state</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_state_historys as $ei_subject_state_history): ?>
    <tr>
      <td><a href="<?php echo url_for('subjectstatehistory/edit?subject_id='.$ei_subject_state_history->getSubjectId().'&new_state='.$ei_subject_state_history->getNewState().'&date='.$ei_subject_state_history->getDate()) ?>"><?php echo $ei_subject_state_history->getSubjectId() ?></a></td>
      <td><a href="<?php echo url_for('subjectstatehistory/edit?subject_id='.$ei_subject_state_history->getSubjectId().'&new_state='.$ei_subject_state_history->getNewState().'&date='.$ei_subject_state_history->getDate()) ?>"><?php echo $ei_subject_state_history->getNewState() ?></a></td>
      <td><a href="<?php echo url_for('subjectstatehistory/edit?subject_id='.$ei_subject_state_history->getSubjectId().'&new_state='.$ei_subject_state_history->getNewState().'&date='.$ei_subject_state_history->getDate()) ?>"><?php echo $ei_subject_state_history->getDate() ?></a></td>
      <td><?php echo $ei_subject_state_history->getAuthorOfChange() ?></td>
      <td><?php echo $ei_subject_state_history->getLastState() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('subjectstatehistory/new') ?>">New</a>
