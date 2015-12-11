<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of deliverySearchForm
 *
 * @author lenine
 */
class deliverySearchForm extends sfForm {
    public function configure() {  
      $deliveryStates=$this->getOption('deliveryStates');
      
      $this->widgetSchema['title'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_delivery_by_title',
                    'class' => 'form-control',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4',
                    ));  
      
      $this->widgetSchema['author'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_delivery_by_author',
                   'class' => 'form-control',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    )); 
      $this->widgetSchema['state']    = new sfWidgetFormChoice(
              array('choices' => $deliveryStates),
              array( 'id' => 'search_delivery_by_state',
                      'class' => 'form-control',));
      $this->validatorSchema['state'] = new sfValidatorChoice(
              array('choices' => array_keys($deliveryStates),
                    'required' => false));
       
      
      //Date de debut 
      $this->widgetSchema['start_date'] = new sfWidgetFormInputText(array(), 
              array('class' => 'form-control ',
                    'readonly' => 'readonly',
                    'data-format'=>"yyyy/MM/dd")); 
      //Date de fin
      $this->widgetSchema['end_date'] = new sfWidgetFormInputText(array(), 
              array('class' => 'form-control',
                    'readonly' => 'readonly',
                    'data-format'=>"yyyy/MM/dd"));
      
      $this->widgetSchema['start_date']->setAttribute('id', 'search_delivery_by_start_date');
      $this->widgetSchema['end_date']->setAttribute('id', 'search_delivery_by_end_date');
      
      //Définition du nom du formulaire
      $this->widgetSchema->setNameFormat('deliverySearch[%s]');
      
    }
}

?>
