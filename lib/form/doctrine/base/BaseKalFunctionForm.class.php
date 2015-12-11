<?php

/**
 * KalFunction form base class.
 *
 * @method KalFunction getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKalFunctionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'function_ref' => new sfWidgetFormInputHidden(),
      'function_id'  => new sfWidgetFormInputHidden(),
      'project_ref'  => new sfWidgetFormInputText(),
      'project_id'   => new sfWidgetFormInputText(),
      'description'  => new sfWidgetFormTextarea(),
      'is_active'    => new sfWidgetFormInputCheckbox(),
      'criticity'    => new sfWidgetFormChoice(array('choices' => array('High' => 'High', 'Medium' => 'Medium', 'Low' => 'Low', 'Blank' => 'Blank'))),
      'delta'        => new sfWidgetFormInputText(),
      'deltaf'       => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'function_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('function_ref')), 'empty_value' => $this->getObject()->get('function_ref'), 'required' => false)),
      'function_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('function_id')), 'empty_value' => $this->getObject()->get('function_id'), 'required' => false)),
      'project_ref'  => new sfValidatorInteger(),
      'project_id'   => new sfValidatorInteger(),
      'description'  => new sfValidatorString(array('required' => false)),
      'is_active'    => new sfValidatorBoolean(array('required' => false)),
      'criticity'    => new sfValidatorChoice(array('choices' => array(0 => 'High', 1 => 'Medium', 2 => 'Low', 3 => 'Blank'), 'required' => false)),
      'delta'        => new sfValidatorInteger(array('required' => false)),
      'deltaf'       => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kal_function[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KalFunction';
  }

}
