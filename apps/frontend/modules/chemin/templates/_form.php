<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form)  ?>

<form action="<?php echo url_for('chemin/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('chemin/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'chemin/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['type_objet']->renderLabel() ?></th>
        <td>
          <?php echo $form['type_objet']->renderError() ?>
          <?php echo $form['type_objet'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['parent']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent']->renderError() ?>
          <?php echo $form['parent'] ?>
        </td>
      </tr>
    </tbody> 
  </table>
</form>
