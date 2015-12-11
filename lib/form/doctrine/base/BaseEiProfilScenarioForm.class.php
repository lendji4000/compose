<?php

/**
 * EiProfilScenario form base class.
 *
 * @method EiProfilScenario getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiProfilScenarioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_id'     => new sfWidgetFormInputHidden(),
      'profile_ref'    => new sfWidgetFormInputHidden(),
      'ei_scenario_id' => new sfWidgetFormInputHidden(),
      'ei_version_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'), 'add_empty' => false)),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'profile_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'profile_ref'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_ref')), 'empty_value' => $this->getObject()->get('profile_ref'), 'required' => false)),
      'ei_scenario_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_scenario_id')), 'empty_value' => $this->getObject()->get('ei_scenario_id'), 'required' => false)),
      'ei_version_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiVersion'))),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_profil_scenario[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiProfilScenario';
  }

}
