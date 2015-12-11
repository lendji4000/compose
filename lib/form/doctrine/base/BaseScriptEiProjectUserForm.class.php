<?php

/**
 * ScriptEiProjectUser form base class.
 *
 * @method ScriptEiProjectUser getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiProjectUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id'  => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormInputHidden(),
      'project_ref' => new sfWidgetFormInputHidden(),
      'user_ref'    => new sfWidgetFormInputHidden(),
      'role'        => new sfWidgetFormInputText(),
      'delta'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'project_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'user_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'project_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_ref')), 'empty_value' => $this->getObject()->get('project_ref'), 'required' => false)),
      'user_ref'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'role'        => new sfValidatorString(array('max_length' => 255)),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_project_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiProjectUser';
  }

}
