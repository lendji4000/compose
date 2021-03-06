<?php

/**
 * ScriptEiTree filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiTreeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'obj_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ref_obj'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_root'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'project_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'position'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'root_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lft'         => new sfWidgetFormFilterInput(),
      'rgt'         => new sfWidgetFormFilterInput(),
      'level'       => new sfWidgetFormFilterInput(),
      'delta'       => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'        => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorPass(array('required' => false)),
      'obj_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ref_obj'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_root'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'project_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'root_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'delta'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_tree_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiTree';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'name'        => 'Text',
      'type'        => 'Text',
      'obj_id'      => 'Number',
      'ref_obj'     => 'Number',
      'is_root'     => 'Boolean',
      'project_id'  => 'Number',
      'project_ref' => 'Number',
      'position'    => 'Number',
      'root_id'     => 'Number',
      'lft'         => 'Number',
      'rgt'         => 'Number',
      'level'       => 'Number',
      'delta'       => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
