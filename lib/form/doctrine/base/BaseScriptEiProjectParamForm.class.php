<?php

/**
 * ScriptEiProjectParam form base class.
 *
 * @method ScriptEiProjectParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiProjectParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'param_id'    => new sfWidgetFormInputHidden(),
      'project_ref' => new sfWidgetFormInputText(),
      'project_id'  => new sfWidgetFormInputText(),
      'name'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'param_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('param_id')), 'empty_value' => $this->getObject()->get('param_id'), 'required' => false)),
      'project_ref' => new sfValidatorInteger(),
      'project_id'  => new sfValidatorInteger(),
      'name'        => new sfValidatorString(array('max_length' => 45)),
      'description' => new sfValidatorString(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_project_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiProjectParam';
  }

}
