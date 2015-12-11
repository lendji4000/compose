<?php

/**
 * ScriptEiPackHasPack form base class.
 *
 * @method ScriptEiPackHasPack getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiPackHasPackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_pack'           => new sfWidgetFormInputHidden(),
      'id_ref'            => new sfWidgetFormInputHidden(),
      'id_pack_conteneur' => new sfWidgetFormInputHidden(),
      'id_ref_conteneur'  => new sfWidgetFormInputHidden(),
      'persistence'       => new sfWidgetFormInputCheckbox(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id_pack'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_pack')), 'empty_value' => $this->getObject()->get('id_pack'), 'required' => false)),
      'id_ref'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_ref')), 'empty_value' => $this->getObject()->get('id_ref'), 'required' => false)),
      'id_pack_conteneur' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_pack_conteneur')), 'empty_value' => $this->getObject()->get('id_pack_conteneur'), 'required' => false)),
      'id_ref_conteneur'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_ref_conteneur')), 'empty_value' => $this->getObject()->get('id_ref_conteneur'), 'required' => false)),
      'persistence'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_pack_has_pack[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiPackHasPack';
  }

}
