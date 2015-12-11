<?php

/**
 * EiTestSetBlockParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetBlockParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'), 'add_empty' => true)),
      'parent_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockParamParent'), 'add_empty' => true)),
      'index_repetition'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                    => new sfWidgetFormFilterInput(),
      'value'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'root_id'                 => new sfWidgetFormFilterInput(),
      'lft'                     => new sfWidgetFormFilterInput(),
      'rgt'                     => new sfWidgetFormFilterInput(),
      'level'                   => new sfWidgetFormFilterInput(),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSet'), 'column' => 'id')),
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersionStructure'), 'column' => 'id')),
      'parent_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiTestSetBlockParamParent'), 'column' => 'id')),
      'index_repetition'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'path'                    => new sfValidatorPass(array('required' => false)),
      'type'                    => new sfValidatorPass(array('required' => false)),
      'name'                    => new sfValidatorPass(array('required' => false)),
      'slug'                    => new sfValidatorPass(array('required' => false)),
      'value'                   => new sfValidatorPass(array('required' => false)),
      'root_id'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_block_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetBlockParam';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_test_set_id'          => 'ForeignKey',
      'ei_version_structure_id' => 'ForeignKey',
      'parent_id'               => 'ForeignKey',
      'index_repetition'        => 'Number',
      'path'                    => 'Text',
      'type'                    => 'Text',
      'name'                    => 'Text',
      'slug'                    => 'Text',
      'value'                   => 'Text',
      'root_id'                 => 'Number',
      'lft'                     => 'Number',
      'rgt'                     => 'Number',
      'level'                   => 'Number',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
