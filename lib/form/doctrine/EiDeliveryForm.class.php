<?php

/**
 * EiDelivery form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDeliveryForm extends BaseEiDeliveryForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at'],$this['author_id']);
      $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['description']->setAttribute('class', 'deliveryDescription form-control col-lg-8 col-md-8 col-sm-8' ); 
      $this->widgetSchema['name']->setAttribute('class', ' form-control col-lg-10 col-md-10 col-sm-10' );
      $this->widgetSchema['description']->setAttribute('placeholder', 'Delivery description ...');
      //Date de debut 
      $this->widgetSchema['delivery_date'] = new sfWidgetFormInputText(array(), 
              array('class' => 'form-control col-lg-8 col-md-8 col-sm-8',
                    'data-format'=>"yyyy/MM/dd")); 
      
            $project_id = $this->getObject()->getProjectId();
            $project_ref = $this->getObject()->getProjectRef();
      //Récupération des statuts de livraison d'un projet 
        $this->widgetSchema['delivery_state_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiDeliveryState', 'multiple' => false,
                      'query' => Doctrine_Core::getTable('EiDeliveryState')
                                    ->getDeliveryStateForProjectQuery($project_id, $project_ref),
                      'add_empty' => false),
                array('class' => ' form-control col-lg-8 col-md-8 col-sm-8'));
        
      $this->validatorSchema['delivery_date']=new sfValidatorString(); 
      
      $this->validatorSchema->setPostValidator(
                    new sfValidatorCallback(array('callback' => array($this, 'checkIfAllSubjectAreClose')))
            );  
  }
  //On vérifie que tous les sujets sont clos ou invalides pour passer la livraison en statut clos 
  function checkIfAllSubjectAreClose($validator, $values, $arguments) { 
      if($this->getObject()->isNew()) return $values; // C'est une nouvelle livraison , on retourne les valeurs du formulaire
        $closeStates=Doctrine_Core::getTable('EiDeliveryState')->findByProjectIdAndProjectRefAndCloseState(
                $values['project_id'], $values['project_ref'],1);   //Statuts de livraison clos 
        /* Si le statut de  livraison est different de statut "Clos" , on retourne les valeurs */
        if(count($closeStates)>0):
            $k=0;
            foreach($closeStates as $closeState):
             if($values['delivery_state_id']==$closeState->getId())   $k++;
            endforeach;
        endif;      
        if($k==0) return $values; //Si la livraison ne passe pas en statut close  
        
        //On récupère les bugs de la livraison
        $ei_delivery_subjects=$this->getObject()->getDeliverySubjectsQuery()->andWhere("st.close_del_state=0")->fetchArray();
        if(count($ei_delivery_subjects)>0):   
            throw new sfValidatorError($validator, 
                         'Can\'t close delivery because there are subjects still opened')  ; 
        endif; 
        return $values;
    }
}
