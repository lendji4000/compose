<h1>Ei views List</h1>

<table>
  <thead>
    <tr>
      <th>View</th>
      <th>View ref</th>
      <th>Project</th>
      <th>Project ref</th>
      <th>Description</th>
      <th>Is active</th>
      <th>Delta</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_views as $ei_view): ?>
    <tr>
      <td><a href="<?php echo url_for('view/edit?view_id='.$ei_view->getViewId().'&view_ref='.$ei_view->getViewRef()) ?>"><?php echo $ei_view->getViewId() ?></a></td>
      <td><a href="<?php echo url_for('view/edit?view_id='.$ei_view->getViewId().'&view_ref='.$ei_view->getViewRef()) ?>"><?php echo $ei_view->getViewRef() ?></a></td>
      <td><?php echo $ei_view->getProjectId() ?></td>
      <td><?php echo $ei_view->getProjectRef() ?></td>
      <td><?php echo $ei_view->getDescription() ?></td>
      <td><?php echo $ei_view->getIsActive() ?></td>
      <td><?php echo $ei_view->getDelta() ?></td>
      <td><?php echo $ei_view->getCreatedAt() ?></td>
      <td><?php echo $ei_view->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('view/new') ?>">New</a>
