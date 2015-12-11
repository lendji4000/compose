<?php

/**
 * EiLogSensor form base class.
 *
 * @method EiLogSensor getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiLogSensorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'ei_log_function_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'), 'add_empty' => false)),
      'app_memory_mean'     => new sfWidgetFormInputText(),
      'app_memory_min'      => new sfWidgetFormInputText(),
      'app_memory_max'      => new sfWidgetFormInputText(),
      'app_memory_start'    => new sfWidgetFormInputText(),
      'app_memory_end'      => new sfWidgetFormInputText(),
      'app_cpu_mean'        => new sfWidgetFormInputText(),
      'app_cpu_min'         => new sfWidgetFormInputText(),
      'app_cpu_max'         => new sfWidgetFormInputText(),
      'app_cpu_start'       => new sfWidgetFormInputText(),
      'app_cpu_end'         => new sfWidgetFormInputText(),
      'db_memory_mean'      => new sfWidgetFormInputText(),
      'db_memory_min'       => new sfWidgetFormInputText(),
      'db_memory_max'       => new sfWidgetFormInputText(),
      'db_memory_start'     => new sfWidgetFormInputText(),
      'db_memory_end'       => new sfWidgetFormInputText(),
      'db_cpu_mean'         => new sfWidgetFormInputText(),
      'db_cpu_min'          => new sfWidgetFormInputText(),
      'db_cpu_max'          => new sfWidgetFormInputText(),
      'db_cpu_start'        => new sfWidgetFormInputText(),
      'db_cpu_end'          => new sfWidgetFormInputText(),
      'client_memory_mean'  => new sfWidgetFormInputText(),
      'client_memory_min'   => new sfWidgetFormInputText(),
      'client_memory_max'   => new sfWidgetFormInputText(),
      'client_memory_start' => new sfWidgetFormInputText(),
      'client_memory_end'   => new sfWidgetFormInputText(),
      'client_cpu_mean'     => new sfWidgetFormInputText(),
      'client_cpu_min'      => new sfWidgetFormInputText(),
      'client_cpu_max'      => new sfWidgetFormInputText(),
      'client_cpu_start'    => new sfWidgetFormInputText(),
      'client_cpu_end'      => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ei_log_function_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiLogFunction'))),
      'app_memory_mean'     => new sfValidatorInteger(array('required' => false)),
      'app_memory_min'      => new sfValidatorInteger(array('required' => false)),
      'app_memory_max'      => new sfValidatorInteger(array('required' => false)),
      'app_memory_start'    => new sfValidatorInteger(array('required' => false)),
      'app_memory_end'      => new sfValidatorInteger(array('required' => false)),
      'app_cpu_mean'        => new sfValidatorInteger(array('required' => false)),
      'app_cpu_min'         => new sfValidatorInteger(array('required' => false)),
      'app_cpu_max'         => new sfValidatorInteger(array('required' => false)),
      'app_cpu_start'       => new sfValidatorInteger(array('required' => false)),
      'app_cpu_end'         => new sfValidatorInteger(array('required' => false)),
      'db_memory_mean'      => new sfValidatorInteger(array('required' => false)),
      'db_memory_min'       => new sfValidatorInteger(array('required' => false)),
      'db_memory_max'       => new sfValidatorInteger(array('required' => false)),
      'db_memory_start'     => new sfValidatorInteger(array('required' => false)),
      'db_memory_end'       => new sfValidatorInteger(array('required' => false)),
      'db_cpu_mean'         => new sfValidatorInteger(array('required' => false)),
      'db_cpu_min'          => new sfValidatorInteger(array('required' => false)),
      'db_cpu_max'          => new sfValidatorInteger(array('required' => false)),
      'db_cpu_start'        => new sfValidatorInteger(array('required' => false)),
      'db_cpu_end'          => new sfValidatorInteger(array('required' => false)),
      'client_memory_mean'  => new sfValidatorInteger(array('required' => false)),
      'client_memory_min'   => new sfValidatorInteger(array('required' => false)),
      'client_memory_max'   => new sfValidatorInteger(array('required' => false)),
      'client_memory_start' => new sfValidatorInteger(array('required' => false)),
      'client_memory_end'   => new sfValidatorInteger(array('required' => false)),
      'client_cpu_mean'     => new sfValidatorInteger(array('required' => false)),
      'client_cpu_min'      => new sfValidatorInteger(array('required' => false)),
      'client_cpu_max'      => new sfValidatorInteger(array('required' => false)),
      'client_cpu_start'    => new sfValidatorInteger(array('required' => false)),
      'client_cpu_end'      => new sfValidatorInteger(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_log_sensor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiLogSensor';
  }

}
