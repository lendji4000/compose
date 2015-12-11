<?php

/**
 * EiTestSetDataSet filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetDataSetFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_test_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_data_set_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'add_empty' => true)),
      'parent_id'                => new sfWidgetFormFilterInput(),
      'parent_index_repetition'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'index_repetition'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                     => new sfWidgetFormFilterInput(),
      'value'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_modified'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'root_id'                  => new sfWidgetFormFilterInput(),
      'lft'                      => new sfWidgetFormFilterInput(),
      'rgt'                      => new sfWidgetFormFilterInput(),
      'level'                    => new sfWidgetFormFilterInput(),
      'created_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_test_set_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_data_set_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSetStructure'), 'column' => 'id')),
      'parent_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parent_index_repetition'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'index_repetition'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                     => new sfValidatorPass(array('required' => false)),
      'name'                     => new sfValidatorPass(array('required' => false)),
      'slug'                     => new sfValidatorPass(array('required' => false)),
      'value'                    => new sfValidatorPass(array('required' => false)),
      'is_modified'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'root_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_data_set_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetDataSet';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'ei_test_set_id'           => 'ForeignKey',
      'ei_data_set_structure_id' => 'ForeignKey',
      'parent_id'                => 'Number',
      'parent_index_repetition'  => 'Number',
      'index_repetition'         => 'Number',
      'type'                     => 'Text',
      'name'                     => 'Text',
      'slug'                     => 'Text',
      'value'                    => 'Text',
      'is_modified'              => 'Boolean',
      'root_id'                  => 'Number',
      'lft'                      => 'Number',
      'rgt'                      => 'Number',
      'level'                    => 'Number',
      'created_at'               => 'Date',
      'updated_at'               => 'Date',
    );
  }
}
