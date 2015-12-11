<?php

/**
 * EiProjet form.
 *
 * @package    kalifast
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiProjetForm extends BaseEiProjetForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at']);
      //parent::configure();
      $this->widgetSchema['libelle'] = new sfWidgetFormInputText();
      $this->validatorSchema['libelle']=new sfValidatorString(array('max_length' => 255, 'required' => true ));
      //vérification de l'unicité du libelle 
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(
	array(
	  new sfValidatorCallback(array('callback'=> array($this, 'checkAvailability')))
           ))
        );
  }
  public function checkAvailability($validator, $values) {
      if (! empty($values['libelle'])) {
              $projet=Doctrine_Core::getTable("EiProjet")->findOneBy('libelle',$values['libelle']);
              if($projet==null){
                  return $values;
              }
              }
    
}
}
