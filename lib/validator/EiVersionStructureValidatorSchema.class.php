<?php

/**
 * Description of EiVersionStructureValidatorSchema
 *
 * @author Grégory Elhaimer
 */
class EiVersionStructureValidatorSchema extends sfValidatorSchema {

    /** @var  EiVersionStructure $str */
    private $str;
    /** @var  bool $isNew */
    private $isNew;
    
    public function __construct($fields = null, $options = array(), $messages = array()) {
        if(!isset($options['ei_version_structure']))
            throw new Exception('ei_version_structure doit être passé en option au validateur');
        
        $this->str = $options['ei_version_structure'];
        $this->isNew = $options['is_new'];
        unset($options['ei_version_structure']);
        unset($options['is_new']);
        parent::__construct($fields, $options, $messages);
    }
    
    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('name', 'Name must be uniq withing this block.');
        $this->addMessage('iterator_empty', 'Node to iterate in the block must be defined.');
        $this->addMessage('iterator_invalid', 'Node to iterate in the block must be valid.');
        $this->addMessage('iterator_type_invalid', 'You can only iterate a node.');
    }

    /**
     * Permet de définir si le formulaire de paramètre pour les versions est valide ou non
     * @param type $values
     * @return type
     * @throws sfValidatorErrorSchema 
     */
    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);

        $name = MyFunction::sluggifyForXML($values['name']);
        $id = isset($values["id"]) ? $values["id"] : -1;
        /** @var EiVersionStructureTable $tableVersionStr */
        $tableVersionStr = Doctrine_Core::getTable("EiVersionStructure");
        
        if($this->str->getEiVersionStructureParentId()){
            $existing = $tableVersionStr->findByEiVersionStructureParentIdAndSlug($this->str->getEiVersionStructureParentId(), $name);

            /** @var Doctrine_Collection $existing */
            if( $existing->count() > 0 && $this->isNew == false ){

                // On retire l'élément si présent dans la liste.
                foreach( $existing as $key => $elt ){
                    if( $elt->getId() == $this->str->getId() ){
                        $existing->remove($key);
                    }
                }
            }
        }
        else{
            $existing = $tableVersionStr->findByEiVersionStructureParentIdAndSlug("is null", $name);
        }

        $count = $existing->count();

        if(($count > 0 && $this->isNew) || ($count >= 1 && !$this->isNew)){
            $errorSchema->addError(new sfValidatorError($this, 'name'), 'name');
        }


        // ON VERIFIE S'IL S'AGIT D'UN BLOC FOREACH QU'UN NOEUD SOIT RENSEIGNE.
        if( $this->str instanceof EiBlockForeach ){
            if( (isset($values["Iterator"]) && $values["Iterator"] != null ) && isset($values["Iterator"]["ei_dataset_structure_id"]) ){
                $idNode = $values["Iterator"]["ei_dataset_structure_id"];
                /** @var EiNodeDataSet $node */
                $node = Doctrine_Core::getTable("EiNodeDataSet")->find($idNode);

                if( !$node ){
                    $errorSchema->addError(new sfValidatorError($this, 'iterator_invalid'), 'iterator');
                }
                elseif( !($node->getType() == EiDataSetStructure::$TYPE_NODE && !$node->isRoot()) ){
                    $errorSchema->addError(new sfValidatorError($this, 'iterator_type_invalid'), 'iterator');
                }
            }
            else{
                $errorSchema->addError(new sfValidatorError($this, 'iterator_empty'), 'iterator');
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
