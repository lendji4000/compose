<?php

?>
<form action="<?php echo url_for('eifonction/generateRobotCode') ?>" method="post" <?php $playOnRobotForm->isMultipart() and print 'enctype="multipart/playOnRobotForm-data" ' ?>>
  <table>
    <tfoot>
      <tr>
        <td>
          <input type="submit" value="Jouer la fonction" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $playOnRobotForm->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $playOnRobotForm['url_depart']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['url_depart']->renderError() ?>
          <?php echo $playOnRobotForm['url_depart'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $playOnRobotForm['navigateur']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['navigateur']->renderError() ?>
          <?php echo $playOnRobotForm['navigateur'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $playOnRobotForm['version']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['version']->renderError() ?>
          <?php echo $playOnRobotForm['version'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $playOnRobotForm['robot']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['robot']->renderError() ?>
          <?php echo $playOnRobotForm['robot'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $playOnRobotForm['vitesse_jeu']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['vitesse_jeu']->renderError() ?>
          <?php echo $playOnRobotForm['vitesse_jeu'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $playOnRobotForm['environnement']->renderLabel() ?></th>
        <td>
          <?php echo $playOnRobotForm['environnement']->renderError() ?>
          <?php echo $playOnRobotForm['environnement'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>