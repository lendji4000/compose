<?php

/**
 * EiSubjectAttachment filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectAttachmentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'subject_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'), 'add_empty' => true)),
      'author_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'description' => new sfWidgetFormFilterInput(),
      'filename'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'        => new sfWidgetFormChoice(array('choices' => array('' => '', 'bugAttachmentDescription' => 'bugAttachmentDescription', 'bugAttachmentDetails' => 'bugAttachmentDetails', 'bugAttachmentSolution' => 'bugAttachmentSolution', 'bugAttachmentMigration' => 'bugAttachmentMigration'))),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'subject_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubject'), 'column' => 'id')),
      'author_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'description' => new sfValidatorPass(array('required' => false)),
      'filename'    => new sfValidatorPass(array('required' => false)),
      'path'        => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorChoice(array('required' => false, 'choices' => array('bugAttachmentDescription' => 'bugAttachmentDescription', 'bugAttachmentDetails' => 'bugAttachmentDetails', 'bugAttachmentSolution' => 'bugAttachmentSolution', 'bugAttachmentMigration' => 'bugAttachmentMigration'))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_attachment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectAttachment';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'subject_id'  => 'ForeignKey',
      'author_id'   => 'ForeignKey',
      'description' => 'Text',
      'filename'    => 'Text',
      'path'        => 'Text',
      'type'        => 'Enum',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
