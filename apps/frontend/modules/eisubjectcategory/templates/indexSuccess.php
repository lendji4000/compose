<h1>Ei subject categorys List</h1>

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
    <?php foreach ($ei_subject_categorys as $ei_subject_category): ?>
    <tr>
      <td><a href="<?php echo url_for('eisubjectcategory/edit?id='.$ei_subject_category->getId()) ?>"><?php echo $ei_subject_category->getId() ?></a></td>
      <td><?php echo $ei_subject_category->getName() ?></td>
      <td><?php echo $ei_subject_category->getProjectId() ?></td>
      <td><?php echo $ei_subject_category->getProjectRef() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('eisubjectcategory/new') ?>">New</a>
