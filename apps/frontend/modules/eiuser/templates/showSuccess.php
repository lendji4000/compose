<table>
  <tbody>
    <tr>
      <th>Project:</th>
      <td><?php echo $ei_user_settings->getProjectId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ei_user_settings->getUserId() ?></td>
    </tr>
    <tr>
      <th>Firefox path:</th>
      <td><?php echo $ei_user_settings->getFirefoxPath() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ei_user_settings->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ei_user_settings->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eiuser/edit?project_id='.$ei_user_settings->getProjectId().'&user_id='.$ei_user_settings->getUserId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('eiuser/index') ?>">List</a>
