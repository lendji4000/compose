<?php

/**
 * ScriptEiEnvironnementProjet filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseScriptEiEnvironnementProjetFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_projet'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ref_projet'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url_base'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nom_environnement'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'desc_environnement' => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'id_projet'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ref_projet'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'url_base'           => new sfValidatorPass(array('required' => false)),
      'nom_environnement'  => new sfValidatorPass(array('required' => false)),
      'desc_environnement' => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('script_ei_environnement_projet_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiEnvironnementProjet';
  }

  public function getFields()
  {
    return array(
      'ref_environnement'  => 'Number',
      'id_environnement'   => 'Number',
      'id_projet'          => 'Number',
      'ref_projet'         => 'Number',
      'url_base'           => 'Text',
      'nom_environnement'  => 'Text',
      'desc_environnement' => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
