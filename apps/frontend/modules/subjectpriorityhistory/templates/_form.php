<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('subjectpriorityhistory/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?subject_id='.$form->getObject()->getSubjectId().'&new_priority='.$form->getObject()->getNewPriority().'&date='.$form->getObject()->getDate() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('subjectpriorityhistory/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'subjectpriorityhistory/delete?subject_id='.$form->getObject()->getSubjectId().'&new_priority='.$form->getObject()->getNewPriority().'&date='.$form->getObject()->getDate(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
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
        <th><?php echo $form['last_priority']->renderLabel() ?></th>
        <td>
          <?php echo $form['last_priority']->renderError() ?>
          <?php echo $form['last_priority'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
