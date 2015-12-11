<?php $prefix='http://'.$sf_request->getHost();  ?>
<?php if(isset($ei_fonction) && $ei_fonction!=null && isset($kal_fonction) && $kal_fonction!=null && isset($ei_profile) && $ei_profile!=null ): ?>
    <?php if(isset($login) && isset($pwd)):?>
    <user
        xml="<?php echo "http://".$sf_request->getHost().$sf_request->getPathInfoPrefix()."/eifonction/generateXML?function_id=".$ei_fonction->getId()."&amp;profile_id=".$ei_profile->profile_id."&amp;profile_ref=".$ei_profile->profile_ref.'&amp;login='.$login.'&amp;pwd='.$pwd.'&amp;sf_format=xml' ?>"
        xsl= "<?php echo "http://".$sf_request->getHost().$sf_request->getPathInfoPrefix()."/eifonction/genererXSL/".$login.'/'.$pwd.'/'.$kal_fonction->function_id."/".$kal_fonction->function_ref."/".$ei_profile->profile_id."/".$ei_profile->profile_ref.".xml" ?>" />
    <?php else: ?>
    <error_login>
        Require login or Password in URI
    </error_login>
    <?php endif; ?>
<?php else: ?>
<error>
    Fonction ou profil non renseigné
</error>
<?php endif; ?>
 