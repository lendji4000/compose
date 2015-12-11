<?php

/**
 * EiLogParam form base class.
 *
 * @method EiLogParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLogParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'ei_log_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'), 'add_empty' => false)),
      'function_id'        => new sfWidgetFormInputText(),
      'function_ref'       => new sfWidgetFormInputText(),
      'ei_test_set_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'ei_log_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => false)),
      'ei_param_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiParam'), 'add_empty' => true)),
      'param_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'), 'add_empty' => false)),
      'iteration_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'param_name'         => new sfWidgetFormTextarea(),
      'param_valeur'       => new sfWidgetFormTextarea(),
      'param_type'         => new sfWidgetFormTextarea(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_log_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLog'))),
      'function_id'        => new sfValidatorInteger(array('required' => false)),
      'function_ref'       => new sfValidatorInteger(array('required' => false)),
      'ei_test_set_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'ei_log_function_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'))),
      'ei_param_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiParam'), 'required' => false)),
      'param_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'))),
      'iteration_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'param_name'         => new sfValidatorString(),
      'param_valeur'       => new sfValidatorString(),
      'param_type'         => new sfValidatorString(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_log_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogParam';
  }

}
