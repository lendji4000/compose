<?php

/**
 * EiSubjectPriorityHistory form base class.
 *
 * @method EiSubjectPriorityHistory getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectPriorityHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'subject_id'       => new sfWidgetFormInputHidden(),
      'new_priority'     => new sfWidgetFormInputHidden(),
      'date'             => new sfWidgetFormInputHidden(),
      'author_of_change' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'last_priority'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LastPriority'), 'add_empty' => false)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'subject_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('subject_id')), 'empty_value' => $this->getObject()->get('subject_id'), 'required' => false)),
      'new_priority'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('new_priority')), 'empty_value' => $this->getObject()->get('new_priority'), 'required' => false)),
      'date'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('date')), 'empty_value' => $this->getObject()->get('date'), 'required' => false)),
      'author_of_change' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'last_priority'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LastPriority'))),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_priority_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectPriorityHistory';
  }

}
