<h1>Ei application objects List</h1>

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
    <?php foreach ($ei_application_objects as $ei_application_object): ?>
    <tr>
      <td><a href="<?php echo url_for('eiapplicationobject/edit?id='.$ei_application_object->getId()) ?>"><?php echo $ei_application_object->getId() ?></a></td>
      <td><?php echo $ei_application_object->getName() ?></td>
      <td><?php echo $ei_application_object->getProjectId() ?></td>
      <td><?php echo $ei_application_object->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eiapplicationobject/new') ?>">New</a>
