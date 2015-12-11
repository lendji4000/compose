<?php

/**
 * EiSubjectAttachment form base class.
 *
 * @method EiSubjectAttachment getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectAttachmentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'subject_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'), 'add_empty' => false)),
      'author_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'description' => new sfWidgetFormTextarea(),
      'filename'    => new sfWidgetFormInputText(),
      'path'        => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormChoice(array('choices' => array('bugAttachmentDescription' => 'bugAttachmentDescription', 'bugAttachmentDetails' => 'bugAttachmentDetails', 'bugAttachmentSolution' => 'bugAttachmentSolution', 'bugAttachmentMigration' => 'bugAttachmentMigration'))),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'subject_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'))),
      'author_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'description' => new sfValidatorString(array('required' => false)),
      'filename'    => new sfValidatorString(array('max_length' => 255)),
      'path'        => new sfValidatorString(array('max_length' => 255)),
      'type'        => new sfValidatorChoice(array('choices' => array(0 => 'bugAttachmentDescription', 1 => 'bugAttachmentDetails', 2 => 'bugAttachmentSolution', 3 => 'bugAttachmentMigration'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_attachment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectAttachment';
  }

}
