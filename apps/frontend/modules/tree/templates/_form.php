<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('tree/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('tree/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'tree/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['type']->renderLabel() ?></th>
        <td>
          <?php echo $form['type']->renderError() ?>
          <?php echo $form['type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['obj_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['obj_id']->renderError() ?>
          <?php echo $form['obj_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ref_obj']->renderLabel() ?></th>
        <td>
          <?php echo $form['ref_obj']->renderError() ?>
          <?php echo $form['ref_obj'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['is_root']->renderLabel() ?></th>
        <td>
          <?php echo $form['is_root']->renderError() ?>
          <?php echo $form['is_root'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['project_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['project_id']->renderError() ?>
          <?php echo $form['project_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['project_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['project_ref']->renderError() ?>
          <?php echo $form['project_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['position']->renderLabel() ?></th>
        <td>
          <?php echo $form['position']->renderError() ?>
          <?php echo $form['position'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['root_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['root_id']->renderError() ?>
          <?php echo $form['root_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lft']->renderLabel() ?></th>
        <td>
          <?php echo $form['lft']->renderError() ?>
          <?php echo $form['lft'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['rgt']->renderLabel() ?></th>
        <td>
          <?php echo $form['rgt']->renderError() ?>
          <?php echo $form['rgt'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['level']->renderLabel() ?></th>
        <td>
          <?php echo $form['level']->renderError() ?>
          <?php echo $form['level'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['delta']->renderLabel() ?></th>
        <td>
          <?php echo $form['delta']->renderError() ?>
          <?php echo $form['delta'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['created_at']->renderLabel() ?></th>
        <td>
          <?php echo $form['created_at']->renderError() ?>
          <?php echo $form['created_at'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['updated_at']->renderLabel() ?></th>
        <td>
          <?php echo $form['updated_at']->renderError() ?>
          <?php echo $form['updated_at'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
