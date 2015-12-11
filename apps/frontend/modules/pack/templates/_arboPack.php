<!-- Arborescence d'un package -->
<?php if(isset($ref_pack) && isset($id_pack) && $ref_pack!=null && $id_pack!=null ):
        //&& isset($ei_scenario_id) && $ei_scenario_id!=null && isset($id_version) && $id_version!=null): //&& isset($kal_parent) ?>
<!-- fonctions du package -->
<?php $fonctions=Doctrine_Core::getTable('EiPackOfFunction')->
        getFunctions(Doctrine_Core::getTable('KalFonction')->getFunctions(),$id_pack, $ref_pack); ?>
<?php if($fonctions->getFirst()): ?>
<?php foreach ($fonctions as $f) : ?>
<li>
    <input type="hidden" name="ref_function" value="<?php echo $f->getCfRefKal(); ?>" class="ref_function" />
    <input type="hidden" name="id_function" value="<?php echo $f->getCfIdKal(); ?>" class="id_function" />
    <input type="hidden" name="kal_fonction" value="<?php echo $f->getId(); ?>" class="kal_fonction" />
    <input type="hidden" name="ref_pack_contenant_function" value="<?php echo $ref_pack; ?>" class="ref_pack_contenant_function" />
    <input type="hidden" name="id_pack_contenant_function" value="<?php echo $id_pack; ?>" class="id_pack_contenant_function" />
    <img src="/images/boutons/plus2.png" alt="" title="Fonction"  class="plus_info_arbo_function " />
    <img src="/images/boutons/minus.png" alt="" title="Fonction"  class="moins_info_arbo_function" />
    <img src="/images/icones/document.png" alt="" title="Fonction"  class="img_arbo_function" />
    <span class="lien_survol_arbre">
        <a href="#" class="pop get_path_function"  rel="popover"  data-content="<?php // include_partial('kalfonction/showDoc', array('kalFonction'=>$f));?>" data-original-title="<?php echo '<b>'.$f->nom_fonction.' </b>';?>">
                <?php echo $f->nom_fonction ?> 
        </a>
    </span>
    <ul class="arbo_function degrade10"></ul>
</li>
<?php endforeach; ?>
<?php endif; ?>

<!-- packs du pack -->

<?php $packs=Doctrine_Core::getTable('EiPackHasPack')
            ->getPacks(Doctrine_Core::getTable('EiPack')->getPacks(),$id_pack, $ref_pack)->execute();?>
<?php if($packs->getFirst()): ?>
<?php foreach ($packs as $p) : ?>
<li >
    <input type="hidden" name="ref_pack" value="<?php echo $p->getIdRef(); ?>" class="ref_pack" />
    <input type="hidden" name="id_pack" value="<?php echo $p->getIdPack(); ?>" class="id_pack" />
    <img src="/images/boutons/minus.png" alt="" title="Package"  class="moins_info_arbo_pack" />
    <img src="/images/boutons/plus2.png" alt="" title="Package"  class="plus_info_arbo_pack" />
    <img src="/images/boutons/vue2.png" alt="" title="Package"  class="img_arbo_pack" />
    <span class="lien_survol_arbre">
        <a href="#" class="get_path_pack">
                <?php echo $p->nom_pack ?>
        </a>
    </span>
    <ul class="arbo_pack "></ul>
</li>
<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
