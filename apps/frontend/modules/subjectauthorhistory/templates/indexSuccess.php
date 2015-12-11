<h1>Ei subject author historys List</h1>

<table>
  <thead>
    <tr>
      <th>Subject</th>
      <th>New author</th>
      <th>Date</th>
      <th>Author of change</th>
      <th>Last author</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_author_historys as $ei_subject_author_history): ?>
    <tr>
      <td><a href="<?php echo url_for('subjectauthorhistory/edit?subject_id='.$ei_subject_author_history->getSubjectId().'&new_author='.$ei_subject_author_history->getNewAuthor().'&date='.$ei_subject_author_history->getDate()) ?>"><?php echo $ei_subject_author_history->getSubjectId() ?></a></td>
      <td><a href="<?php echo url_for('subjectauthorhistory/edit?subject_id='.$ei_subject_author_history->getSubjectId().'&new_author='.$ei_subject_author_history->getNewAuthor().'&date='.$ei_subject_author_history->getDate()) ?>"><?php echo $ei_subject_author_history->getNewAuthor() ?></a></td>
      <td><a href="<?php echo url_for('subjectauthorhistory/edit?subject_id='.$ei_subject_author_history->getSubjectId().'&new_author='.$ei_subject_author_history->getNewAuthor().'&date='.$ei_subject_author_history->getDate()) ?>"><?php echo $ei_subject_author_history->getDate() ?></a></td>
      <td><?php echo $ei_subject_author_history->getAuthorOfChange() ?></td>
      <td><?php echo $ei_subject_author_history->getLastAuthor() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('subjectauthorhistory/new') ?>">New</a>
