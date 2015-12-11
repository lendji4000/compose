<?php

/**
 * EifunctionCampaigns form base class.
 *
 * @method EifunctionCampaigns getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEifunctionCampaignsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'campaign_id'  => new sfWidgetFormInputHidden(),
      'function_id'  => new sfWidgetFormInputHidden(),
      'function_ref' => new sfWidgetFormInputHidden(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'campaign_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('campaign_id')), 'empty_value' => $this->getObject()->get('campaign_id'), 'required' => false)),
      'function_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('function_id')), 'empty_value' => $this->getObject()->get('function_id'), 'required' => false)),
      'function_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('function_ref')), 'empty_value' => $this->getObject()->get('function_ref'), 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('eifunction_campaigns[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EifunctionCampaigns';
  }

}
