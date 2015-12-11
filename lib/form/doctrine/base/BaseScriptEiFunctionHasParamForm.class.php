<?php

/**
 * ScriptEiFunctionHasParam form base class.
 *
 * @method ScriptEiFunctionHasParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiFunctionHasParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'param_id'      => new sfWidgetFormInputHidden(),
      'function_ref'  => new sfWidgetFormInputText(),
      'function_id'   => new sfWidgetFormInputText(),
      'param_type'    => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'description'   => new sfWidgetFormTextarea(),
      'default_value' => new sfWidgetFormTextarea(),
      'is_compulsory' => new sfWidgetFormInputCheckbox(),
      'delta'         => new sfWidgetFormInputText(),
      'deltaf'        => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'param_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('param_id')), 'empty_value' => $this->getObject()->get('param_id'), 'required' => false)),
      'function_ref'  => new sfValidatorInteger(),
      'function_id'   => new sfValidatorInteger(),
      'param_type'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 45)),
      'description'   => new sfValidatorString(array('required' => false)),
      'default_value' => new sfValidatorString(array('required' => false)),
      'is_compulsory' => new sfValidatorBoolean(),
      'delta'         => new sfValidatorInteger(array('required' => false)),
      'deltaf'        => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_function_has_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiFunctionHasParam';
  }

}
