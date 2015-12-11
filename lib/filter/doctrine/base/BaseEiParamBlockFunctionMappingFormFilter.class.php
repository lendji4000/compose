<?php

/**
 * EiParamBlockFunctionMapping filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiParamBlockFunctionMappingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_function_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunction'), 'add_empty' => true)),
      'ei_param_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionParamMapping'), 'add_empty' => true)),
      'ei_param_block_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockParamMapping'), 'add_empty' => true)),
      'expression'           => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_function_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFunction'), 'column' => 'id')),
      'ei_param_function_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFunctionParamMapping'), 'column' => 'param_id')),
      'ei_param_block_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiBlockParamMapping'), 'column' => 'id')),
      'expression'           => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_param_block_function_mapping_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiParamBlockFunctionMapping';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'ei_function_id'       => 'ForeignKey',
      'ei_param_function_id' => 'ForeignKey',
      'ei_param_block_id'    => 'ForeignKey',
      'expression'           => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
