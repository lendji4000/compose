<?php
/**
 * Partiel utilisé lors de l'ajout d'une sous-version au sein d'une autre version.
 * 
 * @param EiScenarioForm $form              Le formulaire de version à partir duquel $chemin commence
 * 
 * @param integer   $position               La position à laquelle doit être ajoutée la fonction suivante
 *                                          Cette position est généralement égale au nombre de fonctions
 *                                          contenues dans le formulaire de version qui inclu ce partial
 * 
 * @param integer   $position_sous_version  La position à laquelle doit être ajoutée la sous_version suivante
 *                                          Cette position est généralement égale au nombre de sous_versions
 *                                          contenues dans le formulaire de version qui inclu ce partial
 * 
 * @param integer   $positionDB             La position à laquelle se trouve la ligne pour curseur. Cette position
 *                                          représente la position que devra avoir l'élément qui sera ajouté quand la ligne
 *                                          générée dans ce template sera sélectionnée.
 * 
 * @param array     $index                  Le chemin représentant la position absolu
 */

// intialisation de position sous version
if(!isset($position_sous_version))
    $position_sous_version = 0;
$sousVersionForm = $form->getSousVersionForm($index);    
?>

<?php
        include_partial('formContent', array(   'form' => $sousVersionForm['versionWidgets'], 
                                                'version' => $sousVersionForm['versionForm'], 
                                                'chemin' => $index
                                                ));
?>

<?php 
    //étant donné que $index contient, dans son dernier index, la position d'ajout
    // celle ci doit etre retirée afin de garantir la bonne hiérarchie des sous versions
    //les une par rapport aux autres
    $index = $index->getRawValue();
    unset($index[count($index)-1]);
    
    include_partial('eiversion/curseurRow', array(  'position' => 0 , 
                                                    'position_sous_version' => $position_sous_version,
                                                    'chemin' => $index,
                                                    'positionDB' => $positionDB+1
                                                )); 
?>