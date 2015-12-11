<?php use_helper('JavascriptBase') ?>
<?php if($ei_scenario!=null) : ?>
<input type="hidden" name="ei_scenario_id"  class="ei_scenario_id" value=" <?php echo $ei_scenario->id ?>" />
<?php endif; ?>
<?php if($ei_version!=null) : ?>
<input type="hidden" name="id_version"  class="id_version" value=" <?php echo $ei_version->id ?>" />
<?php endif; ?>
<div>
    <h2>Fonctions </h2>
    <div id="corps_fonction">
        <div id="fonction_list" >
            <p class="add_fonction"><img src="/images/icons/knob_Add.png" alt="" />de fonction</p>
            
            <?php if(is_null($ei_fonctions)) :?>
            <b>Aucune fonction !!  Choisir une autre version</b>
            <?php  else : ?>
            <table class="drag_elt"  >
                <tbody >
                    <?php foreach ($ei_fonctions as $ei_fonction): ?>
                    <tr>
                        <td>
                            <?php include_partial('showFonction', array("ei_fonction" => $ei_fonction , "ei_version" => $ei_version , "ei_scenario" => $ei_scenario , "class_item" => "item")) ?>
                            
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                
            </table>
            
            <?php endif; ?>
        </div>
        
    </div>
    
    <div id="menu_fonction">
        <?php if(!is_null($ei_fonctions)||!is_null($ei_version)||!is_null($ei_scenario)) :?>
        <?php include_partial('menuFonctions', array('ei_fonctions' => $ei_fonctions, "ei_version" => $ei_version , "ei_scenario" => $ei_scenario)); ?>
        <?php endif; ?>
    </div>
    
</div>
<div id="reception_funct">

</div>
<input type="hidden" name="url_depart" value="<?php echo $sf_request->getUri(); ?>" class="url_depart" />