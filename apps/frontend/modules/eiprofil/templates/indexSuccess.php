<h3>Mes profils</h3>

<table id="common_table">
    <thead id="common_thead">
        <tr class="common_tr">
            <th>Défaut</th>
            <th>Nom </th>
            <th>Description</th>
            <th>Date de creation</th>
            <th>Mis à jour le  </th>
        </tr>
    </thead>
    <tbody id="common_tbody">
        <?php foreach ($ei_profils as $ei_profile): ?>
            <tr class="common_tr">
                <td>
                    <?php if ($ei_profile->getDefaut() == true): ?> <input type="radio" checked="checked" readonly="readonly" disabled="disabled" />
                    <?php else: ?><input type="radio" readonly="readonly" disabled="disabled" />
                    <?php endif; ?>
                </td>
                <td><?php echo $ei_profile->getNomProfil() ?></td> 
                <td><?php echo $ei_profile->getDescProfil() ?></td>
                <td><?php echo $ei_profile->getCreatedAt() ?></td>
                <td><?php echo $ei_profile->getUpdatedAt() ?></td>

            </tr>
        <?php endforeach; ?>

    </tbody> 
</table>