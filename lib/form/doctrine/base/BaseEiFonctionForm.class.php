<?php

/**
 * EiFonction form base class.
 *
 * @method EiFonction getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiFonctionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_version_structure_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'), 'add_empty' => false)),
      'function_ref'            => new sfWidgetFormInputText(),
      'function_id'             => new sfWidgetFormInputText(),
      'description'             => new sfWidgetFormTextarea(),
      'project_ref'             => new sfWidgetFormInputText(),
      'project_id'              => new sfWidgetFormInputText(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_version_structure_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersionStructure'))),
      'function_ref'            => new sfValidatorInteger(),
      'function_id'             => new sfValidatorInteger(),
      'description'             => new sfValidatorString(array('required' => false)),
      'project_ref'             => new sfValidatorInteger(),
      'project_id'              => new sfValidatorInteger(),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_fonction[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiFonction';
  }

}
