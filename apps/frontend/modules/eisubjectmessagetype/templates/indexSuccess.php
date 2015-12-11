<h1>Ei subject message types List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Project</th>
      <th>Project ref</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_subject_message_types as $ei_subject_message_type): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjectmessagetype/edit?id='.$ei_subject_message_type->getId()) ?>"><?php echo $ei_subject_message_type->getId() ?></a></td>
      <td><?php echo $ei_subject_message_type->getName() ?></td>
      <td><?php echo $ei_subject_message_type->getProjectId() ?></td>
      <td><?php echo $ei_subject_message_type->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjectmessagetype/new') ?>">New</a>
