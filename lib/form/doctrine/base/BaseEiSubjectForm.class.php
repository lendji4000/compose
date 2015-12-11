<?php

/**
 * EiSubject form base class.
 *
 * @method EiSubject getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'author_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'delivery_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDelivery'), 'add_empty' => true)),
      'subject_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectType'), 'add_empty' => false)),
      'subject_state_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectState'), 'add_empty' => false)),
      'subject_priority_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectPriority'), 'add_empty' => false)),
      'project_id'             => new sfWidgetFormInputText(),
      'project_ref'            => new sfWidgetFormInputText(),
      'name'                   => new sfWidgetFormInputText(),
      'description'            => new sfWidgetFormTextarea(),
      'alternative_system_id'  => new sfWidgetFormInputText(),
      'package_id'             => new sfWidgetFormInputText(),
      'package_ref'            => new sfWidgetFormInputText(),
      'development_time'       => new sfWidgetFormInputText(),
      'development_estimation' => new sfWidgetFormInputText(),
      'test_time'              => new sfWidgetFormInputText(),
      'test_estimation'        => new sfWidgetFormInputText(),
      'expected_date'          => new sfWidgetFormDateTime(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'delivery_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDelivery'), 'required' => false)),
      'subject_type_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectType'))),
      'subject_state_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectState'))),
      'subject_priority_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectPriority'))),
      'project_id'             => new sfValidatorInteger(),
      'project_ref'            => new sfValidatorInteger(),
      'name'                   => new sfValidatorString(array('max_length' => 255)),
      'description'            => new sfValidatorString(array('required' => false)),
      'alternative_system_id'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'package_id'             => new sfValidatorInteger(array('required' => false)),
      'package_ref'            => new sfValidatorInteger(array('required' => false)),
      'development_time'       => new sfValidatorInteger(array('required' => false)),
      'development_estimation' => new sfValidatorInteger(array('required' => false)),
      'test_time'              => new sfValidatorInteger(array('required' => false)),
      'test_estimation'        => new sfValidatorInteger(array('required' => false)),
      'expected_date'          => new sfValidatorDateTime(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubject';
  }

}
