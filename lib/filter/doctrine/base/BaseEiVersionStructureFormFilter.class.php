<?php

/**
 * EiVersionStructure filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiVersionStructureFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_version_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => true)),
      'ei_fonction_id'                 => new sfWidgetFormFilterInput(),
      'ei_scenario_executable_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenarioExecutable'), 'add_empty' => true)),
      'ei_version_structure_parent_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureParent'), 'add_empty' => true)),
      'type'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                           => new sfWidgetFormFilterInput(),
      'description'                    => new sfWidgetFormFilterInput(),
      'created_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'root_id'                        => new sfWidgetFormFilterInput(),
      'lft'                            => new sfWidgetFormFilterInput(),
      'rgt'                            => new sfWidgetFormFilterInput(),
      'level'                          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'ei_version_id'                  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersion'), 'column' => 'id')),
      'ei_fonction_id'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ei_scenario_executable_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenarioExecutable'), 'column' => 'id')),
      'ei_version_structure_parent_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersionStructureParent'), 'column' => 'id')),
      'type'                           => new sfValidatorPass(array('required' => false)),
      'name'                           => new sfValidatorPass(array('required' => false)),
      'slug'                           => new sfValidatorPass(array('required' => false)),
      'description'                    => new sfValidatorPass(array('required' => false)),
      'created_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'root_id'                        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ei_version_structure_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiVersionStructure';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'ei_version_id'                  => 'ForeignKey',
      'ei_fonction_id'                 => 'Number',
      'ei_scenario_executable_id'      => 'ForeignKey',
      'ei_version_structure_parent_id' => 'ForeignKey',
      'type'                           => 'Text',
      'name'                           => 'Text',
      'slug'                           => 'Text',
      'description'                    => 'Text',
      'created_at'                     => 'Date',
      'updated_at'                     => 'Date',
      'root_id'                        => 'Number',
      'lft'                            => 'Number',
      'rgt'                            => 'Number',
      'level'                          => 'Number',
    );
  }
}
