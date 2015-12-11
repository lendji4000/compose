<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('eiparam/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('eiparam/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'eiparam/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <td>
          <?php echo $form['id_fonction']->renderError() ?>
          <?php echo $form['id_fonction'] ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $form['id_version']->renderError() ?>
          <?php echo $form['id_version'] ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $form['ei_scenario_id']->renderError() ?>
          <?php echo $form['ei_scenario_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['kal_param']->renderLabel() ?></th>
        <td>
          <?php echo $form['kal_param']->renderError() ?>
          <?php echo $form['kal_param'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['valeur']->renderLabel() ?></th>
        <td>
          <?php echo $form['valeur']->renderError() ?>
          <?php echo $form['valeur'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['observation']->renderLabel() ?></th>
        <td>
          <?php echo $form['observation']->renderError() ?>
          <?php echo $form['observation'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
