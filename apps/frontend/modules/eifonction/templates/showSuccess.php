<?php use_helper('JavascriptBase') ?>
<div>
    <div class="part1">
        <?php  include_partial('showFonction', array("ei_fonction" => $ei_fonction , "ei_version" => $ei_version , "ei_scenario" => $ei_scenario ,"class_item" => 'item_show')) ?>
        
        <table width="100%">
            <thead>

            </thead>
            <tbody>

                <tr>
                    <td> <a href="<?php echo url_for('@ei_fonction_xml?sf_format=xml&id_fonction='.$ei_fonction->id,true); ?>">xml exemple </a>   </td>
                    <td><?php echo link_to1('xml exemple sous php', 'eifonction/generateXML?id_fonction='.$ei_fonction->id) ?></td>
                    <td> <?php echo link_to1('Nouveau paramètre', 'eiparam/new?id_fonction='.$ei_fonction->id) ?>   </td>
                    <td> <?php echo link_to1('Nouvelle fonction kalifast', 'kalfonction/new') ?>  </td>
                    <td> <?php echo link_to1('Jouer la fonction sur le robot', 'eifonction/playOnRobot') ?>  </td>
                </tr>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
    <div class="part2">
        <?php if(!is_null($ei_fonction)) :?>
        <?php include_partial('menuDetailsFonction', array('ei_fonction' =>$ei_fonction)) ?>
        <?php endif; ?>
    </div>
</div>
<input type="hidden" name="url_depart" value="<?php echo $sf_request->getUri(); ?>" class="url_depart" />