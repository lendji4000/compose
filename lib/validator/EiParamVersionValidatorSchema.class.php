<?php

/**
 * Description of EiParamVersionValidatorSchema
 *
 * @author Grégory Elhaimer
 */
class EiParamVersionValidatorSchema extends sfValidatorSchema {

    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('nom_param', 'Nom du paramètre requis.');
        $this->addMessage('valeur', 'Spécifiez une valeur par défaut.');
    }

    /**
     * Permet de définir si le formulaire de paramètre pour les versions est valide ou non
     * @param type $values
     * @return type
     * @throws sfValidatorErrorSchema 
     */
    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);

        foreach ($values as $key => $value) {
            $errorSchemaLocal = new sfValidatorErrorSchema($this);
            //ni le nom ni la valeur ne sont saisis : on ignore le paramètre
            if ( (!$value['valeur'] && !$value['nom_param']) ||  $value['delete'] == true)
                unset($values[$key]); 
            else{
                // le nom est saisie mais pas la valeur
                if ($value['nom_param'] && !$value['valeur'])
                    $errorSchemaLocal->addError(new sfValidatorError($this, 'valeur'), 'valeur');
                // valeur saisie mais pas le nom
                if ($value['valeur'] && !$value['nom_param']) 
                    $errorSchemaLocal->addError(new sfValidatorError($this, 'nom_param'), 'nom_param'); 
                // Si des erreurs sont trouvées, on les ajoute au tableau d'erreurs
                if (count($errorSchemaLocal)) 
                    $errorSchema->addError($errorSchemaLocal, (string) $key);
            }
        }

        // si l'on a trouvé des erreurs, alors on transmet une exception
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}

?>
