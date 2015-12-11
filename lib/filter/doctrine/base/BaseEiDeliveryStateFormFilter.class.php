<?php

/**
 * EiDeliveryState filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiDeliveryStateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'color_code'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'display_in_home_page' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'display_in_search'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'close_state'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'project_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'                 => new sfValidatorPass(array('required' => false)),
      'color_code'           => new sfValidatorPass(array('required' => false)),
      'display_in_home_page' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'display_in_search'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'close_state'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'project_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_delivery_state_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDeliveryState';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'name'                 => 'Text',
      'color_code'           => 'Text',
      'display_in_home_page' => 'Boolean',
      'display_in_search'    => 'Boolean',
      'close_state'          => 'Boolean',
      'project_id'           => 'Number',
      'project_ref'          => 'Number',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
