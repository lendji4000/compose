<?php

/**
 * EiSubjectAuthorHistory form base class.
 *
 * @method EiSubjectAuthorHistory getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectAuthorHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'subject_id'       => new sfWidgetFormInputHidden(),
      'new_author'       => new sfWidgetFormInputHidden(),
      'date'             => new sfWidgetFormInputHidden(),
      'author_of_change' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('AuthorOfChange'), 'add_empty' => false)),
      'last_author'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LastAuthor'), 'add_empty' => false)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'subject_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('subject_id')), 'empty_value' => $this->getObject()->get('subject_id'), 'required' => false)),
      'new_author'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('new_author')), 'empty_value' => $this->getObject()->get('new_author'), 'required' => false)),
      'date'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('date')), 'empty_value' => $this->getObject()->get('date'), 'required' => false)),
      'author_of_change' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('AuthorOfChange'))),
      'last_author'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LastAuthor'))),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_author_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectAuthorHistory';
  }

}
