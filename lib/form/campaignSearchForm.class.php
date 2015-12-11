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
class campaignSearchForm extends sfForm {
    public function configure() {   
      $projectDeliveries=$this->getOption('projectDeliveries');
      
      $this->widgetSchema['title'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_campaign_by_title'
                    ));  
      
      $this->widgetSchema['author'] = new sfWidgetFormInputText(
              array(),
              array('id'=> 'search_campaign_by_author', 
                    'data-provide'=> "typeahead" ,
                    'data-items' =>'4', 
                    )); 
      //Widget pour les champs de recherche
      $this->widgetSchema['delivery']    = new sfWidgetFormChoice(
              array('choices' => $projectDeliveries),
              array( 'id' => 'search_campaign_by_delivery' ));
       
      //Validateurs de recherche  
      $this->validatorSchema['delivery'] = new sfValidatorChoice(
              array('choices' => array_keys($projectDeliveries),
                    'required' => false));
       
       
      
      //Définition du nom du formulaire
      $this->widgetSchema->setNameFormat('campaignSearch[%s]');
      
    }
}

?>
