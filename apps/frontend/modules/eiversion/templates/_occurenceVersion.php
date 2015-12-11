<table  class="detail_version <?php if(isset($display)) echo $display?>">
    
        <thead>
            <input type="hidden" name="id_version_courante" value="<?php echo $form['id']->getValue() ?>" class="id_version_courante" />
            <tr>
                <td colspan="2" class="display_hidden"><?php echo $form->renderHiddenFields(true) ;?></td>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2"><?php  echo $form->renderError() ?></td></tr>
            <tr>
                <td >
                    <img src="/images/icones/expand.png" alt="" class="version_plus_info" />
                    <img src="/images/icones/collapse.png" alt="" class="version_moins_info" />
                        <?php  echo $form['libelle'] ?>
                </td>
                <td class="delete_version"  align="right">
                    <img src="/images/icons/knob_Cancel.png" alt="" />
                </td>

            </tr>
            <tr class="details_version_observation">
                <td><b>Description: &nbsp; </b> </td>
                <td><?php  echo $form['description'] ?></td>
            </tr>

        </tbody>
    </table>
