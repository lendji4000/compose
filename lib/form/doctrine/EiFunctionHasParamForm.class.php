<?php

/**
 * EiFunctionHasParam form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiFunctionHasParamForm extends BaseEiFunctionHasParamForm
{
  public function configure()
  {
      unset(  $this['created_at'],$this['updated_at'],$this['delta'],$this['deltaf'],
              $this['is_compulsory'],$this['function_id'],$this['function_ref']); 
      
      $this->widgetSchema['param_type'] = new sfWidgetFormInputHidden();
      
      $this->widgetSchema['description']->setAttributes(array(
          'class'=>'col-lg-12 col-md-12 desc_param form-control',
          'placeholder' => "Description  ...",
          'style' => 'height:30px'));
      $this->widgetSchema['default_value']->setAttributes(array(
          'class'=>'col-lg-12 col-md-12 val_param form-control',
          'placeholder' => "Default value ...",
          'style' => 'height:30px'));
      $this->widgetSchema['name']->setAttributes(array(
          'class'=>'col-lg-12 col-md-12 nom_param form-control',
          'placeholder' => "Name...",
          'style' => 'height:30px')); 
      
      $this->validatorSchema['name'] = new sfValidatorAnd(
                        array(new sfValidatorString(
                                    array('required' => true, 'trim' => true),
                                    array()), new sfValidatorRegex(array('pattern' => '/^[a-zA-Z0-9-]+$/'))),
                        array(), array('required' => "Name is required", 'invalid' => "Name could not contain special characters.Space also ..." ) );
  }
}
