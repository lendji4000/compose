<div class="part1">
    <?php if(isset($eimodule)): ?>
    <?php
            switch ($eimodule) {
                case 'eiprojet':
                    include_partial('aideProjet');
                    break;
                case 'eiscenario':
                    include_partial('aideScenario');
                    break;
                case 'eifonction':
                    include_partial('aideFonction');
                    break;
                case 'eiversion':
                    include_partial('aideVersion');
                    break;
                case 'eiprofil':
                    include_partial('aideProfil');
                    break;
                case 'eilog':
                    include_partial('aideLog');
                    break;
                default:

                    break;
            }
            ?>
    <?php else: ?>
    <b>Faire un choix dans le menu contextuel de droite !!</b>
    <?php  endif;?>
</div>
<div class="part2">
    <table id="table_menu"  width="100%">
        <tr>
            <td >
                <div >
                    <h3 style="text-align: center "><b > AIDE CONTEXTUELLE</b></h3>
                    <?php if($sf_user->isAuthenticated()):  ?>
                    <ul id="" style="text-align: center ">
                        <li><b><i><?php echo link_to1("Projets", "@aide?eimodule=eiprojet") ?></i></b></li>
                        <li><b><i><?php echo link_to1('Scenarios', "@aide?eimodule=eiscenario") ?></i></b></li>
                        <li><b><i><?php echo link_to1('Versions', "@aide?eimodule=eiversion") ?></i></b></li>
                        <li><b><i><?php echo link_to1("Fonctions", "@aide?eimodule=eifonction") ?></i></b></li>
                        <li><b><i><?php echo link_to1('Profils', "@aide?eimodule=eiprofil") ?></i></b></li>
                        <li><b><i><?php echo link_to1('Logs', "@aide?eimodule=eilog") ?></i></b></li>

                    </ul>
                    <?php else: ?>
                    <b>Attente d'authentification ...</b>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
</div>
<div id="cadrage" ></div>
