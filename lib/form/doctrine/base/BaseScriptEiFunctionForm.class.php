<?php

/**
 * ScriptEiFunction form base class.
 *
 * @method ScriptEiFunction getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiFunctionForm extends BaseFormDoctrine
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
      'delta'        => new sfValidatorInteger(array('required' => false)),
      'deltaf'       => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_function[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiFunction';
  }

}
