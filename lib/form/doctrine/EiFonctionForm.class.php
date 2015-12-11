<?php

/**
 * EiFonction form.
 *
 * @package    kalifast
 * @subpackage form
 * @author     Grégory Elhaimer
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiFonctionForm extends BaseEiFonctionForm {

    public function configure() {
        $this->useFields(array('description','function_id' , 'function_ref'));
        //unset($this['kal_fonction']);
        //l'utilisation du champ kal fonction permet de déterminer l'affichage dans
        //le template eiVersion/_formContent.php
        $this->initFields();

        $this->widgetSchema['description']->setAttributes(Array( 
            'class' => '  form-control',
            'rows' => 2));

        if($this->getOption('params')){
            $this->embedParameters($this->getOption('params'));
        }

        if($this->getOption('mappings')){
            $this->embedMappings($this->getOption('mappings'));
        }
        
        parent::configure();
    }

    /**
     * Imbrique les EiParams existant pour la fonction associée au formulaire
     *  
     */
    public function embedParameters($params){        
        $paramForms = new EiParamCollectionForm();
        foreach($params as $p => $param){
            $paramForms->embedEiParamForm($p, new EiParamForm($param, array('nomParam' => $param->getName())));
        }
        $this->embedForm('params', $paramForms);
    }

    /**
     * Imbrique les EiParamBlockFunctionMapping existant pour la fonction associée au formulaire
     *
     */
    public function embedMappings($mappings){
        $mappingForms = new EiParamBlockFunctionMappingCollectionForm();

        /** @var EiParamBlockFunctionMapping $mapping */
        foreach($mappings as $m => $mapping){
            $mappingForms->embedEiParamBlockFunctionMappingForm($m, new EiParamBlockFunctionMappingForm($mapping, array(
                'nomParam' => $mapping->getEiFunctionParamMapping()->getEiParam()->getName()
            )));
        }
        $this->embedForm('mappings', $mappingForms);
    }
    
    /**
     * Recherche le formulaire $index dans le formulaire imbriqué 'params'
     * @param type $index l'index où chercher
     * @return EiParamForm 
     */
    public function getEmbeddedParamForm($index){
        return $this->getEmbeddedForm('params')->getEmbeddedForm($index);
    }
    
    /**
     * Initialise les widgets et leur validateurs 
     */
    private function initFields(){
        $this->initWidgets();
        $this->initValidators();
    }
    
    /**
     * Initiliase les widgets 
     */
    private function initWidgets() {
        $this->widgetSchema['function_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['function_ref'] = new sfWidgetFormInputHidden();
        //Ajout de la description de la kalfonction au champ observation  de la fonction
        if($this->isNew && $this->getObject()->getFunctionId()!=null && $this->getObject()->getFunctionRef()!=null){
//            $this->setDefault('observation', $this->getObject()->getKalFonction()->getDescription());
        }
    }
    
    /**
     * Initiliase les validateurs 
     */
    private function initValidators(){
        $this->validatorSchema['delete'] = new sfValidatorBoolean();
        //fonction de post validation du formulaire
        $this->validatorSchema->setPostValidator(new sfValidatorAnd(
                        array(
                            new sfValidatorCallback(array('callback' => array($this, 'checkAvailability')))
                        )));
    }
    
    public function checkAvailability($validator, $values) {

        if (!empty($values['ei_version_id'])) {
            $version = Doctrine_Core::getTable("EiVersion")->findOneBy('id', $values['ei_version_id']);
            if ($version != null) {
                $values['ei_scenario_id'] = $version->ei_scenario_id;
            }
        }
        return $values;
    }
}
