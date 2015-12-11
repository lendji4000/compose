<?php

/**
 * EiTestSetParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'iteration_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'function_id'             => new sfWidgetFormFilterInput(),
      'function_ref'            => new sfWidgetFormFilterInput(),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => true)),
      'param_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'), 'add_empty' => true)),
      'valeur'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'param_type'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'iteration_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiIteration'), 'column' => 'id')),
      'function_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_ref'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetFunction'), 'column' => 'id')),
      'param_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFunctionHasParam'), 'column' => 'param_id')),
      'valeur'                  => new sfValidatorPass(array('required' => false)),
      'param_type'              => new sfValidatorPass(array('required' => false)),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetParam';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_test_set_id'          => 'ForeignKey',
      'iteration_id'            => 'ForeignKey',
      'function_id'             => 'Number',
      'function_ref'            => 'Number',
      'ei_test_set_function_id' => 'ForeignKey',
      'param_id'                => 'ForeignKey',
      'valeur'                  => 'Text',
      'param_type'              => 'Text',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
