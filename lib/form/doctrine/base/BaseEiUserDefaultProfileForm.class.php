<?php

/**
 * EiUserDefaultProfile form base class.
 *
 * @method EiUserDefaultProfile getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiUserDefaultProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'    => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormInputHidden(),
      'project_ref' => new sfWidgetFormInputHidden(),
      'project_id'  => new sfWidgetFormInputHidden(),
      'profile_ref' => new sfWidgetFormInputText(),
      'profile_id'  => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'user_ref'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'user_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'project_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_ref')), 'empty_value' => $this->getObject()->get('project_ref'), 'required' => false)),
      'project_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'profile_ref' => new sfValidatorInteger(),
      'profile_id'  => new sfValidatorInteger(),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_user_default_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiUserDefaultProfile';
  }

}
