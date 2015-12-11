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
class subjectSearchForm extends sfForm {
    public function configure() {  
      $subjectStates=$this->getOption('subjectStates'); 
      $deliveries=$this->getOption('deliveries');
      $ei_delivery=$this->getOption('ei_delivery');
      $subjectPriorities=$this->getOption('subjectPriorities');
      $subjectTypes=$this->getOption('subjectTypes'); 
      
      $this->widgetSchema['external_id'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_subject_by_external_id',
                  'class' => 'form-control  ',
                    ));
      
      $this->widgetSchema['title'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_subject_by_title', 
                  'class' => 'form-control  ',
                    ));  
      
      $this->widgetSchema['author'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_subject_by_author',
                  'class' => 'form-control  ',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    )); 
      
      $this->widgetSchema['assignment'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_subject_by_assignment', 
                    'class' => 'form-control  ',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    ));
       
       $stateTab=array();
        if(count($subjectStates)>0):
            foreach($subjectStates as $state):
                $stateTab[$state->getId()]=$state->getName();
            endforeach;
        endif; 
      $this->widgetSchema['state']= new sfWidgetFormChoice(array( 
        'choices'  =>  $stateTab,
        'multiple' => true,
        'expanded' => true, 
        'renderer_options' => array('formatter' => array('subjectSearchForm', 'MyFormatter'))),
        array( 'id' => 'search_subject_by_state', 
               'class' => 'search_subject_by_state  ' ));
      
      $priorTab=array();
        if(count($subjectPriorities)>0):
            foreach($subjectPriorities as $prior):
                $priorTab[$prior->getId()]=$prior->getName();
            endforeach;
        endif; 
      $this->widgetSchema['priority']= new sfWidgetFormChoice(array( 
        'choices'  =>  $priorTab,
        'multiple' => true,
        'expanded' => true,
        'renderer_options' => array('formatter' => array('subjectSearchForm', 'MyFormatter'))) ,
        array( 'id' => 'search_subject_by_priority' , 
               'class' => 'search_subject_by_priority  '));
      
      $typesTab=array();
        if(count($subjectTypes)>0):
            foreach($subjectTypes as $type):
                $typesTab[$type->getId()]=$type->getName();
            endforeach;
        endif;
      $this->widgetSchema['type']= new sfWidgetFormChoice(array( 
        'choices'  =>  $typesTab,
        'multiple' => true,
        'expanded' => true,
        'renderer_options' => array('formatter' => array('subjectSearchForm', 'MyFormatter'))),
        array( 'id' => 'search_subject_by_type', 
               'class' => 'search_subject_by_type ' ));
      
           
      $this->widgetSchema['delivery']= new sfWidgetFormChoice(array( 
        'choices'  =>  $deliveries,
        'default' => ($ei_delivery!=null)?$ei_delivery->getId():null,  
        'multiple' => false,
        'expanded' => false, 
        //'renderer_options' => array('formatter' => array('subjectSearchForm', 'MyFormatter'))
          ),
        array( 'id' => 'search_subject_by_delivery', 
               'class' => 'search_subject_by_delivery' ));
      
      
      
       
       
      //Validateurs de recherche
      $this->validatorSchema['state'] = new sfValidatorChoice(
              array('choices' => array_keys($stateTab),
                    'required' => false));
       
      $this->validatorSchema['delivery_state'] = new sfValidatorChoice(
              array('choices' => array_keys($deliveries),
                    'required' => false));
       
      $this->validatorSchema['type'] = new sfValidatorChoice(
              array('choices' => array_keys($typesTab),
                    'required' => false));
      
      $this->validatorSchema['priority'] = new sfValidatorChoice(
              array('choices' => array_keys($priorTab),
                    'required' => false));
       
      //Définition du nom du formulaire
      $this->widgetSchema->setNameFormat('subjectSearch[%s]');
      
    }
    public static function MyFormatter($widget, $inputs) {  
            $result='';
            foreach ($inputs as  $input) {    
               //$result .= '<br/> ' . $input ['input']  . '   ' . $input ['label']. '';  
                $result .='<ul  class='.$widget->getAttribute('class').'><li>   ' . $input ['input'] . '</li><li>' . $input ['label'] . '</li></ul>';
            } 
             return $result; 
       
}
}

?>
