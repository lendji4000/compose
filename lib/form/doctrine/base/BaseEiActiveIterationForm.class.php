<?php

/**
 * EiActiveIteration form base class.
 *
 * @method EiActiveIteration getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiActiveIterationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id'   => new sfWidgetFormInputHidden(),
      'project_ref'  => new sfWidgetFormInputHidden(),
      'profile_id'   => new sfWidgetFormInputHidden(),
      'profile_ref'  => new sfWidgetFormInputHidden(),
      'iteration_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'add_empty' => true)),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'project_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'project_ref'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_ref')), 'empty_value' => $this->getObject()->get('project_ref'), 'required' => false)),
      'profile_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'profile_ref'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_ref')), 'empty_value' => $this->getObject()->get('profile_ref'), 'required' => false)),
      'iteration_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiIteration'), 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_active_iteration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiActiveIteration';
  }

}
