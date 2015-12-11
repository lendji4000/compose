<?php print_r($tab);?>
<?php if($versions==null) : ?>
<b>Erreur </b>
<?php else :?>
<?php foreach ($versions as $version ) : ?>
<?php echo 'liste versions : '?>
<?php echo $version->libelle ?>
<?php endforeach; ?>
<?php endif; ?>
