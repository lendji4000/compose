<?php

/**
 * EiTestSetParam form base class.
 *
 * @method EiTestSetParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'iteration_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'function_id'             => new sfWidgetFormInputText(),
      'function_ref'            => new sfWidgetFormInputText(),
      'ei_test_set_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'), 'add_empty' => false)),
      'param_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'), 'add_empty' => false)),
      'valeur'                  => new sfWidgetFormInputText(),
      'param_type'              => new sfWidgetFormTextarea(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'iteration_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'function_id'             => new sfValidatorInteger(array('required' => false)),
      'function_ref'            => new sfValidatorInteger(array('required' => false)),
      'ei_test_set_function_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSetFunction'))),
      'param_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'))),
      'valeur'                  => new sfValidatorPass(),
      'param_type'              => new sfValidatorString(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetParam';
  }

}
