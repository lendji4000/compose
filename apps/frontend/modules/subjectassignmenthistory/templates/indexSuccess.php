<h1>Ei subject assignment historys List</h1>

<table>
  <thead>
    <tr>
      <th>Subject</th>
      <th>Author of assignment</th>
      <th>Assign to</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_assignment_historys as $ei_subject_assignment_history): ?>
    <tr>
      <td><a href="<?php echo url_for('subjectassignmenthistory/edit?subject_id='.$ei_subject_assignment_history->getSubjectId().'&author_of_assignment='.$ei_subject_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_subject_assignment_history->getAssignTo().'&date='.$ei_subject_assignment_history->getDate()) ?>"><?php echo $ei_subject_assignment_history->getSubjectId() ?></a></td>
      <td><a href="<?php echo url_for('subjectassignmenthistory/edit?subject_id='.$ei_subject_assignment_history->getSubjectId().'&author_of_assignment='.$ei_subject_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_subject_assignment_history->getAssignTo().'&date='.$ei_subject_assignment_history->getDate()) ?>"><?php echo $ei_subject_assignment_history->getAuthorOfAssignment() ?></a></td>
      <td><a href="<?php echo url_for('subjectassignmenthistory/edit?subject_id='.$ei_subject_assignment_history->getSubjectId().'&author_of_assignment='.$ei_subject_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_subject_assignment_history->getAssignTo().'&date='.$ei_subject_assignment_history->getDate()) ?>"><?php echo $ei_subject_assignment_history->getAssignTo() ?></a></td>
      <td><a href="<?php echo url_for('subjectassignmenthistory/edit?subject_id='.$ei_subject_assignment_history->getSubjectId().'&author_of_assignment='.$ei_subject_assignment_history->getAuthorOfAssignment().'&assign_to='.$ei_subject_assignment_history->getAssignTo().'&date='.$ei_subject_assignment_history->getDate()) ?>"><?php echo $ei_subject_assignment_history->getDate() ?></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('subjectassignmenthistory/new') ?>">New</a>
