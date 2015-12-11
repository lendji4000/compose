<?php

/**
 * EiDataLine filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiDataLineFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_data_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'ei_data_line_parent_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineParent'), 'add_empty' => true)),
      'ei_data_set_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'add_empty' => true)),
      'valeur'                   => new sfWidgetFormFilterInput(),
      'created_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'root_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataLineRoot'), 'add_empty' => true)),
      'lft'                      => new sfWidgetFormFilterInput(),
      'rgt'                      => new sfWidgetFormFilterInput(),
      'level'                    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'ei_data_set_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSet'), 'column' => 'id')),
      'ei_data_line_parent_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataLineParent'), 'column' => 'id')),
      'ei_data_set_structure_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataSetStructure'), 'column' => 'id')),
      'valeur'                   => new sfValidatorPass(array('required' => false)),
      'created_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'root_id'                  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDataLineRoot'), 'column' => 'id')),
      'lft'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ei_data_line_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDataLine';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'ei_data_set_id'           => 'ForeignKey',
      'ei_data_line_parent_id'   => 'ForeignKey',
      'ei_data_set_structure_id' => 'ForeignKey',
      'valeur'                   => 'Text',
      'created_at'               => 'Date',
      'updated_at'               => 'Date',
      'root_id'                  => 'ForeignKey',
      'lft'                      => 'Number',
      'rgt'                      => 'Number',
      'level'                    => 'Number',
    );
  }
}
