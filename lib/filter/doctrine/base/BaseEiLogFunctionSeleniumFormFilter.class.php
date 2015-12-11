<?php

/**
 * EiLogFunctionSelenium filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiLogFunctionSeleniumFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_log_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => true)),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_scenario_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => true)),
      'ei_fonction_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'add_empty' => true)),
      'ei_log_function_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => true)),
      'message'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_log_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLog'), 'column' => 'id')),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_scenario_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetFunction'), 'column' => 'id')),
      'ei_fonction_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFonction'), 'column' => 'id')),
      'ei_log_function_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiLogFunction'), 'column' => 'id')),
      'message'                 => new sfValidatorPass(array('required' => false)),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_log_function_selenium_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogFunctionSelenium';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_log_id'               => 'ForeignKey',
      'ei_test_set_id'          => 'ForeignKey',
      'ei_scenario_id'          => 'ForeignKey',
      'ei_test_set_function_id' => 'ForeignKey',
      'ei_fonction_id'          => 'ForeignKey',
      'ei_log_function_id'      => 'ForeignKey',
      'message'                 => 'Text',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
