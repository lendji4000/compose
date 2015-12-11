<?php

/**
 * EiBlockDataSetMapping filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiBlockDataSetMappingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureMapping'), 'add_empty' => true)),
      'ei_dataset_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureMapping'), 'add_empty' => true)),
      'type'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiVersionStructureMapping'), 'column' => 'id')),
      'ei_dataset_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSetStructureMapping'), 'column' => 'id')),
      'type'                    => new sfValidatorPass(array('required' => false)),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_block_data_set_mapping_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiBlockDataSetMapping';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'ei_version_structure_id' => 'ForeignKey',
      'ei_dataset_structure_id' => 'ForeignKey',
      'type'                    => 'Text',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
