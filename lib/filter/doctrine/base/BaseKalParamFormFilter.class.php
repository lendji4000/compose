<?php

/**
 * KalParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseKalParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code_fonction'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('KalFonction'), 'add_empty' => true)),
      'type_param'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nom_param'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'desc_param'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'valeur_defaut'   => new sfWidgetFormFilterInput(),
      'est_obligatoire' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'cn_param_kal'    => new sfWidgetFormFilterInput(),
      'cf_id_kal'       => new sfWidgetFormFilterInput(),
      'cf_ref_kal'      => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'code_fonction'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('KalFonction'), 'column' => 'id')),
      'type_param'      => new sfValidatorPass(array('required' => false)),
      'nom_param'       => new sfValidatorPass(array('required' => false)),
      'desc_param'      => new sfValidatorPass(array('required' => false)),
      'valeur_defaut'   => new sfValidatorPass(array('required' => false)),
      'est_obligatoire' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'cn_param_kal'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cf_id_kal'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cf_ref_kal'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('kal_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KalParam';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'code_fonction'   => 'ForeignKey',
      'type_param'      => 'Text',
      'nom_param'       => 'Text',
      'desc_param'      => 'Text',
      'valeur_defaut'   => 'Text',
      'est_obligatoire' => 'Boolean',
      'cn_param_kal'    => 'Number',
      'cf_id_kal'       => 'Number',
      'cf_ref_kal'      => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
