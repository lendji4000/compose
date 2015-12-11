
<?php if(isset($users_projet) && ($users_projet->getFirst())) : ?>
<users>
    <?php foreach ($users_projet as $up): ?>

      <user>
        <first_name><?php echo $up->first_name ?></first_name>
        <last_name><?php echo $up->last_name ?></last_name>
        <email_address><?php echo $up->email_address ?></email_address>
        <username><?php echo $up->username ?></username>
      </user>
    <?php  endforeach; ?>
</users>
<?php endif; ?>
<?php if(isset($projets) && ($projets->getFirst())) : ?>
<projets>
    <?php foreach ($projets as $projet): ?>
       
      <projet>
          <?php foreach ($projet as $key => $value): ?>
        <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
         <?php endforeach; ?>
      </projet>
    <?php  endforeach; ?>
</projets>
<?php endif; ?>