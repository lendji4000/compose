<projets>
<?php if(isset($projets) && ($projets->getFirst())) : ?>

    <?php foreach ($projets as $projet): ?>

      <projet>
          <?php foreach ($projet as $key => $value): ?>
        <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
         <?php endforeach; ?>
      </projet>
    <?php  endforeach; ?>
<?php else :  ?>
    <no_project></no_project>
<?php endif; ?>

<?php if(isset($guard_user) && ($guard_user!=null)) : ?> 
      <user>
        <first_name><?php echo $guard_user->first_name ?></first_name>
        <last_name><?php echo $guard_user->last_name ?></last_name>
        <company><?php echo $guard_user->company ?></company>
        <email_address><?php echo $guard_user->email_address ?></email_address>
        <username><?php echo $guard_user->username ?></username>
      </user> 
<?php else :  ?>
    <no_user></no_user>
<?php endif; ?>
 

</projets>