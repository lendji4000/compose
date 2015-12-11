<?php

/**
 * ScriptEiFunctionHasParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiFunctionHasParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'function_ref'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'function_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'param_type'    => new sfWidgetFormFilterInput(),
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'   => new sfWidgetFormFilterInput(),
      'default_value' => new sfWidgetFormFilterInput(),
      'is_compulsory' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'delta'         => new sfWidgetFormFilterInput(),
      'deltaf'        => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'function_ref'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'param_type'    => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'description'   => new sfValidatorPass(array('required' => false)),
      'default_value' => new sfValidatorPass(array('required' => false)),
      'is_compulsory' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'delta'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deltaf'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_function_has_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiFunctionHasParam';
  }

  public function getFields()
  {
    return array(
      'param_id'      => 'Number',
      'function_ref'  => 'Number',
      'function_id'   => 'Number',
      'param_type'    => 'Text',
      'name'          => 'Text',
      'description'   => 'Text',
      'default_value' => 'Text',
      'is_compulsory' => 'Boolean',
      'delta'         => 'Number',
      'deltaf'        => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
