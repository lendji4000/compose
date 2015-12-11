<table>
  <tbody>
    <tr>
      <th>Param:</th>
      <td><?php echo $ei_function_has_param->getParamId() ?></td>
    </tr>
    <tr>
      <th>Function ref:</th>
      <td><?php echo $ei_function_has_param->getFunctionRef() ?></td>
    </tr>
    <tr>
      <th>Function:</th>
      <td><?php echo $ei_function_has_param->getFunctionId() ?></td>
    </tr>
    <tr>
      <th>Param type:</th>
      <td><?php echo $ei_function_has_param->getParamType() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $ei_function_has_param->getName() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $ei_function_has_param->getDescription() ?></td>
    </tr>
    <tr>
      <th>Default value:</th>
      <td><?php echo $ei_function_has_param->getDefaultValue() ?></td>
    </tr>
    <tr>
      <th>Is compulsory:</th>
      <td><?php echo $ei_function_has_param->getIsCompulsory() ?></td>
    </tr>
    <tr>
      <th>Delta:</th>
      <td><?php echo $ei_function_has_param->getDelta() ?></td>
    </tr>
    <tr>
      <th>Deltaf:</th>
      <td><?php echo $ei_function_has_param->getDeltaf() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ei_function_has_param->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ei_function_has_param->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('eifuncttionparams/edit?param_id='.$ei_function_has_param->getParamId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('eifuncttionparams/index') ?>">List</a>
