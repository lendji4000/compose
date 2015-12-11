<?php

/**
 * EiBlockDataSetMapping form base class.
 *
 * @method EiBlockDataSetMapping getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiBlockDataSetMappingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureMapping'), 'add_empty' => false)),
      'ei_dataset_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureMapping'), 'add_empty' => false)),
      'type'                    => new sfWidgetFormInputText(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureMapping'))),
      'ei_dataset_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureMapping'))),
      'type'                    => new sfValidatorString(array('max_length' => 255)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_block_data_set_mapping[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiBlockDataSetMapping';
  }

}
