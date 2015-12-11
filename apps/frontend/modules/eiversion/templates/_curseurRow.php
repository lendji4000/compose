<?php
/**
 * Partial représentant les lignes séparants les fonctions et les versions au sein du scenario.
 * Ces lignes permettent de positionner le curseur.
 * 
 * @author Grégory Elhaimer
 * @param integer   $position               La position à laquelle doit être ajoutée la fonction suivante
 *                                          Cette position est généralement égale au nombre de fonctions
 *                                          contenues dans le formulaire de version qui inclu ce partial
 * 
 * @param integer  $position_sous_version   La position à laquelle doit être ajoutée la sous_version suivante
 *                                          Cette position est généralement égale au nombre de sous_versions
 *                                          contenues dans le formulaire de version qui inclu ce partial
 * 
 * @param integer   $positionDB             La position à laquelle se trouve la ligne pour curseur. Cette position
 *                                          représente la position que devra avoir l'élément qui sera ajouté quand la ligne
 *                                          générée dans ce template sera sélectionnée.
 * 
 * @param array     $chemin                 Le chemin représentant la position absolue du formulaire.    
 * @param boolean   $is_selected            True si la ligne doit être sélectionnée, false sinon
 */

$paramsUrl = $paramsUrl->getRawValue();

if (isset($insert_after))
   $paramsUrl ['insert_after'] = $insert_after;

if (!isset($is_selected)) // permet de déterminer si la ligne est sélectionnée ou non
    $is_selected = false;
?>

<!-- Ligne pour curseur -->
<div class="checked_place_to_add <?php if ($is_selected) echo 'lighter'; ?>" >
    <a class="add_fonction_link" href="<?php echo url_for2('ajouterFonction', $paramsUrl)?>"></a>
    <a class="add_block_link" href="<?php echo url_for2("ajouterBlock", $paramsUrl) ?>"></a>
</div>
