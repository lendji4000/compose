<?php

/**
 * EiLogFunction filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiLogFunctionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_log_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => true)),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'function_id'             => new sfWidgetFormFilterInput(),
      'function_ref'            => new sfWidgetFormFilterInput(),
      'ei_scenario_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => true)),
      'ei_fonction_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'add_empty' => true)),
      'position'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_debut'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'date_fin'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'duree'                   => new sfWidgetFormFilterInput(),
      'iteration_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_log_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLog'), 'column' => 'id')),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'function_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_ref'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ei_scenario_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetFunction'), 'column' => 'id')),
      'ei_fonction_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFonction'), 'column' => 'id')),
      'position'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                  => new sfValidatorPass(array('required' => false)),
      'date_debut'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'date_fin'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'duree'                   => new sfValidatorPass(array('required' => false)),
      'iteration_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiIteration'), 'column' => 'id')),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_log_function_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogFunction';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_log_id'               => 'ForeignKey',
      'ei_test_set_id'          => 'ForeignKey',
      'function_id'             => 'Number',
      'function_ref'            => 'Number',
      'ei_scenario_id'          => 'ForeignKey',
      'ei_test_set_function_id' => 'ForeignKey',
      'ei_fonction_id'          => 'ForeignKey',
      'position'                => 'Number',
      'status'                  => 'Text',
      'date_debut'              => 'Date',
      'date_fin'                => 'Date',
      'duree'                   => 'Text',
      'iteration_id'            => 'ForeignKey',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
