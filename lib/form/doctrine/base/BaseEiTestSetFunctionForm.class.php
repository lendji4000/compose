<?php

/**
 * EiTestSetFunction form base class.
 *
 * @method EiTestSetFunction getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetFunctionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'ei_test_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'iteration_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'ei_fonction_id' => new sfWidgetFormInputText(),
      'function_ref'   => new sfWidgetFormInputText(),
      'function_id'    => new sfWidgetFormInputText(),
      'position'       => new sfWidgetFormInputText(),
      'xpath'          => new sfWidgetFormInputText(),
      'log'            => new sfWidgetFormInputText(),
      'date_debut'     => new sfWidgetFormDateTime(),
      'date_fin'       => new sfWidgetFormDateTime(),
      'status'         => new sfWidgetFormInputText(),
      'duree'          => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_test_set_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'iteration_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'ei_fonction_id' => new sfValidatorInteger(array('required' => false)),
      'function_ref'   => new sfValidatorInteger(),
      'function_id'    => new sfValidatorInteger(),
      'position'       => new sfValidatorInteger(),
      'xpath'          => new sfValidatorPass(),
      'log'            => new sfValidatorPass(array('required' => false)),
      'date_debut'     => new sfValidatorDateTime(array('required' => false)),
      'date_fin'       => new sfValidatorDateTime(array('required' => false)),
      'status'         => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'duree'          => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set_function[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSetFunction';
  }

}
