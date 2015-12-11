<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EiVersionForValidatorSchema
 *
 * @author gregoriz
 */
class EiVersionValidatorSchema extends sfValidatorSchema{
     
    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('libelle', 'Nom du block doit être unique à son niveau.');
        $this->addMessage('requis', 'Le champ est requis.');
    }

    
    /**
     * Permet de vérifier, si, dans une sous version, les noms de ses sous versions
     * sont uniques.
     * 
     * @param type $values Les sous versions reçues à la soumission du formulaire
     * @return type
     * @throws sfValidatorErrorSchema 
     */
    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);

        //on parcourt chaque sous_versions reçues
        foreach ($values as $key => $value){ 
             $errorSchemaLocal = new sfValidatorErrorSchema($this);
            //si le champ delete est a true on supprime la clé 
            if ($value['delete'] == true) {
                unset($values[$key]);
            }
                
            //sinon on vérifie les valeurs
            else{
               if(trim($value['libelle']) == "")
                   $errorSchemaLocal->addError(new sfValidatorError($this, 'requis'), 'libelle'); 
                    foreach($values as $keyAux => $valueAux){
                        //on compare les sous_versions les une par rapport aux autres.
                        //si les libellés sont identiques pour deux sous versions différentes, alors
                        //on ajoute une erreur
                        if($value['libelle'] == $valueAux['libelle'] && $key != $keyAux)
                            $errorSchemaLocal->addError(new sfValidatorError($this, 'libelle'), 'libelle');  
                    }
            }
            
             // Si des erreurs sont trouvées, on les ajoute au tableau d'erreurs
            if (count($errorSchemaLocal)) {
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
