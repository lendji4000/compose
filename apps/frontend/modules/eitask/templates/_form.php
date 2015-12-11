<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('eitask/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('eitask/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'eitask/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['author_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['author_id']->renderError() ?>
          <?php echo $form['author_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['task_state_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['task_state_id']->renderError() ?>
          <?php echo $form['task_state_id'] ?>
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
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['description']->renderLabel() ?></th>
        <td>
          <?php echo $form['description']->renderError() ?>
          <?php echo $form['description'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expected_start_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expected_start_date']->renderError() ?>
          <?php echo $form['expected_start_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expected_end_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['expected_end_date']->renderError() ?>
          <?php echo $form['expected_end_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expected_delay']->renderLabel() ?></th>
        <td>
          <?php echo $form['expected_delay']->renderError() ?>
          <?php echo $form['expected_delay'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expected_duration']->renderLabel() ?></th>
        <td>
          <?php echo $form['expected_duration']->renderError() ?>
          <?php echo $form['expected_duration'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['to_plan']->renderLabel() ?></th>
        <td>
          <?php echo $form['to_plan']->renderError() ?>
          <?php echo $form['to_plan'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['plan_start_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['plan_start_date']->renderError() ?>
          <?php echo $form['plan_start_date'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
