<?php

/**
 * EiTask form base class.
 *
 * @method EiTask getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'author_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'task_state_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTaskState'), 'add_empty' => false)),
      'project_id'          => new sfWidgetFormInputText(),
      'project_ref'         => new sfWidgetFormInputText(),
      'name'                => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormTextarea(),
      'expected_start_date' => new sfWidgetFormInputText(),
      'expected_end_date'   => new sfWidgetFormInputText(),
      'expected_delay'      => new sfWidgetFormInputText(),
      'expected_duration'   => new sfWidgetFormInputText(),
      'to_plan'             => new sfWidgetFormInputCheckbox(),
      'plan_start_date'     => new sfWidgetFormDateTime(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'task_state_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTaskState'))),
      'project_id'          => new sfValidatorInteger(),
      'project_ref'         => new sfValidatorInteger(),
      'name'                => new sfValidatorString(array('max_length' => 255)),
      'description'         => new sfValidatorString(),
      'expected_start_date' => new sfValidatorNumber(array('required' => false)),
      'expected_end_date'   => new sfValidatorNumber(array('required' => false)),
      'expected_delay'      => new sfValidatorNumber(array('required' => false)),
      'expected_duration'   => new sfValidatorNumber(array('required' => false)),
      'to_plan'             => new sfValidatorBoolean(array('required' => false)),
      'plan_start_date'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTask';
  }

}
