<?php

/**
 * EiTestSetDataSet form base class.
 *
 * @method EiTestSetDataSet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetDataSetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'ei_test_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'ei_data_set_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'add_empty' => true)),
      'parent_id'                => new sfWidgetFormInputText(),
      'parent_index_repetition'  => new sfWidgetFormInputText(),
      'index_repetition'         => new sfWidgetFormInputText(),
      'type'                     => new sfWidgetFormInputText(),
      'name'                     => new sfWidgetFormInputText(),
      'slug'                     => new sfWidgetFormInputText(),
      'value'                    => new sfWidgetFormInputText(),
      'is_modified'              => new sfWidgetFormInputCheckbox(),
      'root_id'                  => new sfWidgetFormInputText(),
      'lft'                      => new sfWidgetFormInputText(),
      'rgt'                      => new sfWidgetFormInputText(),
      'level'                    => new sfWidgetFormInputText(),
      'created_at'               => new sfWidgetFormDateTime(),
      'updated_at'               => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_test_set_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'ei_data_set_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructure'), 'required' => false)),
      'parent_id'                => new sfValidatorInteger(array('required' => false)),
      'parent_index_repetition'  => new sfValidatorInteger(array('required' => false)),
      'index_repetition'         => new sfValidatorInteger(array('required' => false)),
      'type'                     => new sfValidatorString(array('max_length' => 255)),
      'name'                     => new sfValidatorString(array('max_length' => 255)),
      'slug'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'value'                    => new sfValidatorPass(),
      'is_modified'              => new sfValidatorBoolean(array('required' => false)),
      'root_id'                  => new sfValidatorInteger(array('required' => false)),
      'lft'                      => new sfValidatorInteger(array('required' => false)),
      'rgt'                      => new sfValidatorInteger(array('required' => false)),
      'level'                    => new sfValidatorInteger(array('required' => false)),
      'created_at'               => new sfValidatorDateTime(),
      'updated_at'               => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_data_set[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetDataSet';
  }

}
