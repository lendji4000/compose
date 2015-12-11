<?php

/**
 * EiFunctionNotice form base class.
 *
 * @method EiFunctionNotice getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiFunctionNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_version_id'  => new sfWidgetFormInputHidden(),
      'ei_fonction_id' => new sfWidgetFormInputHidden(),
      'lang'           => new sfWidgetFormInputHidden(),
      'description'    => new sfWidgetFormTextarea(),
      'expected'       => new sfWidgetFormTextarea(),
      'result'         => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ei_version_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_version_id')), 'empty_value' => $this->getObject()->get('ei_version_id'), 'required' => false)),
      'ei_fonction_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_fonction_id')), 'empty_value' => $this->getObject()->get('ei_fonction_id'), 'required' => false)),
      'lang'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('lang')), 'empty_value' => $this->getObject()->get('lang'), 'required' => false)),
      'description'    => new sfValidatorString(array('required' => false)),
      'expected'       => new sfValidatorString(array('required' => false)),
      'result'         => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_function_notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiFunctionNotice';
  }

}
