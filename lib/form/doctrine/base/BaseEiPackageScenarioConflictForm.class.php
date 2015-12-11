<?php

/**
 * EiPackageScenarioConflict form base class.
 *
 * @method EiPackageScenarioConflict getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiPackageScenarioConflictForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_scenario_id'  => new sfWidgetFormInputHidden(),
      'delivery_id'     => new sfWidgetFormInputHidden(),
      'package_id'      => new sfWidgetFormInputText(),
      'package_ref'     => new sfWidgetFormInputText(),
      'resolved_date'   => new sfWidgetFormDateTime(),
      'resolved_author' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ei_scenario_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_scenario_id')), 'empty_value' => $this->getObject()->get('ei_scenario_id'), 'required' => false)),
      'delivery_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('delivery_id')), 'empty_value' => $this->getObject()->get('delivery_id'), 'required' => false)),
      'package_id'      => new sfValidatorInteger(),
      'package_ref'     => new sfValidatorInteger(),
      'resolved_date'   => new sfValidatorDateTime(),
      'resolved_author' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_package_scenario_conflict[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiPackageScenarioConflict';
  }

}
