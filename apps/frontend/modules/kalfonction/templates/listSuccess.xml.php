
<kalfonction>
<?php foreach ($kal_fonctions as $url => $kalfonction): ?>
  <kalfonction >
<?php foreach ($kalfonction as $key => $value): ?>
    <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
<?php endforeach ?>
  </kalfonction>
<?php endforeach ?>
</kalfonction>

