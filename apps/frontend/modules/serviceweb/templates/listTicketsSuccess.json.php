<?php if(isset($error)): ?>
[
{
"erreur" : <?php echo $error; ?> ,
}
]
<?php else : ?>
<?php if(isset($ei_tickets) && $ei_tickets->getFirst() && isset($defPack) && $defPack!=null):  ?>
{
"default": {
   "ticket_id" : "<?php echo (isset($defPack)?$defPack->getTicketId():0) ?>",
   "ticket_ref" : "<?php echo (isset($defPack)?$defPack->getTicketRef():0) ?>"
},
"list":
[
<?php $nb = count($ei_tickets); $i = 0; foreach ($ei_tickets as $i => $ei_ticket): ++$i ?>
{
<?php $nb1 = count($ei_ticket); $j = 0; foreach ($ei_ticket as $key => $value): ++$j ?>
  "<?php echo $key ?>": <?php echo json_encode($value).($nb1 == $j ? '' : ',') ?>
 
<?php endforeach ?>
}<?php echo $nb == $i ? '' : ',' ?>
 
<?php endforeach ?>
]
}
<?php else : ?>
{
"erreur" : "No ticket found or no default package loaded ..."
}
<?php endif; ?>

<?php endif; ?>