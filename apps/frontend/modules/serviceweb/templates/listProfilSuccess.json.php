<?php if(isset($profils) && $profils->getFirst()):  ?>
[
<?php $nb = count($profils); $i = 0; foreach ($profils as $i => $profil): ++$i ?>
{
<?php $nb1 = count($profil); $j = 0; foreach ($profil as $key => $value): ++$j ?>
  "<?php echo $key ?>": <?php echo json_encode($value).($nb1 == $j ? '' : ',') ?>
 
<?php endforeach ?>
}<?php echo $nb == $i ? '' : ',' ?>
 
<?php endforeach ?>
]
<?php else : ?>
[
{
    "erreur" : "e"
    }
]
<?php endif; ?>
