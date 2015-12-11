<?php
/**
 * Validator empéchant la sauvegarde d'un élément ajouté dans le DOM puis supprimé
 * sans avoir été sauvgeradé en base au préalable.
 * Evite ainsi des traitements JS supplémentaires.
 *
 * @author Grégory Elhaimer
 */
class EiFonctionValidatorSchema extends sfValidatorSchema{
    /**
     * Permet de définir si le formulaire est valide ou non
     * @param type $values
     * @return type
     */
    protected function doClean($values) {

        foreach ($values as $key => $value) 
            //la version est a supprimer
            if ($value['delete'] == true) {
                unset($values[$key]);
            }

        return $values;
    }
}

?>
