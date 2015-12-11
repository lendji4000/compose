<?php

/**
 * ScriptEiParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type_param'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nom_param'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'desc_param'      => new sfWidgetFormFilterInput(),
      'valeur_defaut'   => new sfWidgetFormFilterInput(),
      'est_obligatoire' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'type_param'      => new sfValidatorPass(array('required' => false)),
      'nom_param'       => new sfValidatorPass(array('required' => false)),
      'desc_param'      => new sfValidatorPass(array('required' => false)),
      'valeur_defaut'   => new sfValidatorPass(array('required' => false)),
      'est_obligatoire' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiParam';
  }

  public function getFields()
  {
    return array(
      'ref_function'    => 'Number',
      'id_function'     => 'Number',
      'num_param'       => 'Number',
      'type_param'      => 'Text',
      'nom_param'       => 'Text',
      'desc_param'      => 'Text',
      'valeur_defaut'   => 'Text',
      'est_obligatoire' => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
