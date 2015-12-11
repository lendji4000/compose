<?php

/**
 * EiParam filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiParamFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_fonction' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFonction'), 'add_empty' => true)),
      'param_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiFunctionHasParam'), 'add_empty' => true)),
      'valeur'      => new sfWidgetFormFilterInput(),
      'observation' => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'id_fonction' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFonction'), 'column' => 'id')),
      'param_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiFunctionHasParam'), 'column' => 'param_id')),
      'valeur'      => new sfValidatorPass(array('required' => false)),
      'observation' => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_param_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiParam';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'id_fonction' => 'ForeignKey',
      'param_id'    => 'ForeignKey',
      'valeur'      => 'Text',
      'observation' => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
