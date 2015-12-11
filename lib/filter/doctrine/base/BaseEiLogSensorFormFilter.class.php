<?php

/**
 * EiLogSensor filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiLogSensorFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_log_function_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => true)),
      'app_memory_mean'     => new sfWidgetFormFilterInput(),
      'app_memory_min'      => new sfWidgetFormFilterInput(),
      'app_memory_max'      => new sfWidgetFormFilterInput(),
      'app_memory_start'    => new sfWidgetFormFilterInput(),
      'app_memory_end'      => new sfWidgetFormFilterInput(),
      'app_cpu_mean'        => new sfWidgetFormFilterInput(),
      'app_cpu_min'         => new sfWidgetFormFilterInput(),
      'app_cpu_max'         => new sfWidgetFormFilterInput(),
      'app_cpu_start'       => new sfWidgetFormFilterInput(),
      'app_cpu_end'         => new sfWidgetFormFilterInput(),
      'db_memory_mean'      => new sfWidgetFormFilterInput(),
      'db_memory_min'       => new sfWidgetFormFilterInput(),
      'db_memory_max'       => new sfWidgetFormFilterInput(),
      'db_memory_start'     => new sfWidgetFormFilterInput(),
      'db_memory_end'       => new sfWidgetFormFilterInput(),
      'db_cpu_mean'         => new sfWidgetFormFilterInput(),
      'db_cpu_min'          => new sfWidgetFormFilterInput(),
      'db_cpu_max'          => new sfWidgetFormFilterInput(),
      'db_cpu_start'        => new sfWidgetFormFilterInput(),
      'db_cpu_end'          => new sfWidgetFormFilterInput(),
      'client_memory_mean'  => new sfWidgetFormFilterInput(),
      'client_memory_min'   => new sfWidgetFormFilterInput(),
      'client_memory_max'   => new sfWidgetFormFilterInput(),
      'client_memory_start' => new sfWidgetFormFilterInput(),
      'client_memory_end'   => new sfWidgetFormFilterInput(),
      'client_cpu_mean'     => new sfWidgetFormFilterInput(),
      'client_cpu_min'      => new sfWidgetFormFilterInput(),
      'client_cpu_max'      => new sfWidgetFormFilterInput(),
      'client_cpu_start'    => new sfWidgetFormFilterInput(),
      'client_cpu_end'      => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_log_function_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLogFunction'), 'column' => 'id')),
      'app_memory_mean'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_memory_min'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_memory_max'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_memory_start'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_memory_end'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_cpu_mean'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_cpu_min'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_cpu_max'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_cpu_start'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'app_cpu_end'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_memory_mean'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_memory_min'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_memory_max'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_memory_start'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_memory_end'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_cpu_mean'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_cpu_min'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_cpu_max'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_cpu_start'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_cpu_end'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_memory_mean'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_memory_min'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_memory_max'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_memory_start' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_memory_end'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_cpu_mean'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_cpu_min'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_cpu_max'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_cpu_start'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'client_cpu_end'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_log_sensor_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogSensor';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'ei_log_function_id'  => 'ForeignKey',
      'app_memory_mean'     => 'Number',
      'app_memory_min'      => 'Number',
      'app_memory_max'      => 'Number',
      'app_memory_start'    => 'Number',
      'app_memory_end'      => 'Number',
      'app_cpu_mean'        => 'Number',
      'app_cpu_min'         => 'Number',
      'app_cpu_max'         => 'Number',
      'app_cpu_start'       => 'Number',
      'app_cpu_end'         => 'Number',
      'db_memory_mean'      => 'Number',
      'db_memory_min'       => 'Number',
      'db_memory_max'       => 'Number',
      'db_memory_start'     => 'Number',
      'db_memory_end'       => 'Number',
      'db_cpu_mean'         => 'Number',
      'db_cpu_min'          => 'Number',
      'db_cpu_max'          => 'Number',
      'db_cpu_start'        => 'Number',
      'db_cpu_end'          => 'Number',
      'client_memory_mean'  => 'Number',
      'client_memory_min'   => 'Number',
      'client_memory_max'   => 'Number',
      'client_memory_start' => 'Number',
      'client_memory_end'   => 'Number',
      'client_cpu_mean'     => 'Number',
      'client_cpu_min'      => 'Number',
      'client_cpu_max'      => 'Number',
      'client_cpu_start'    => 'Number',
      'client_cpu_end'      => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
