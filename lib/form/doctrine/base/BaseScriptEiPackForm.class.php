<?php

/**
 * ScriptEiPack form base class.
 *
 * @method ScriptEiPack getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiPackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_pack'    => new sfWidgetFormInputHidden(),
      'id_ref'     => new sfWidgetFormInputHidden(),
      'id_projet'  => new sfWidgetFormInputText(),
      'ref_projet' => new sfWidgetFormInputText(),
      'nom_pack'   => new sfWidgetFormInputText(),
      'actif'      => new sfWidgetFormInputCheckbox(),
      'is_root'    => new sfWidgetFormInputCheckbox(),
      'delta'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id_pack'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_pack')), 'empty_value' => $this->getObject()->get('id_pack'), 'required' => false)),
      'id_ref'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_ref')), 'empty_value' => $this->getObject()->get('id_ref'), 'required' => false)),
      'id_projet'  => new sfValidatorInteger(),
      'ref_projet' => new sfValidatorInteger(),
      'nom_pack'   => new sfValidatorString(array('max_length' => 45)),
      'actif'      => new sfValidatorBoolean(array('required' => false)),
      'is_root'    => new sfValidatorBoolean(array('required' => false)),
      'delta'      => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_pack[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiPack';
  }

}
