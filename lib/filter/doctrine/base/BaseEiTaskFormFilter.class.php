<?php

/**
 * EiTask filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTaskFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'task_state_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTaskState'), 'add_empty' => true)),
      'project_id'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'expected_start_date' => new sfWidgetFormFilterInput(),
      'expected_end_date'   => new sfWidgetFormFilterInput(),
      'expected_delay'      => new sfWidgetFormFilterInput(),
      'expected_duration'   => new sfWidgetFormFilterInput(),
      'to_plan'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'plan_start_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'author_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'task_state_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTaskState'), 'column' => 'id')),
      'project_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'expected_start_date' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'expected_end_date'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'expected_delay'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'expected_duration'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'to_plan'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'plan_start_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_task_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTask';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'author_id'           => 'ForeignKey',
      'task_state_id'       => 'ForeignKey',
      'project_id'          => 'Number',
      'project_ref'         => 'Number',
      'name'                => 'Text',
      'description'         => 'Text',
      'expected_start_date' => 'Number',
      'expected_end_date'   => 'Number',
      'expected_delay'      => 'Number',
      'expected_duration'   => 'Number',
      'to_plan'             => 'Boolean',
      'plan_start_date'     => 'Date',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
