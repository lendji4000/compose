<?php

/**
 * EiProfil form base class.
 *
 * @method EiProfil getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiProfilForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_ref' => new sfWidgetFormInputHidden(),
      'project_id'  => new sfWidgetFormInputHidden(),
      'profile_ref' => new sfWidgetFormInputHidden(),
      'profile_id'  => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'base_url'    => new sfWidgetFormTextarea(),
      'description' => new sfWidgetFormTextarea(),
      'is_default'  => new sfWidgetFormInputText(),
      'parent_id'   => new sfWidgetFormInputText(),
      'parent_ref'  => new sfWidgetFormInputText(),
      'delta'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'project_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_ref')), 'empty_value' => $this->getObject()->get('project_ref'), 'required' => false)),
      'project_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'profile_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_ref')), 'empty_value' => $this->getObject()->get('profile_ref'), 'required' => false)),
      'profile_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'base_url'    => new sfValidatorString(array('required' => false)),
      'description' => new sfValidatorString(array('required' => false)),
      'is_default'  => new sfValidatorInteger(),
      'parent_id'   => new sfValidatorInteger(array('required' => false)),
      'parent_ref'  => new sfValidatorInteger(array('required' => false)),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_profil[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiProfil';
  }

}
