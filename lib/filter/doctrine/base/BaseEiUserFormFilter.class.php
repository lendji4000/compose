<?php

/**
 * EiUser filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'guard_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'matricule'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delta'      => new sfWidgetFormFilterInput(),
      'token_api'  => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'guard_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'matricule'  => new sfValidatorPass(array('required' => false)),
      'delta'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'token_api'  => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiUser';
  }

  public function getFields()
  {
    return array(
      'ref_id'     => 'Number',
      'user_id'    => 'Number',
      'guard_id'   => 'Number',
      'matricule'  => 'Text',
      'delta'      => 'Number',
      'token_api'  => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
