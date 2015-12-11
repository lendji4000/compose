<?php

/**
 * EiExecutionStack form base class.
 *
 * @method EiExecutionStack getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiExecutionStackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'ei_scenario_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'add_empty' => true)),
      'ei_data_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'ei_campaign_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => true)),
      'project_ref'              => new sfWidgetFormInputText(),
      'project_id'               => new sfWidgetFormInputText(),
      'profile_ref'              => new sfWidgetFormInputText(),
      'profile_id'               => new sfWidgetFormInputText(),
      'user_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'start_pos'                => new sfWidgetFormInputText(),
      'end_pos'                  => new sfWidgetFormInputText(),
      'synchronous'              => new sfWidgetFormInputCheckbox(),
      'status'                   => new sfWidgetFormInputText(),
      'ei_test_set_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_campaign_execution_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignExecution'), 'add_empty' => true)),
      'robot'                    => new sfWidgetFormInputText(),
      'device'                   => new sfWidgetFormChoice(array('choices' => array('SeleniumIde' => 'SeleniumIde', 'Ios' => 'Ios', 'Android' => 'Android', 'Chrome' => 'Chrome', 'Firefox' => 'Firefox', 'InternetExplorer' => 'InternetExplorer', 'Safari' => 'Safari', 'Raspberry' => 'Raspberry'))),
      'device_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDevice'), 'add_empty' => true)),
      'driver_id'                => new sfWidgetFormInputText(),
      'browser_id'               => new sfWidgetFormInputText(),
      'expected_date'            => new sfWidgetFormDateTime(),
      'created_at'               => new sfWidgetFormDateTime(),
      'updated_at'               => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_scenario_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiScenario'), 'required' => false)),
      'ei_data_set_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'ei_campaign_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'required' => false)),
      'project_ref'              => new sfValidatorInteger(),
      'project_id'               => new sfValidatorInteger(),
      'profile_ref'              => new sfValidatorInteger(),
      'profile_id'               => new sfValidatorInteger(),
      'user_id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'start_pos'                => new sfValidatorInteger(array('required' => false)),
      'end_pos'                  => new sfValidatorInteger(array('required' => false)),
      'synchronous'              => new sfValidatorBoolean(array('required' => false)),
      'status'                   => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'ei_test_set_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'required' => false)),
      'ei_campaign_execution_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaignExecution'), 'required' => false)),
      'robot'                    => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'device'                   => new sfValidatorChoice(array('choices' => array(0 => 'SeleniumIde', 1 => 'Ios', 2 => 'Android', 3 => 'Chrome', 4 => 'Firefox', 5 => 'InternetExplorer', 6 => 'Safari', 7 => 'Raspberry'), 'required' => false)),
      'device_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDevice'), 'required' => false)),
      'driver_id'                => new sfValidatorInteger(array('required' => false)),
      'browser_id'               => new sfValidatorInteger(array('required' => false)),
      'expected_date'            => new sfValidatorDateTime(array('required' => false)),
      'created_at'               => new sfValidatorDateTime(),
      'updated_at'               => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_execution_stack[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiExecutionStack';
  }

}
