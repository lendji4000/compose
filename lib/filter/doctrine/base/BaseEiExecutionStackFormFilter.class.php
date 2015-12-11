<?php

/**
 * EiExecutionStack filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiExecutionStackFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_scenario_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_data_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'ei_campaign_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => true)),
      'project_ref'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_id'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'profile_ref'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'profile_id'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'start_pos'                => new sfWidgetFormFilterInput(),
      'end_pos'                  => new sfWidgetFormFilterInput(),
      'synchronous'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'status'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ei_test_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_campaign_execution_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignExecution'), 'add_empty' => true)),
      'robot'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'device'                   => new sfWidgetFormChoice(array('choices' => array('' => '', 'SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari', 'Raspberry' => 'Raspberry'))),
      'device_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDevice'), 'add_empty' => true)),
      'driver_id'                => new sfWidgetFormFilterInput(),
      'browser_id'               => new sfWidgetFormFilterInput(),
      'expected_date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_scenario_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'ei_data_set_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSet'), 'column' => 'id')),
      'ei_campaign_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiCampaign'), 'column' => 'id')),
      'project_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'                  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'start_pos'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_pos'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'synchronous'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'status'                   => new sfValidatorPass(array('required' => false)),
      'ei_test_set_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_campaign_execution_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiCampaignExecution'), 'column' => 'id')),
      'robot'                    => new sfValidatorPass(array('required' => false)),
      'device'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari', 'Raspberry' => 'Raspberry'))),
      'device_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDevice'), 'column' => 'id')),
      'driver_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'browser_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expected_date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_execution_stack_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiExecutionStack';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'ei_scenario_id'           => 'ForeignKey',
      'ei_data_set_id'           => 'ForeignKey',
      'ei_campaign_id'           => 'ForeignKey',
      'project_ref'              => 'Number',
      'project_id'               => 'Number',
      'profile_ref'              => 'Number',
      'profile_id'               => 'Number',
      'user_id'                  => 'ForeignKey',
      'start_pos'                => 'Number',
      'end_pos'                  => 'Number',
      'synchronous'              => 'Boolean',
      'status'                   => 'Text',
      'ei_test_set_id'           => 'ForeignKey',
      'ei_campaign_execution_id' => 'ForeignKey',
      'robot'                    => 'Text',
      'device'                   => 'Enum',
      'device_id'                => 'ForeignKey',
      'driver_id'                => 'Number',
      'browser_id'               => 'Number',
      'expected_date'            => 'Date',
      'created_at'               => 'Date',
      'updated_at'               => 'Date',
    );
  }
}
