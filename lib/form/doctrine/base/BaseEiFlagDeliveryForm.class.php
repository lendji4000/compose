<?php

/**
 * EiFlagDelivery form base class.
 *
 * @method EiFlagDelivery getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiFlagDeliveryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'campaign_id' => new sfWidgetFormInputHidden(),
      'delivery_id' => new sfWidgetFormInputHidden(),
      'state'       => new sfWidgetFormChoice(array('choices' => array('Blank' => 'Blank', 'Ok' => 'Ok', 'Ko' => 'Ko', 'Warning' => 'Warning'))),
      'description' => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'campaign_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('campaign_id')), 'empty_value' => $this->getObject()->get('campaign_id'), 'required' => false)),
      'delivery_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('delivery_id')), 'empty_value' => $this->getObject()->get('delivery_id'), 'required' => false)),
      'state'       => new sfValidatorChoice(array('choices' => array(0 => 'Blank', 1 => 'Ok', 2 => 'Ko', 3 => 'Warning'), 'required' => false)),
      'description' => new sfValidatorString(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_flag_delivery[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiFlagDelivery';
  }

}
