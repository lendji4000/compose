<?php

/**
 * EiUserSettings form base class.
 *
 * @method EiUserSettings getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiUserSettingsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'     => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormInputHidden(),
      'firefox_path' => new sfWidgetFormTextarea(),
      'excel_mode'   => new sfWidgetFormChoice(array('choices' => array('file' => 'file', 'normal' => 'normal'))),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'user_ref'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'user_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'firefox_path' => new sfValidatorString(),
      'excel_mode'   => new sfValidatorChoice(array('choices' => array(0 => 'file', 1 => 'normal'), 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_user_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiUserSettings';
  }

}
