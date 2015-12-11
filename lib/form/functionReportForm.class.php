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
class functionReportForm extends sfForm {
    public function configure() {   
      
      $this->widgetSchema['execution_id'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_execution_id',
                  'class' => 'form-control  ',
                    ));
      $this->widgetSchema['iteration_id'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_iteration_id',
                  'class' => 'form-control  ',
                    ));
      
      $this->widgetSchema['scenario_id'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_scenario_id', 
                  'class' => 'form-control  ',
                    ));  
      
      $this->widgetSchema['profile'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_profile',
                  'class' => 'form-control  ',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    )); 
      
      $this->widgetSchema['state'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_state', 
                    'class' => 'form-control  '  
                    ));
      $this->widgetSchema['mode'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_function_report_by_mode', 
                    'class' => 'form-control  ' 
                    )); 
       
      //Validateurs de recherche
//      $this->validatorSchema['state'] = new sfValidatorChoice(
//              array('choices' => array_keys($stateTab),
//                    'required' => false));
//       
//      $this->validatorSchema['delivery_state'] = new sfValidatorChoice(
//              array('choices' => array_keys($deliveries),
//                    'required' => false));
//       
//      $this->validatorSchema['type'] = new sfValidatorChoice(
//              array('choices' => array_keys($subjectTypes),
//                    'required' => false));
//      
//      $this->validatorSchema['priority'] = new sfValidatorChoice(
//              array('choices' => array_keys($subjectPriorities),
//                    'required' => false));
       
      //Définition du nom du formulaire
      $this->widgetSchema->setNameFormat('functionReportForm[%s]');
      
    } 
}

?>
