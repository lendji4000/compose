<?php if(!isset($error)): ?>
<?php
/**
 * Partiel initiant la génération du XSL d'interprétation des jeux de tests.
 * Ce partiel est la partie commune de chaque XSL.
 * 
 * @author Grégory Elhaimer
 * 
 * @param EiVersion $eiVersion  La version racine du scenario.
 *  
 */
    echo '<?xml version="1.0" ?>';
    echo '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">';

        echo '<xsl:output method="html" />';
        echo '<xsl:template match="/">';
        echo '<TestCase>';
            include_partial('generateSousVersionXSL', 
                    array('eiVersion' => $eiVersion, 
                            'isRoot' => true,
                            'isFirstNodeInRoot'=> true, 
                            'rootNodeName' => $eiVersion->getLibelle()));
        echo '</TestCase>';
        echo '</xsl:template>';
        
    echo '</xsl:stylesheet>';
?>
<?php else: ?> 
<?php   echo '<error>'.$error.'</error>';   ?>
<?php endif; ?>
