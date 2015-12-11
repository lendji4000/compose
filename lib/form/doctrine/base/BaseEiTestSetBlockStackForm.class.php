<?php

/**
 * EiTestSetBlockStack form base class.
 *
 * @method EiTestSetBlockStack getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetBlockStackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'), 'add_empty' => true)),
      'ei_block_param_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockParam'), 'add_empty' => true)),
      'ei_test_set_dataset_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetDataSet'), 'add_empty' => true)),
      'parent_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockStackParent'), 'add_empty' => true)),
      'position'                => new sfWidgetFormInputText(),
      'repetition_index'        => new sfWidgetFormInputText(),
      'parts_count'             => new sfWidgetFormInputText(),
      'part_index'              => new sfWidgetFormInputText(),
      'path'                    => new sfWidgetFormInputText(),
      'executed'                => new sfWidgetFormInputCheckbox(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'), 'required' => false)),
      'ei_block_param_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockParam'), 'required' => false)),
      'ei_test_set_dataset_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetDataSet'), 'required' => false)),
      'parent_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetBlockStackParent'), 'required' => false)),
      'position'                => new sfValidatorInteger(array('required' => false)),
      'repetition_index'        => new sfValidatorInteger(array('required' => false)),
      'parts_count'             => new sfValidatorInteger(array('required' => false)),
      'part_index'              => new sfValidatorInteger(array('required' => false)),
      'path'                    => new sfValidatorString(array('max_length' => 255)),
      'executed'                => new sfValidatorBoolean(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'EiTestSetBlockStack', 'column' => array('ei_test_set_id', 'ei_block_param_id', 'parent_id', 'part_index')))
    );

    $this->widgetSchema->setNameFormat('ei_test_set_block_stack[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetBlockStack';
  }

}
