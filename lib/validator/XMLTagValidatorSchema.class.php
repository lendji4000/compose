<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XMLTagValidatorSchema
 *
 * @author Grégory Elhaimer <gregory.elhaimer@gmail.com>
 */
class XMLTagValidatorSchema extends sfValidatorSchema {

    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('name', 'Name must be a valid xml tag name.');
    }

    /**
     * Permet de définir si le formulaire de paramètre pour les versions est valide ou non
     * @param type $values
     * @return type
     * @throws sfValidatorErrorSchema 
     */
    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);

        $name = $values['name'];

        try {
            $tag = new DOMElement($name);
        } catch (Exception $e) {
            $errorSchema->addError(new sfValidatorError($this, 'name'), 'name');
        }            

        // si l'on a trouvé des erreurs, alors on transmet une exception
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}
?>
