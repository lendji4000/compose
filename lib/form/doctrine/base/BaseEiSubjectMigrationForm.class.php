<?php

/**
 * EiSubjectMigration form base class.
 *
 * @method EiSubjectMigration getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectMigrationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'migration'  => new sfWidgetFormTextarea(),
      'subject_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'), 'add_empty' => false)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'migration'  => new sfValidatorString(array('required' => false)),
      'subject_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubject'))),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_migration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectMigration';
  }

}
