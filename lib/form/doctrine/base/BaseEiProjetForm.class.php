<?php

/**
 * EiProjet form base class.
 *
 * @method EiProjet getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiProjetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ref_id'              => new sfWidgetFormInputHidden(),
      'project_id'          => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormTextarea(),
      'state'               => new sfWidgetFormInputText(),
      'default_notice_lang' => new sfWidgetFormInputText(),
      'user_ref'            => new sfWidgetFormInputText(),
      'user_id'             => new sfWidgetFormInputText(),
      'system_id'           => new sfWidgetFormInputText(),
      'version'             => new sfWidgetFormInputText(),
      'version_courante'    => new sfWidgetFormInputText(),
      'version_kalifast'    => new sfWidgetFormInputText(),
      'obsolete'            => new sfWidgetFormInputCheckbox(),
      'checked_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'created_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ref_id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_id')), 'empty_value' => $this->getObject()->get('ref_id'), 'required' => false)),
      'project_id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 90)),
      'description'         => new sfValidatorString(array('required' => false)),
      'state'               => new sfValidatorInteger(),
      'default_notice_lang' => new sfValidatorString(array('max_length' => 255)),
      'user_ref'            => new sfValidatorInteger(),
      'user_id'             => new sfValidatorInteger(),
      'system_id'           => new sfValidatorInteger(),
      'version'             => new sfValidatorInteger(array('required' => false)),
      'version_courante'    => new sfValidatorInteger(array('required' => false)),
      'version_kalifast'    => new sfValidatorInteger(array('required' => false)),
      'obsolete'            => new sfValidatorBoolean(array('required' => false)),
      'checked_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(),
      'created_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_projet[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiProjet';
  }

}
