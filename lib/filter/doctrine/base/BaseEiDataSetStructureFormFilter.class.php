<?php

/**
 * EiDataSetStructure filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiDataSetStructureFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_scenario_id'                 => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_dataset_structure_parent_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureParent'), 'add_empty' => true)),
      'root_id'                        => new sfWidgetFormFilterInput(),
      'project_id'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                           => new sfWidgetFormFilterInput(),
      'description'                    => new sfWidgetFormFilterInput(),
      'created_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'lft'                            => new sfWidgetFormFilterInput(),
      'rgt'                            => new sfWidgetFormFilterInput(),
      'level'                          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'ei_scenario_id'                 => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiScenario'), 'column' => 'id')),
      'ei_dataset_structure_parent_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSetStructureParent'), 'column' => 'id')),
      'root_id'                        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_id'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                           => new sfValidatorPass(array('required' => false)),
      'name'                           => new sfValidatorPass(array('required' => false)),
      'slug'                           => new sfValidatorPass(array('required' => false)),
      'description'                    => new sfValidatorPass(array('required' => false)),
      'created_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'lft'                            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ei_data_set_structure_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDataSetStructure';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'ei_scenario_id'                 => 'ForeignKey',
      'ei_dataset_structure_parent_id' => 'ForeignKey',
      'root_id'                        => 'Number',
      'project_id'                     => 'Number',
      'project_ref'                    => 'Number',
      'type'                           => 'Text',
      'name'                           => 'Text',
      'slug'                           => 'Text',
      'description'                    => 'Text',
      'created_at'                     => 'Date',
      'updated_at'                     => 'Date',
      'lft'                            => 'Number',
      'rgt'                            => 'Number',
      'level'                          => 'Number',
    );
  }
}
