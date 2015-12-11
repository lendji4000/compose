<?php

/**
 * EiNotice form base class.
 *
 * @method EiNotice getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'notice_id'    => new sfWidgetFormInputHidden(),
      'notice_ref'   => new sfWidgetFormInputHidden(),
      'function_id'  => new sfWidgetFormInputText(),
      'function_ref' => new sfWidgetFormInputText(),
      'name'         => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'notice_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_id')), 'empty_value' => $this->getObject()->get('notice_id'), 'required' => false)),
      'notice_ref'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_ref')), 'empty_value' => $this->getObject()->get('notice_ref'), 'required' => false)),
      'function_id'  => new sfValidatorInteger(array('required' => false)),
      'function_ref' => new sfValidatorInteger(array('required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 45)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiNotice';
  }

}
