<?php

/**
 * EiLogFunctionSelenium form base class.
 *
 * @method EiLogFunctionSelenium getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLogFunctionSeleniumForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_log_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => false)),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'ei_scenario_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => false)),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => true)),
      'ei_fonction_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'add_empty' => true)),
      'ei_log_function_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => false)),
      'message'                 => new sfWidgetFormTextarea(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_log_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'))),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'ei_scenario_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'))),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'required' => false)),
      'ei_fonction_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'required' => false)),
      'ei_log_function_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'))),
      'message'                 => new sfValidatorString(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_log_function_selenium[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogFunctionSelenium';
  }

}
