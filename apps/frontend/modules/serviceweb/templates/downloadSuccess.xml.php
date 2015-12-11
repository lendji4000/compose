<?php if(isset($login) && isset($profile_id) && isset($profile_ref) ):  ?>
<?php if(isset($result)): ?>
<?php if($result==-5 || $result==-6): //Si l'utilisateur n'est pas retrouvé ?>
<error_user>
    User not found
</error_user>
<?php endif; ?>
<?php if($result==-2): ?>
<error_function>
    Warning : many scripts are associated with the spécific environment. Correct it or contact administrator
</error_function>
<?php else : ?>
<?php echo html_entity_decode($result , ENT_QUOTES, 'UTF-8') ?>
<?php endif; ?>

<?php else : ?>

<error_function>Fichier xsl introuvable
</error_function>
<?php endif; ?>
<?php else : ?>
<param_manquant>Paramètre manquant</param_manquant>
<?php endif; ?>
