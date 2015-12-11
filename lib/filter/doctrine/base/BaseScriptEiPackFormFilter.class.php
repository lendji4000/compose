<?php

/**
 * ScriptEiPack filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiPackFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_projet'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ref_projet' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nom_pack'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'actif'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_root'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'delta'      => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'id_projet'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ref_projet' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nom_pack'   => new sfValidatorPass(array('required' => false)),
      'actif'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_root'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'delta'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_pack_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiPack';
  }

  public function getFields()
  {
    return array(
      'id_pack'    => 'Number',
      'id_ref'     => 'Number',
      'id_projet'  => 'Number',
      'ref_projet' => 'Number',
      'nom_pack'   => 'Text',
      'actif'      => 'Boolean',
      'is_root'    => 'Boolean',
      'delta'      => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
