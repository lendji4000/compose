<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('pack/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id_pack='.$form->getObject()->getIdPack().'&id_ref='.$form->getObject()->getIdRef() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('pack/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'pack/delete?id_pack='.$form->getObject()->getIdPack().'&id_ref='.$form->getObject()->getIdRef(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['id_projet']->renderLabel() ?></th>
        <td>
          <?php echo $form['id_projet']->renderError() ?>
          <?php echo $form['id_projet'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ref_projet']->renderLabel() ?></th>
        <td>
          <?php echo $form['ref_projet']->renderError() ?>
          <?php echo $form['ref_projet'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['nom_pack']->renderLabel() ?></th>
        <td>
          <?php echo $form['nom_pack']->renderError() ?>
          <?php echo $form['nom_pack'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['actif']->renderLabel() ?></th>
        <td>
          <?php echo $form['actif']->renderError() ?>
          <?php echo $form['actif'] ?>
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
