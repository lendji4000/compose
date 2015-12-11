<?php

/**
 * EiSubjectAssignmentHistory form base class.
 *
 * @method EiSubjectAssignmentHistory getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectAssignmentHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'subject_id'           => new sfWidgetFormInputHidden(),
      'author_of_assignment' => new sfWidgetFormInputHidden(),
      'assign_to'            => new sfWidgetFormInputHidden(),
      'date'                 => new sfWidgetFormInputHidden(),
      'is_assignment'        => new sfWidgetFormInputCheckbox(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'subject_id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('subject_id')), 'empty_value' => $this->getObject()->get('subject_id'), 'required' => false)),
      'author_of_assignment' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('author_of_assignment')), 'empty_value' => $this->getObject()->get('author_of_assignment'), 'required' => false)),
      'assign_to'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('assign_to')), 'empty_value' => $this->getObject()->get('assign_to'), 'required' => false)),
      'date'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('date')), 'empty_value' => $this->getObject()->get('date'), 'required' => false)),
      'is_assignment'        => new sfValidatorBoolean(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_assignment_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectAssignmentHistory';
  }

}
