<?php

/**
 * Affiche au format XML, la version $eiVersion pour l'utilisation des jeux de tests
 * 
 * @author Grégory Elhaimer
 * @param $eiVersion  La version a afficher
 */
?>

<?php if (isset($eiVersion)): ?>

    <?php echo "<" . $eiVersion->getLibelle() . ">" ?>
    <?php
    foreach ($eiVersion->getEiParamsVersion() as $param)
        echo "<" . $param->getNomParam() . ">" . $param->getValeur() . "</" . $param->getNomParam() . ">";

    foreach ($eiVersion->getEiSousVersionsOrderedByPosition() as $sversion)
        include_partial('eiversion/downloadJDT', array("eiVersion" => $sversion));
    ?>
    <?php echo "</" . $eiVersion->getLibelle() . ">" ?>
<?php
else:
    echo "<error>Version non spécifiée.</error>";
endif;
?>
