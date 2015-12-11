<?php

/**
 * EiTestSetFunction filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetFunctionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_test_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'iteration_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'ei_fonction_id' => new sfWidgetFormFilterInput(),
      'function_ref'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'function_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'position'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'xpath'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'log'            => new sfWidgetFormFilterInput(),
      'date_debut'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'date_fin'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'duree'          => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_test_set_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'iteration_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiIteration'), 'column' => 'id')),
      'ei_fonction_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_ref'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'xpath'          => new sfValidatorPass(array('required' => false)),
      'log'            => new sfValidatorPass(array('required' => false)),
      'date_debut'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'date_fin'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'status'         => new sfValidatorPass(array('required' => false)),
      'duree'          => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_function_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetFunction';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'ei_test_set_id' => 'ForeignKey',
      'iteration_id'   => 'ForeignKey',
      'ei_fonction_id' => 'Number',
      'function_ref'   => 'Number',
      'function_id'    => 'Number',
      'position'       => 'Number',
      'xpath'          => 'Text',
      'log'            => 'Text',
      'date_debut'     => 'Date',
      'date_fin'       => 'Date',
      'status'         => 'Text',
      'duree'          => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
