<?php

/**
 * EiLog form base class.
 *
 * @method EiLog getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'ei_test_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => false)),
      'ei_scenario_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => false)),
      'ei_version_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => false)),
      'ei_data_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'profile_id'     => new sfWidgetFormInputText(),
      'profile_ref'    => new sfWidgetFormInputText(),
      'user_id'        => new sfWidgetFormInputText(),
      'user_ref'       => new sfWidgetFormInputText(),
      'iteration_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'log'            => new sfWidgetFormInputText(),
      'date_debut'     => new sfWidgetFormDateTime(),
      'date_fin'       => new sfWidgetFormDateTime(),
      'duree'          => new sfWidgetFormTextarea(),
      'status'         => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_test_set_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'))),
      'ei_scenario_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'))),
      'ei_version_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'))),
      'ei_data_set_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'profile_id'     => new sfValidatorInteger(array('required' => false)),
      'profile_ref'    => new sfValidatorInteger(array('required' => false)),
      'user_id'        => new sfValidatorInteger(array('required' => false)),
      'user_ref'       => new sfValidatorInteger(array('required' => false)),
      'iteration_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'log'            => new sfValidatorPass(array('required' => false)),
      'date_debut'     => new sfValidatorDateTime(array('required' => false)),
      'date_fin'       => new sfValidatorDateTime(array('required' => false)),
      'duree'          => new sfValidatorString(array('required' => false)),
      'status'         => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLog';
  }

}
