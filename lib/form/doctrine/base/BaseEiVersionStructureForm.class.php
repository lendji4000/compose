<?php

/**
 * EiVersionStructure form base class.
 *
 * @method EiVersionStructure getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiVersionStructureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'ei_version_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => false)),
      'ei_fonction_id'                 => new sfWidgetFormInputText(),
      'ei_scenario_executable_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenarioExecutable'), 'add_empty' => true)),
      'ei_version_structure_parent_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureParent'), 'add_empty' => true)),
      'type'                           => new sfWidgetFormInputText(),
      'name'                           => new sfWidgetFormInputText(),
      'slug'                           => new sfWidgetFormInputText(),
      'description'                    => new sfWidgetFormTextarea(),
      'created_at'                     => new sfWidgetFormDateTime(),
      'updated_at'                     => new sfWidgetFormDateTime(),
      'root_id'                        => new sfWidgetFormInputText(),
      'lft'                            => new sfWidgetFormInputText(),
      'rgt'                            => new sfWidgetFormInputText(),
      'level'                          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_version_id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'))),
      'ei_fonction_id'                 => new sfValidatorInteger(array('required' => false)),
      'ei_scenario_executable_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenarioExecutable'), 'required' => false)),
      'ei_version_structure_parent_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructureParent'), 'required' => false)),
      'type'                           => new sfValidatorString(array('max_length' => 255)),
      'name'                           => new sfValidatorString(array('max_length' => 255)),
      'slug'                           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'                    => new sfValidatorString(array('required' => false)),
      'created_at'                     => new sfValidatorDateTime(),
      'updated_at'                     => new sfValidatorDateTime(),
      'root_id'                        => new sfValidatorInteger(array('required' => false)),
      'lft'                            => new sfValidatorInteger(array('required' => false)),
      'rgt'                            => new sfValidatorInteger(array('required' => false)),
      'level'                          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ei_version_structure[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiVersionStructure';
  }

}
