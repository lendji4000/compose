<h2>Profils : </h2>
<?php $pair = false; ?>

<table id="common_table">
    <thead id="common_thead">
        <tr class="common_tr">
            <th>Nom profil</th>
            <th>Desc profil</th>
            <th>Dernière mise à jour</th>
        </tr>
    </thead>
    <tbody id="common_tbody">
        <?php
        foreach ($ei_profils as $ei_profile):
            $pair = !$pair;
            ?>
            <tr class="common_tr <?php if($pair) echo "pair" ?>">
                <td><?php echo $ei_profile->getNomProfil() ?></td>
                <td><?php echo $ei_profile->getDescProfil() ?></td>
                <td class="date"><?php echo $ei_profile->getUpdatedAt() ?></td>
                
            </tr>
<?php endforeach; ?>

    </tbody> 
</table>