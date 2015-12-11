<?php

/**
 * EiSubjectMessage form base class.
 *
 * @method EiSubjectMessage getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectMessageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'guard_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'subject_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'), 'add_empty' => false)),
      'message_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectMessageType'), 'add_empty' => false)),
      'message'         => new sfWidgetFormTextarea(),
      'type'            => new sfWidgetFormChoice(array('choices' => array('bugDescriptionMessage' => 'bugDescriptionMessage', 'bugDetailsMessage' => 'bugDetailsMessage', 'bugSolutionMessage' => 'bugSolutionMessage', 'bugMigrationMessage' => 'bugMigrationMessage', 'bugCampaignMessage' => 'bugCampaignMessage'))),
      'position'        => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'root_id'         => new sfWidgetFormInputText(),
      'lft'             => new sfWidgetFormInputText(),
      'rgt'             => new sfWidgetFormInputText(),
      'level'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'guard_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'subject_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'))),
      'message_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectMessageType'))),
      'message'         => new sfValidatorString(),
      'type'            => new sfValidatorChoice(array('choices' => array(0 => 'bugDescriptionMessage', 1 => 'bugDetailsMessage', 2 => 'bugSolutionMessage', 3 => 'bugMigrationMessage', 4 => 'bugCampaignMessage'), 'required' => false)),
      'position'        => new sfValidatorInteger(),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'root_id'         => new sfValidatorInteger(array('required' => false)),
      'lft'             => new sfValidatorInteger(array('required' => false)),
      'rgt'             => new sfValidatorInteger(array('required' => false)),
      'level'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectMessage';
  }

}
