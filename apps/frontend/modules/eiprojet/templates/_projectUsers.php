<!--comments-->
<?php if ($users->getFirst()): ?>
    <table class="liste_utilisateurs">
    <thead>
        <th colspan="2" align="justify" >
            <b>Utilisateurs</b>
        </th>
        <td class="nombre">
            <a href="#" id="derouler_liste_utilisateurs" alt="Details" tag="Details" > 
                <img class="liste_users_plus_info" alt="" src="/images/icones/expand.png">
                <img class="liste_users_moins_info" alt="" src="/images/icones/collapse.png">
            </a>
        </td>
    </thead>

    <tbody>
        <?php foreach ($users as $u) : ?>
            <tr class="to_hide">
                <td class="prenom_utilisateur">
                    <?php echo $u->getEiUser()->getFirstName(); ?>
                </td>
                <td class="nom_utilisateur">
                    <?php echo $u->getEiUser()->getLastName(); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    <th colspan="2" ></th>
    </tfoot>

<?php endif; ?>
</table>


