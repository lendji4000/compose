<?php

/**
 * Class EiDataSetStructureValidatorSchema
 */
class EiDataSetStructureValidatorSchema extends sfValidatorSchema {

    private $str;
    private $isNew;
    
    public function __construct($fields = null, $options = array(), $messages = array()) {
        if(!isset($options['ei_dataset_structure']))
            throw new Exception('ei_dataset_structure doit être passé en option au validateur');
        
        $this->str = $options['ei_dataset_structure'];
        $this->isNew = $options['is_new'];
        unset($options['ei_dataset_structure']);
        unset($options['is_new']);
        parent::__construct($fields, $options, $messages);
    }
    
    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('name', 'Name must be uniq withing this node.');
    }

    /**
     * Permet de définir si le formulaire de paramètre pour les jeux de données est valide ou non.
     *
     * @param type $values
     * @return type
     * @throws sfValidatorErrorSchema 
     */
    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);

        $name = MyFunction::sluggifyForXML($values['name']);
        $id = isset($values["id"]) ? $values["id"] : -1;

        if( $this->str->getEiDatasetStructureParentId() ){
            /** @var Doctrine_Collection $existing */
            $existing = Doctrine_Core::getTable('EiDataSetStructure')
                ->findByEiDatasetStructureParentIdAndSlug($this->str->getEiDatasetStructureParentId(), $name);

            /** @var EiDataSetStructure $node */
            foreach( $existing as $key => $node ){
                if( $node->getId() == $id ){
                    $existing->remove($key);
                }
            }
        }
        else
            $existing = Doctrine_Core::getTable('EiDataSetStructure')
                ->findByEiDatasetStructureParentIdAndSlug("is null", $name);
        
        if(($existing->count() > 0 && $this->isNew) || ($existing->count() >= 1 && !$this->isNew))
            $errorSchema->addError(new sfValidatorError($this, 'name'), 'name');
        
        // si l'on a trouvé des erreurs, alors on transmet une exception
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}

?>
