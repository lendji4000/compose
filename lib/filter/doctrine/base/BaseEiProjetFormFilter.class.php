<?php

/**
 * EiProjet filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiProjetFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'         => new sfWidgetFormFilterInput(),
      'state'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'default_notice_lang' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_ref'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'system_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'version'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'version_courante'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'version_kalifast'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'obsolete'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'checked_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'                => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'state'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'default_notice_lang' => new sfValidatorPass(array('required' => false)),
      'user_ref'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'system_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'version'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'version_courante'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'version_kalifast'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'obsolete'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'checked_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_projet_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiProjet';
  }

  public function getFields()
  {
    return array(
      'ref_id'              => 'Number',
      'project_id'          => 'Number',
      'name'                => 'Text',
      'description'         => 'Text',
      'state'               => 'Number',
      'default_notice_lang' => 'Text',
      'user_ref'            => 'Number',
      'user_id'             => 'Number',
      'system_id'           => 'Number',
      'version'             => 'Number',
      'version_courante'    => 'Number',
      'version_kalifast'    => 'Number',
      'obsolete'            => 'Boolean',
      'checked_at'          => 'Date',
      'updated_at'          => 'Date',
      'created_at'          => 'Date',
    );
  }
}
