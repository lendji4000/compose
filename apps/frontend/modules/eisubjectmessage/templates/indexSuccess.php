<h1>Ei subject messages List</h1>

<table>
  <thead>
    <tr>
      <th>Guard</th>
      <th>Subject</th>
      <th>Message type</th>
      <th>Message</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_messages as $ei_subject_message): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjectmessage/edit?guard_id='.$ei_subject_message->getGuardId().'&subject_id='.$ei_subject_message->getSubjectId()) ?>"><?php echo $ei_subject_message->getGuardId() ?></a></td>
      <td><a href="<?php echo url_for('eisubjectmessage/edit?guard_id='.$ei_subject_message->getGuardId().'&subject_id='.$ei_subject_message->getSubjectId()) ?>"><?php echo $ei_subject_message->getSubjectId() ?></a></td>
      <td><?php echo $ei_subject_message->getMessageTypeId() ?></td>
      <td><?php echo $ei_subject_message->getMessage() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjectmessage/new') ?>">New</a>
