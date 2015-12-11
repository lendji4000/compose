<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of iterationSearchStatsForm
 *
 * @author lenine
 */
class iterationSearchStatsForm extends sfForm {
    public function configure() {   
        $deliveries=$this->getOption('deliveries'); 
        $ei_profiles=$this->getOption('ei_profiles');
        
      $this->widgetSchema['environment']= new sfWidgetFormChoice(array( 
        'choices'  =>  $ei_profiles,  
        'multiple' => false,
        'expanded' => false
          ),
        array( 'id' => 'search_iteration_by_environment', 
               'class' => 'search_iteration_by_environment form-control' ));
      $this->widgetSchema['author'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_iteration_by_author',
                  'class' => 'form-control  ',
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    )); 
      $this->widgetSchema['is_active'] = new sfWidgetFormInputCheckbox(
              array(),
              array('id'=> 'search_iteration_by_active',
                  'class' => 'form-control search_iteration_by_active '  
                    )); 
        
      
           
      $this->widgetSchema['delivery']= new sfWidgetFormChoice(array( 
        'choices'  =>  $deliveries, 
        'multiple' => false,
        'expanded' => false
          ),
        array( 'id' => 'search_iteration_by_delivery', 
               'class' => 'search_iteration_by_delivery form-control' ));
       
      //Définition du nom du formulaire
      $this->widgetSchema->setNameFormat('iterationSearchStats[%s]');
      
    } 
}

?>
