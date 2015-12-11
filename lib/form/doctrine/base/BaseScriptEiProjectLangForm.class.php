<?php

/**
 * ScriptEiProjectLang form base class.
 *
 * @method ScriptEiProjectLang getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiProjectLangForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'lang'        => new sfWidgetFormInputHidden(),
      'project_ref' => new sfWidgetFormInputHidden(),
      'project_id'  => new sfWidgetFormInputHidden(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'lang'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('lang')), 'empty_value' => $this->getObject()->get('lang'), 'required' => false)),
      'project_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_ref')), 'empty_value' => $this->getObject()->get('project_ref'), 'required' => false)),
      'project_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('project_id')), 'empty_value' => $this->getObject()->get('project_id'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_project_lang[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiProjectLang';
  }

}
