<?php

/**
 * EiScenario form base class.
 *
 * @method EiScenario getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiScenarioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'ei_node_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiNode'), 'add_empty' => false)),
      'project_id'   => new sfWidgetFormInputText(),
      'project_ref'  => new sfWidgetFormInputText(),
      'nom_scenario' => new sfWidgetFormInputText(),
      'nb_joue'      => new sfWidgetFormInputText(),
      'description'  => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_node_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiNode'))),
      'project_id'   => new sfValidatorInteger(),
      'project_ref'  => new sfValidatorInteger(),
      'nom_scenario' => new sfValidatorString(array('max_length' => 255)),
      'nb_joue'      => new sfValidatorInteger(array('required' => false)),
      'description'  => new sfValidatorString(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_scenario[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScenario';
  }

}
