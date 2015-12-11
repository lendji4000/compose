<?php

/**
 * EiLogFunction form base class.
 *
 * @method EiLogFunction getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLogFunctionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_log_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => false)),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'function_id'             => new sfWidgetFormInputText(),
      'function_ref'            => new sfWidgetFormInputText(),
      'ei_scenario_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => false)),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => true)),
      'ei_fonction_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'add_empty' => true)),
      'position'                => new sfWidgetFormInputText(),
      'status'                  => new sfWidgetFormTextarea(),
      'date_debut'              => new sfWidgetFormDateTime(),
      'date_fin'                => new sfWidgetFormDateTime(),
      'duree'                   => new sfWidgetFormTextarea(),
      'iteration_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_log_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'))),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'function_id'             => new sfValidatorInteger(array('required' => false)),
      'function_ref'            => new sfValidatorInteger(array('required' => false)),
      'ei_scenario_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'))),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'required' => false)),
      'ei_fonction_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'required' => false)),
      'position'                => new sfValidatorInteger(),
      'status'                  => new sfValidatorString(array('required' => false)),
      'date_debut'              => new sfValidatorDateTime(array('required' => false)),
      'date_fin'                => new sfValidatorDateTime(array('required' => false)),
      'duree'                   => new sfValidatorString(array('required' => false)),
      'iteration_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_log_function[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogFunction';
  }

}
