<!--comments-->
<?php
    if(!isset($id_profil)) $id_profil = 0;
    if(!isset($profile_name)) $profile_name = "profil";
?>

<?php if ($sf_user->hasFlash('erreur_projet')): ?>
    <p class="flash_msg_success"> 
        <?php echo $sf_user->getFlash('erreur_projet') ?>
    </p>
<?php else: ?>    


    <table id="detail_projet">

        <thead>
            <tr>
                <td  class ="nom_projet">
                    <?php echo link_to2($projet->getLibelle(),
                            'projet_show', 
                            array(  'id_projet'=>$projet->getId(), 
                                    'profile_name'=> $sf_request->getParameter('profile_name'),
                                    'id_profil'=> $sf_request->getParameter('id_profil'))); 
                    ?>
                </td>
                <td>   
                    <a href="#" id="derouler_detail_projet" alt="Details" tag="Details" > 
                        <img class="details_projet_plus_info" alt="" src="/images/icones/expand.png">
                        <img class="details_projet_moins_info" alt="" src="/images/icones/collapse.png">
                    </a>
                </td>
            </tr>

        </thead>

        <tbody>
            <tr id="description_projet" class="to_hide" >
                <td colspan="3">
                    <div>
                        <?php echo $projet->getDescription(); ?>
                    </div>
                </td>
            </tr>
            <tr id="nb_scenarios" class="to_hide">
                <th>
                    Scénarios:
                </th>
                <td class="nombre" colspan="2">
                    <?php
                    if ($nbScenarios > 0)
                        $class = 'nombre_positif';
                    else
                        $class = 'nombre_null';
                    ?>
                    <span class="<?php echo $class ?>"> 
                        <?php echo $nbScenarios; ?>
                    </span>
                </td>
            </tr>
            <tr  id="nb_profil" class="to_hide">
                <th>
                    Profils:
                </th>
                <td class="nombre" colspan="2">
                    <?php
                    if ($nbProfils > 0)
                        $class = 'nombre_positif';
                    else
                        $class = 'nombre_null';
                    ?>
                    <span class="<?php echo $class ?>"> 
                        <?php echo $nbProfils; ?>
                    </span>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td > 
                    <input type="hidden" name="cp_ref_kal" value="<?php echo $projet->getCpRefKal() ?>" class="cp_ref_kal" />
                </td>
                <td colspan="2">  <input type="hidden" name="cp_id_kal" value="<?php echo $projet->getCpIdKal() ?>" class="cp_id_kal" /></td>
            </tr>

        </tfoot>
    </table>
<?php endif; ?>
