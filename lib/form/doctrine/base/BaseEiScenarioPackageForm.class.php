<?php

/**
 * EiScenarioPackage form base class.
 *
 * @method EiScenarioPackage getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiScenarioPackageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_scenario_id' => new sfWidgetFormInputHidden(),
      'package_id'     => new sfWidgetFormInputHidden(),
      'package_ref'    => new sfWidgetFormInputHidden(),
      'ei_version_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => false)),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ei_scenario_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_scenario_id')), 'empty_value' => $this->getObject()->get('ei_scenario_id'), 'required' => false)),
      'package_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('package_id')), 'empty_value' => $this->getObject()->get('package_id'), 'required' => false)),
      'package_ref'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('package_ref')), 'empty_value' => $this->getObject()->get('package_ref'), 'required' => false)),
      'ei_version_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'))),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_scenario_package[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiScenarioPackage';
  }

}
