<?php

/**
 * EiSubjectHasTask form base class.
 *
 * @method EiSubjectHasTask getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectHasTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'task_id'    => new sfWidgetFormInputHidden(),
      'subject_id' => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'task_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('task_id')), 'empty_value' => $this->getObject()->get('task_id'), 'required' => false)),
      'subject_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('subject_id')), 'empty_value' => $this->getObject()->get('subject_id'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_has_task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectHasTask';
  }

}
