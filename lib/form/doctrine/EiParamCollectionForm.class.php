<?php

/**
 * EiParamCollectionForm représente une collection de formulaires
 * de paramètre. Elle sera utilisée afin de générer automatiquement des
 * imbrication de formulaire de paramètre dans les fonctions et les versions
 *
 * @author Grégory Elhaimer
 */
class EiParamCollectionForm extends sfForm{
    
    public function configure(){
            
        if($eiFonction = $this->getOption('eiFonction'))
                $this->embedExistingFonctionParameters($eiFonction->getParams());
                
    }
    
    /**
     * Configure la collection pour une fonction.
     * @param type $fonction 
     */
    protected function embedExistingFonctionParameters($params){
        foreach($params as $i => $param){
            $this->embedEiParamForm($i, new EiParamForm($param));
        }
    }
    
    /**
     * Ajoute un formulaire EiParam à la collection
     * @param type $name
     * @param EiParamForm $form 
     */
    public function embedEiParamForm($name, EiParamForm $form){
        if(isset($name))
            $this->embedForm($name, $form);
        else
            throw new InvalidArgumentException('No name give for addEmbedParamForm: '. $name.' given');
    }
}

?>
