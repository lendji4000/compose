<?php

/**
 * EiScriptVersion form base class.
 *
 * @method EiScriptVersion getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiScriptVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'script_id'   => new sfWidgetFormInputHidden(),
      'profile_ref' => new sfWidgetFormInputHidden(),
      'profile_id'  => new sfWidgetFormInputHidden(),
      'num_version' => new sfWidgetFormInputText(),
      'project_ref' => new sfWidgetFormInputText(),
      'project_id'  => new sfWidgetFormInputText(),
      'delta'       => new sfWidgetFormInputText(),
      'deltaf'      => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'script_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('script_id')), 'empty_value' => $this->getObject()->get('script_id'), 'required' => false)),
      'profile_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_ref')), 'empty_value' => $this->getObject()->get('profile_ref'), 'required' => false)),
      'profile_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'num_version' => new sfValidatorInteger(),
      'project_ref' => new sfValidatorInteger(),
      'project_id'  => new sfValidatorInteger(),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'deltaf'      => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_script_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScriptVersion';
  }

}
