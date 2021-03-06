<?php

/**
 * EiScript filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiScriptFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ticket_ref'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ticket_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'num_version'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'  => new sfWidgetFormFilterInput(),
      'remark'       => new sfWidgetFormFilterInput(),
      'function_ref' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'function_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delta'        => new sfWidgetFormFilterInput(),
      'deltaf'       => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ticket_ref'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ticket_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num_version'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description'  => new sfValidatorPass(array('required' => false)),
      'remark'       => new sfValidatorPass(array('required' => false)),
      'function_ref' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'delta'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deltaf'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_script_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScript';
  }

  public function getFields()
  {
    return array(
      'script_id'    => 'Number',
      'ticket_ref'   => 'Number',
      'ticket_id'    => 'Number',
      'num_version'  => 'Number',
      'description'  => 'Text',
      'remark'       => 'Text',
      'function_ref' => 'Number',
      'function_id'  => 'Number',
      'delta'        => 'Number',
      'deltaf'       => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
