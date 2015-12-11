<?php

/**
 * EiScenario filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiScenarioFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_node_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiNode'), 'add_empty' => true)),
      'project_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nom_scenario' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nb_joue'      => new sfWidgetFormFilterInput(),
      'description'  => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_node_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiNode'), 'column' => 'id')),
      'project_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nom_scenario' => new sfValidatorPass(array('required' => false)),
      'nb_joue'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description'  => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_scenario_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScenario';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'ei_node_id'   => 'ForeignKey',
      'project_id'   => 'Number',
      'project_ref'  => 'Number',
      'nom_scenario' => 'Text',
      'nb_joue'      => 'Number',
      'description'  => 'Text',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
