<?php

/**
 * EiDataSetStructure form base class.
 *
 * @method EiDataSetStructure getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiDataSetStructureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'ei_scenario_id'                 => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => false)),
      'ei_dataset_structure_parent_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureParent'), 'add_empty' => true)),
      'root_id'                        => new sfWidgetFormInputText(),
      'project_id'                     => new sfWidgetFormInputText(),
      'project_ref'                    => new sfWidgetFormInputText(),
      'type'                           => new sfWidgetFormInputText(),
      'name'                           => new sfWidgetFormInputText(),
      'slug'                           => new sfWidgetFormInputText(),
      'description'                    => new sfWidgetFormTextarea(),
      'created_at'                     => new sfWidgetFormDateTime(),
      'updated_at'                     => new sfWidgetFormDateTime(),
      'lft'                            => new sfWidgetFormInputText(),
      'rgt'                            => new sfWidgetFormInputText(),
      'level'                          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_scenario_id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'))),
      'ei_dataset_structure_parent_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetStructureParent'), 'required' => false)),
      'root_id'                        => new sfValidatorInteger(array('required' => false)),
      'project_id'                     => new sfValidatorInteger(),
      'project_ref'                    => new sfValidatorInteger(),
      'type'                           => new sfValidatorString(array('max_length' => 255)),
      'name'                           => new sfValidatorString(array('max_length' => 255)),
      'slug'                           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'                    => new sfValidatorString(array('required' => false)),
      'created_at'                     => new sfValidatorDateTime(),
      'updated_at'                     => new sfValidatorDateTime(),
      'lft'                            => new sfValidatorInteger(array('required' => false)),
      'rgt'                            => new sfValidatorInteger(array('required' => false)),
      'level'                          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ei_data_set_structure[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDataSetStructure';
  }

}
