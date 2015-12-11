<?php

/**
 * EiCampaignGraph form base class.
 *
 * @method EiCampaignGraph getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignGraphForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'campaign_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => false)),
      'scenario_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'data_set_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'step_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraphType'), 'add_empty' => false)),
      'filename'     => new sfWidgetFormInputText(),
      'path'         => new sfWidgetFormTextarea(),
      'mime_type'    => new sfWidgetFormInputText(),
      'state'        => new sfWidgetFormChoice(array('choices' => array('Blank' => 'Blank', 'Ok' => 'Ok', 'Ko' => 'Ko', 'Processing' => 'Processing'))),
      'description'  => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'campaign_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'))),
      'scenario_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'required' => false)),
      'data_set_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'step_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraphType'))),
      'filename'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'path'         => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'mime_type'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'state'        => new sfValidatorChoice(array('choices' => array(0 => 'Blank', 1 => 'Ok', 2 => 'Ko', 3 => 'Processing'), 'required' => false)),
      'description'  => new sfValidatorString(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_graph[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignGraph';
  }

}
