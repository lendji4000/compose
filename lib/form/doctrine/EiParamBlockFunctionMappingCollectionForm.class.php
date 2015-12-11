<?php

/**
 * Class EiParamBlockFunctionMappingCollectionForm
 */
class EiParamBlockFunctionMappingCollectionForm extends sfForm{
    
    public function configure(){

        /** @var EiFonction $eiFonction */
        if($eiFonction = $this->getOption('eiFonction')){
            $this->embedExistingFonctionMapping($eiFonction->getEiFunctionMapping());
        }
                
    }

    /**
     * @param $params
     */
    protected function embedExistingFonctionMapping($mappings){
        foreach($mappings as $i => $mapping){
            $this->embedEiParamBlockFunctionMappingForm($i, new EiParamBlockFunctionMappingForm($mapping));
        }
    }

    /**
     * @param $name
     * @param EiParamBlockFunctionMapping $form
     * @throws InvalidArgumentException
     */
    public function embedEiParamBlockFunctionMappingForm($name, EiParamBlockFunctionMappingForm $form){
        if(isset($name))
            $this->embedForm($name, $form);
        else
            throw new InvalidArgumentException('No name give for addEmbedParamMappingForm: '. $name.' given');
    }
}

?>
