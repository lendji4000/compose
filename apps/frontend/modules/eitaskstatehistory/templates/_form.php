<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('eitaskstatehistory/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?task_id='.$form->getObject()->getTaskId().'&new_state='.$form->getObject()->getNewState().'&date='.$form->getObject()->getDate() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('eitaskstatehistory/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'eitaskstatehistory/delete?task_id='.$form->getObject()->getTaskId().'&new_state='.$form->getObject()->getNewState().'&date='.$form->getObject()->getDate(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['author_of_change']->renderLabel() ?></th>
        <td>
          <?php echo $form['author_of_change']->renderError() ?>
          <?php echo $form['author_of_change'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['last_state']->renderLabel() ?></th>
        <td>
          <?php echo $form['last_state']->renderError() ?>
          <?php echo $form['last_state'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
