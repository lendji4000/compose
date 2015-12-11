<?php

/**
 * ScriptEiFunctionHasCommande filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiFunctionHasCommandeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'function_ref'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'function_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'script_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'command_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'position'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'num_version'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'selenium_ref'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'command_target' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'command_value'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delta'          => new sfWidgetFormFilterInput(),
      'deltaf'         => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'function_ref'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'function_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'script_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'command_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num_version'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'selenium_ref'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'command_target' => new sfValidatorPass(array('required' => false)),
      'command_value'  => new sfValidatorPass(array('required' => false)),
      'delta'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deltaf'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_function_has_commande_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiFunctionHasCommande';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'function_ref'   => 'Number',
      'function_id'    => 'Number',
      'script_id'      => 'Number',
      'command_id'     => 'Number',
      'position'       => 'Number',
      'num_version'    => 'Number',
      'selenium_ref'   => 'Number',
      'command_target' => 'Text',
      'command_value'  => 'Text',
      'delta'          => 'Number',
      'deltaf'         => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
