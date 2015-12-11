<?php

/**
 * EiTestSetBlockStack filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetBlockStackFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'), 'add_empty' => true)),
      'ei_block_param_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockParam'), 'add_empty' => true)),
      'ei_test_set_dataset_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetDataSet'), 'add_empty' => true)),
      'parent_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockStackParent'), 'add_empty' => true)),
      'position'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'repetition_index'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'parts_count'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'part_index'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'executed'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersionStructure'), 'column' => 'id')),
      'ei_block_param_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetBlockParam'), 'column' => 'id')),
      'ei_test_set_dataset_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetDataSet'), 'column' => 'id')),
      'parent_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetBlockStackParent'), 'column' => 'id')),
      'position'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'repetition_index'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parts_count'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'part_index'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'path'                    => new sfValidatorPass(array('required' => false)),
      'executed'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_block_stack_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetBlockStack';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_test_set_id'          => 'ForeignKey',
      'ei_version_structure_id' => 'ForeignKey',
      'ei_block_param_id'       => 'ForeignKey',
      'ei_test_set_dataset_id'  => 'ForeignKey',
      'parent_id'               => 'ForeignKey',
      'position'                => 'Number',
      'repetition_index'        => 'Number',
      'parts_count'             => 'Number',
      'part_index'              => 'Number',
      'path'                    => 'Text',
      'executed'                => 'Boolean',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
