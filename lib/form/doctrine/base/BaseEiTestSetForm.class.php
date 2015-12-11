<?php

/**
 * EiTestSet form base class.
 *
 * @method EiTestSet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTestSetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'profile_ref'    => new sfWidgetFormInputText(),
      'profile_id'     => new sfWidgetFormInputText(),
      'ei_scenario_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => false)),
      'ei_version_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => false)),
      'ei_data_set_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'mode'           => new sfWidgetFormChoice(array('choices' => array('Campaign' => 'Campaign', 'AutoPlay' => 'AutoPlay', 'Record' => 'Record', 'StepByStep' => 'StepByStep'))),
      'author_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'device'         => new sfWidgetFormChoice(array('choices' => array('SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari'))),
      'status'         => new sfWidgetFormInputText(),
      'termine'        => new sfWidgetFormInputCheckbox(),
      'iteration_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'profile_ref'    => new sfValidatorInteger(),
      'profile_id'     => new sfValidatorInteger(),
      'ei_scenario_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'))),
      'ei_version_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'))),
      'ei_data_set_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'mode'           => new sfValidatorChoice(array('choices' => array(0 => 'Campaign', 1 => 'AutoPlay', 2 => 'Record', 3 => 'StepByStep'), 'required' => false)),
      'author_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'device'         => new sfValidatorChoice(array('choices' => array(0 => 'SeleniumIde', 1 => 'Ios', 2 => 'Android', 3 => 'Chrome', 4 => 'Firefox', 5 => 'InternetExplorer', 6 => 'Safari'), 'required' => false)),
      'status'         => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'termine'        => new sfValidatorBoolean(array('required' => false)),
      'iteration_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_test_set[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTestSet';
  }

}
