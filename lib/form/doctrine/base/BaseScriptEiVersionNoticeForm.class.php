<?php

/**
 * ScriptEiVersionNotice form base class.
 *
 * @method ScriptEiVersionNotice getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiVersionNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'version_notice_id' => new sfWidgetFormInputHidden(),
      'notice_id'         => new sfWidgetFormInputHidden(),
      'notice_ref'        => new sfWidgetFormInputHidden(),
      'lang'              => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'expected'          => new sfWidgetFormTextarea(),
      'result'            => new sfWidgetFormTextarea(),
      'is_active'         => new sfWidgetFormInputCheckbox(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'version_notice_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('version_notice_id')), 'empty_value' => $this->getObject()->get('version_notice_id'), 'required' => false)),
      'notice_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_id')), 'empty_value' => $this->getObject()->get('notice_id'), 'required' => false)),
      'notice_ref'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_ref')), 'empty_value' => $this->getObject()->get('notice_ref'), 'required' => false)),
      'lang'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('lang')), 'empty_value' => $this->getObject()->get('lang'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 45)),
      'description'       => new sfValidatorString(array('required' => false)),
      'expected'          => new sfValidatorString(array('required' => false)),
      'result'            => new sfValidatorString(array('required' => false)),
      'is_active'         => new sfValidatorBoolean(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_version_notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiVersionNotice';
  }

}
