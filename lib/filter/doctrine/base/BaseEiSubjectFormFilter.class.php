<?php

/**
 * EiSubject filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'delivery_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDelivery'), 'add_empty' => true)),
      'subject_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectType'), 'add_empty' => true)),
      'subject_state_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectState'), 'add_empty' => true)),
      'subject_priority_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiSubjectPriority'), 'add_empty' => true)),
      'project_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'            => new sfWidgetFormFilterInput(),
      'alternative_system_id'  => new sfWidgetFormFilterInput(),
      'package_id'             => new sfWidgetFormFilterInput(),
      'package_ref'            => new sfWidgetFormFilterInput(),
      'development_time'       => new sfWidgetFormFilterInput(),
      'development_estimation' => new sfWidgetFormFilterInput(),
      'test_time'              => new sfWidgetFormFilterInput(),
      'test_estimation'        => new sfWidgetFormFilterInput(),
      'expected_date'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'author_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'delivery_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiDelivery'), 'column' => 'id')),
      'subject_type_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubjectType'), 'column' => 'id')),
      'subject_state_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubjectState'), 'column' => 'id')),
      'subject_priority_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiSubjectPriority'), 'column' => 'id')),
      'project_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'                   => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'alternative_system_id'  => new sfValidatorPass(array('required' => false)),
      'package_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'package_ref'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'development_time'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'development_estimation' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'test_time'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'test_estimation'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expected_date'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubject';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'author_id'              => 'ForeignKey',
      'delivery_id'            => 'ForeignKey',
      'subject_type_id'        => 'ForeignKey',
      'subject_state_id'       => 'ForeignKey',
      'subject_priority_id'    => 'ForeignKey',
      'project_id'             => 'Number',
      'project_ref'            => 'Number',
      'name'                   => 'Text',
      'description'            => 'Text',
      'alternative_system_id'  => 'Text',
      'package_id'             => 'Number',
      'package_ref'            => 'Number',
      'development_time'       => 'Number',
      'development_estimation' => 'Number',
      'test_time'              => 'Number',
      'test_estimation'        => 'Number',
      'expected_date'          => 'Date',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
