<?php

/**
 * EiCampaignExecutionGraph filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignExecutionGraphFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'execution_id'   => new sfWidgetFormFilterInput(),
      'position'       => new sfWidgetFormFilterInput(),
      'graph_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraph'), 'add_empty' => true)),
      'scenario_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'version_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => true)),
      'data_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'step_type_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignGraphType'), 'add_empty' => true)),
      'filename'       => new sfWidgetFormFilterInput(),
      'path'           => new sfWidgetFormFilterInput(),
      'mime_type'      => new sfWidgetFormFilterInput(),
      'description'    => new sfWidgetFormFilterInput(),
      'state'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'Blank' => 'Blank', 'Ok' => 'Ok', 'Ko' => 'Ko', 'Processing' => 'Processing', 'Aborted' => 'Aborted'))),
      'ei_test_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'execution_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'graph_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiCampaignGraph'), 'column' => 'id')),
      'scenario_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'version_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersion'), 'column' => 'id')),
      'data_set_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSet'), 'column' => 'id')),
      'step_type_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiCampaignGraphType'), 'column' => 'id')),
      'filename'       => new sfValidatorPass(array('required' => false)),
      'path'           => new sfValidatorPass(array('required' => false)),
      'mime_type'      => new sfValidatorPass(array('required' => false)),
      'description'    => new sfValidatorPass(array('required' => false)),
      'state'          => new sfValidatorChoice(array('required' => false, 'choices' => array('Blank' => 'Blank', 'Ok' => 'Ok', 'Ko' => 'Ko', 'Processing' => 'Processing', 'Aborted' => 'Aborted'))),
      'ei_test_set_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_execution_graph_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignExecutionGraph';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'execution_id'   => 'Number',
      'position'       => 'Number',
      'graph_id'       => 'ForeignKey',
      'scenario_id'    => 'ForeignKey',
      'version_id'     => 'ForeignKey',
      'data_set_id'    => 'ForeignKey',
      'step_type_id'   => 'ForeignKey',
      'filename'       => 'Text',
      'path'           => 'Text',
      'mime_type'      => 'Text',
      'description'    => 'Text',
      'state'          => 'Enum',
      'ei_test_set_id' => 'ForeignKey',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
