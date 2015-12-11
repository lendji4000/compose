<?php
/**
 * Template permettant la génération du XSL relatif à un bloc de version.
 * 
 * @author Grégory Elhaimer
 * 
 * 
 * @param EiVersion $eiVersion          La version en cours de traitement.
 * 
 * @param Boolean   $isFirstNodeInRoot  Boolean indiquant si l'on traite ou non la balise de niveau 1 depuis la racine du document
 *                                      XML que le XSL se doi de traiter. Cela permet de générer un xpath correct pour la première boucle du bloc.
 * 
 * @param Boolean   $isRoot             Indique si l'on est sur la version root ou non
 * 
 * @param String    $rootNodeName       Le nom de la version racine du scenario (et donc de la balise racine du XML)
 *  
 */

        //récupération du contenu trié de la version
        $content = $eiVersion->getOrderedContent();
        
        // si l'on affiche le premier bloc, alors on préfixe le xpath
        //de la boucle foreach XSL par le nom de la version racine.
        if(!$isRoot && $isFirstNodeInRoot) {
            $xpath = $rootNodeName.'/'.$eiVersion->getLibelle();
            $isFirstNodeInRoot = false;
            }
        else
            $xpath = $eiVersion->getLibelle();
        
        //pour chaque éléments de la version, s'il en existe
        if(isset($content) && count($content) > 0){
            //si l'affiche n'est pas celui du bloc root, alors on 
            //embarque le contenu XSL qui va suivre dans une boucle XSL
            //qui permettra le parsage de valeurs multiples.
            if(!$isRoot)
                echo '<xsl:for-each select="'.$xpath.'">';
            
            //pour chaque élément contenu dans la version
            foreach($content as $c => $object){
                $obj = $object->getRawValue();
                //si l'objet en question est une version (un bloc), alors on rappelle ce partial
                if(get_class($obj) == "EiVersion")
                    include_partial('generateSousVersionXSL', 
                            array(  'eiVersion' => $obj,
                                    'isFirstNodeInRoot'=> $isFirstNodeInRoot, 
                                    'isRoot' => false, 
                                    'rootNodeName' => $eiVersion->getLibelle())
                            );
                
                else{
                    //si l'on est en train de traiter une fonction
                    //alors on affiche les paramètres parsés de la manière souhaitée
                    $params = $obj->getEiParamsXSLParsed();
                    $kalFonction = $obj->getKalFonction();
                    $commandes = $kalFonction->getCommandes();
                    foreach($commandes as $comm => $commande){
                        echo $commande->getXSLParsed($params);
                    }
                }
            }
            
            if(!$isRoot)
                echo '</xsl:for-each>';
        }
?>
