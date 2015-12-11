<?php

/**
 * EiCampaignExecutionGraph form base class.
 *
 * @method EiCampaignExecutionGraph getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignExecutionGraphForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'execution_id'   => new sfWidgetFormInputText(),
      'position'       => new sfWidgetFormInputText(),
      'graph_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraph'), 'add_empty' => true)),
      'scenario_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'version_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => true)),
      'data_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'step_type_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraphType'), 'add_empty' => false)),
      'filename'       => new sfWidgetFormInputText(),
      'path'           => new sfWidgetFormTextarea(),
      'mime_type'      => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormTextarea(),
      'state'          => new sfWidgetFormChoice(array('choices' => array('Blank' => 'Blank', 'Ok' => 'Ok', 'Ko' => 'Ko', 'Processing' => 'Processing', 'Aborted' => 'Aborted'))),
      'ei_test_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'execution_id'   => new sfValidatorInteger(array('required' => false)),
      'position'       => new sfValidatorInteger(array('required' => false)),
      'graph_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraph'), 'required' => false)),
      'scenario_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'required' => false)),
      'version_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'required' => false)),
      'data_set_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'step_type_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraphType'))),
      'filename'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'path'           => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'mime_type'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'    => new sfValidatorString(array('required' => false)),
      'state'          => new sfValidatorChoice(array('choices' => array(0 => 'Blank', 1 => 'Ok', 2 => 'Ko', 3 => 'Processing', 4 => 'Aborted'), 'required' => false)),
      'ei_test_set_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_execution_graph[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignExecutionGraph';
  }

}
