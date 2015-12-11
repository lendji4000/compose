<?php

/**
 * EiLogParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiLogParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_log_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => true)),
      'function_id'        => new sfWidgetFormFilterInput(),
      'function_ref'       => new sfWidgetFormFilterInput(),
      'ei_test_set_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_log_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => true)),
      'ei_param_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiParam'), 'add_empty' => true)),
      'param_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'), 'add_empty' => true)),
      'iteration_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'param_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'param_valeur'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'param_type'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_log_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLog'), 'column' => 'id')),
      'function_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ei_test_set_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_log_function_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLogFunction'), 'column' => 'id')),
      'ei_param_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiParam'), 'column' => 'id')),
      'param_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFunctionHasParam'), 'column' => 'param_id')),
      'iteration_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiIteration'), 'column' => 'id')),
      'param_name'         => new sfValidatorPass(array('required' => false)),
      'param_valeur'       => new sfValidatorPass(array('required' => false)),
      'param_type'         => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_log_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogParam';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'ei_log_id'          => 'ForeignKey',
      'function_id'        => 'Number',
      'function_ref'       => 'Number',
      'ei_test_set_id'     => 'ForeignKey',
      'ei_log_function_id' => 'ForeignKey',
      'ei_param_id'        => 'ForeignKey',
      'param_id'           => 'ForeignKey',
      'iteration_id'       => 'ForeignKey',
      'param_name'         => 'Text',
      'param_valeur'       => 'Text',
      'param_type'         => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
