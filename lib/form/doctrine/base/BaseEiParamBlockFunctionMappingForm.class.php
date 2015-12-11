<?php

/**
 * EiParamBlockFunctionMapping form base class.
 *
 * @method EiParamBlockFunctionMapping getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiParamBlockFunctionMappingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'ei_function_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunction'), 'add_empty' => false)),
      'ei_param_function_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionParamMapping'), 'add_empty' => false)),
      'ei_param_block_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockParamMapping'), 'add_empty' => true)),
      'expression'           => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_function_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunction'))),
      'ei_param_function_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionParamMapping'))),
      'ei_param_block_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockParamMapping'), 'required' => false)),
      'expression'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_param_block_function_mapping[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiParamBlockFunctionMapping';
  }

}
