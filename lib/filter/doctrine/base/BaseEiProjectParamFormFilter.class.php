<?php

/**
 * EiProjectParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiProjectParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_ref'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'param_type'       => new sfWidgetFormChoice(array('choices' => array('' => '', 'IN' => 'IN', 'OUT' => 'OUT', 'SONDE' => 'SONDE'))),
      'param_visibility' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'ei_table_name'    => new sfWidgetFormFilterInput(),
      'ei_column_name'   => new sfWidgetFormFilterInput(),
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'      => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'project_ref'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'param_type'       => new sfValidatorChoice(array('required' => false, 'choices' => array('IN' => 'IN', 'OUT' => 'OUT', 'SONDE' => 'SONDE'))),
      'param_visibility' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'ei_table_name'    => new sfValidatorPass(array('required' => false)),
      'ei_column_name'   => new sfValidatorPass(array('required' => false)),
      'name'             => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_project_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiProjectParam';
  }

  public function getFields()
  {
    return array(
      'param_id'         => 'Number',
      'project_ref'      => 'Number',
      'project_id'       => 'Number',
      'param_type'       => 'Enum',
      'param_visibility' => 'Boolean',
      'ei_table_name'    => 'Text',
      'ei_column_name'   => 'Text',
      'name'             => 'Text',
      'description'      => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
