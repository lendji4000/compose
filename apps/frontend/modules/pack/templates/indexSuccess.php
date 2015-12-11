<h2>Packs</h2>

<table>
  <thead>
    <tr>
      <th>Id pack</th>
      <th>Id ref</th>
      <th>Id projet</th>
      <th>Ref projet</th>
      <th>Nom pack</th>
      <th>Actif</th>
      <th>Is root</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ei_packs as $ei_pack): ?>
    <tr>
      <td><a href="<?php echo url_for('pack/edit?id_pack='.$ei_pack->getIdPack().'&id_ref='.$ei_pack->getIdRef()) ?>"><?php echo $ei_pack->getIdPack() ?></a></td>
      <td><a href="<?php echo url_for('pack/edit?id_pack='.$ei_pack->getIdPack().'&id_ref='.$ei_pack->getIdRef()) ?>"><?php echo $ei_pack->getIdRef() ?></a></td>
      <td><?php echo $ei_pack->getIdProjet() ?></td>
      <td><?php echo $ei_pack->getRefProjet() ?></td>
      <td><?php echo $ei_pack->getNomPack() ?></td>
      <td><?php echo $ei_pack->getActif() ?></td>
      <td><?php echo $ei_pack->getIsRoot() ?></td>
      <td><?php echo $ei_pack->getCreatedAt() ?></td>
      <td><?php echo $ei_pack->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('pack/new') ?>">New</a>
