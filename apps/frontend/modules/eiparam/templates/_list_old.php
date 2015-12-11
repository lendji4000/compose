<?php if($ei_params!=null) : ?>
<table class="param_table">
  <thead>
    <tr>
      <th>Nom paramètre</th>
      <th>Valeur</th>
      <th>Observation</th>
      <th>Mise à jour le </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_params as $ei_param): ?>
    <tr>
      <td><?php echo $ei_param->getKalParam()->nom_param ?></td>
      <td><?php echo $ei_param->getValeur() ?></td>
      <td><?php echo $ei_param->getObservation() ?></td>
      <td><?php echo $ei_param->getUpdatedAt() ?></td>
      <td ><a href="<?php echo url_for('eiparam/show?id='.$ei_param->getId()) ?>"><img src="/images/icons/knob_Info.png" alt="" /> </a></td>
      <td class="edit_param"><img src="/images/icons/edit.png" alt="" /> <input type="hidden" name="id_param" value="<?php echo $ei_param->id ?>" class="id_param" /></td>
      <td class="delete_param"><img src="/images/icons/knob_Cancel.png" alt="" /> <input type="hidden" name="id_param" value="<?php echo $ei_param->id ?>" class="id_param" /></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <?php if(isset($ei_fonction)) :?>
  <tfoot>
      <tr><td class="add_param"><img src="/images/icons/knob_Add.png" alt="" />de paramètres </td></tr>
  </tfoot>
  <input type="hidden" name="id_fonction" value="<?php echo $ei_fonction->id ?>" class="id_fonction" />
   <?php endif;?>
</table>
<?php else : ?>
<b>Aucun paramètre : ajoutez en si besoin !!</b>
<?php endif; ?>

