<?php

/**
 * EiScript form base class.
 *
 * @method EiScript getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiScriptForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'script_id'    => new sfWidgetFormInputHidden(),
      'ticket_ref'   => new sfWidgetFormInputText(),
      'ticket_id'    => new sfWidgetFormInputText(),
      'num_version'  => new sfWidgetFormInputText(),
      'description'  => new sfWidgetFormTextarea(),
      'remark'       => new sfWidgetFormInputText(),
      'function_ref' => new sfWidgetFormInputText(),
      'function_id'  => new sfWidgetFormInputText(),
      'delta'        => new sfWidgetFormInputText(),
      'deltaf'       => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'script_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('script_id')), 'empty_value' => $this->getObject()->get('script_id'), 'required' => false)),
      'ticket_ref'   => new sfValidatorInteger(),
      'ticket_id'    => new sfValidatorInteger(),
      'num_version'  => new sfValidatorInteger(),
      'description'  => new sfValidatorString(array('required' => false)),
      'remark'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'function_ref' => new sfValidatorInteger(),
      'function_id'  => new sfValidatorInteger(),
      'delta'        => new sfValidatorInteger(array('required' => false)),
      'deltaf'       => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_script[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScript';
  }

}
