<?php if(isset($ei_user_param) && $ei_user_param!=null): ?>
<tr class="userParamLine">
    <td><a href="<?php echo url_for('eiuserparam/edit?id=' . $ei_user_param->getId()) ?>"><?php echo $ei_user_param->getId() ?></a></td>
    <td><?php echo $ei_user_param->getUserRef() ?></td>
    <td><?php echo $ei_user_param->getUserId() ?></td>
    <td><?php echo $ei_user_param->getName() ?></td>
    <td><?php echo $ei_user_param->getDescription() ?></td>
    <td><?php echo $ei_user_param->getValue() ?></td>
    <td><?php echo $ei_user_param->getCreatedAt() ?></td>
    <td><?php echo $ei_user_param->getUpdatedAt() ?></td>
    <td>
        <a href="#" class="editUserParam">
            <i class="fa fa-pencil-square"></i>
        </a>
    </td>
</tr>
<?php endif; ?>