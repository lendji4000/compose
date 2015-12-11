<?php

/**
 * EiSubjectMessage filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectMessageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'guard_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'subject_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'), 'add_empty' => true)),
      'message_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectMessageType'), 'add_empty' => true)),
      'message'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'bugDescriptionMessage' => 'bugDescriptionMessage', 'bugDetailsMessage' => 'bugDetailsMessage', 'bugSolutionMessage' => 'bugSolutionMessage', 'bugMigrationMessage' => 'bugMigrationMessage', 'bugCampaignMessage' => 'bugCampaignMessage'))),
      'position'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'root_id'         => new sfWidgetFormFilterInput(),
      'lft'             => new sfWidgetFormFilterInput(),
      'rgt'             => new sfWidgetFormFilterInput(),
      'level'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'guard_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'subject_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubject'), 'column' => 'id')),
      'message_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubjectMessageType'), 'column' => 'id')),
      'message'         => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('bugDescriptionMessage' => 'bugDescriptionMessage', 'bugDetailsMessage' => 'bugDetailsMessage', 'bugSolutionMessage' => 'bugSolutionMessage', 'bugMigrationMessage' => 'bugMigrationMessage', 'bugCampaignMessage' => 'bugCampaignMessage'))),
      'position'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'root_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectMessage';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'guard_id'        => 'ForeignKey',
      'subject_id'      => 'ForeignKey',
      'message_type_id' => 'ForeignKey',
      'message'         => 'Text',
      'type'            => 'Enum',
      'position'        => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'root_id'         => 'Number',
      'lft'             => 'Number',
      'rgt'             => 'Number',
      'level'           => 'Number',
    );
  }
}
