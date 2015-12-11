<?php

/**
 * EiTestSet filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_ref'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'profile_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ei_scenario_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_version_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => true)),
      'ei_data_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'mode'           => new sfWidgetFormChoice(array('choices' => array('' => '', 'Campaign' => 'Campaign', 'AutoPlay' => 'AutoPlay', 'Record' => 'Record', 'StepByStep' => 'StepByStep'))),
      'author_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'device'         => new sfWidgetFormChoice(array('choices' => array('' => '', 'SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari'))),
      'status'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'termine'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'iteration_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'profile_ref'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ei_scenario_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'ei_version_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersion'), 'column' => 'id')),
      'ei_data_set_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSet'), 'column' => 'id')),
      'mode'           => new sfValidatorChoice(array('required' => false, 'choices' => array('Campaign' => 'Campaign', 'AutoPlay' => 'AutoPlay', 'Record' => 'Record', 'StepByStep' => 'StepByStep'))),
      'author_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'device'         => new sfValidatorChoice(array('required' => false, 'choices' => array('SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari'))),
      'status'         => new sfValidatorPass(array('required' => false)),
      'termine'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'iteration_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiIteration'), 'column' => 'id')),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSet';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'profile_ref'    => 'Number',
      'profile_id'     => 'Number',
      'ei_scenario_id' => 'ForeignKey',
      'ei_version_id'  => 'ForeignKey',
      'ei_data_set_id' => 'ForeignKey',
      'mode'           => 'Enum',
      'author_id'      => 'ForeignKey',
      'device'         => 'Enum',
      'status'         => 'Text',
      'termine'        => 'Boolean',
      'iteration_id'   => 'ForeignKey',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
