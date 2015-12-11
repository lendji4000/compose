<table>
  <tbody>
    <tr>
      <th>Version notice:</th>
      <td><?php echo $ei_version_notice->getVersionNoticeId() ?></td>
    </tr>
    <tr>
      <th>Notice:</th>
      <td><?php echo $ei_version_notice->getNoticeId() ?></td>
    </tr>
    <tr>
      <th>Notice ref:</th>
      <td><?php echo $ei_version_notice->getNoticeRef() ?></td>
    </tr>
    <tr>
      <th>Lang:</th>
      <td><?php echo $ei_version_notice->getLang() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $ei_version_notice->getName() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $ei_version_notice->getDescription() ?></td>
    </tr>
    <tr>
      <th>Expected:</th>
      <td><?php echo $ei_version_notice->getExpected() ?></td>
    </tr>
    <tr>
      <th>Result:</th>
      <td><?php echo $ei_version_notice->getResult() ?></td>
    </tr>
    <tr>
      <th>Is active:</th>
      <td><?php echo $ei_version_notice->getIsActive() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ei_version_notice->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ei_version_notice->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eiversionnotice/edit?version_notice_id='.$ei_version_notice->getVersionNoticeId().'&notice_id='.$ei_version_notice->getNoticeId().'&notice_ref='.$ei_version_notice->getNoticeRef().'&lang='.$ei_version_notice->getLang()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('eiversionnotice/index') ?>">List</a>
