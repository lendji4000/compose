<?php
if(isset($error1)) echo 'profil scenario introuvable'.'<br/>';
if(isset($error2)) echo 'version introuvable'.'<br/>';
if(isset($error3)) echo 'Profil , scenario , ou version introuvable'.'<br/>';
if(isset($error4)) echo 'Aucune fonction en base, veuillez en rajouter'.'<br/>';
//echo $ei_scenario_id.'<br/>';
//echo $id_version.'<br/>';
//echo $id_profil.'<br/>';
//$version=Doctrine_Core::getTable('EiVersion')->findOneById(33);
//if($version!=null) echo $version;
?>

