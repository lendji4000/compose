<?php

/**
 * ScriptEiFunctionHasPack form base class.
 *
 * @method ScriptEiFunctionHasPack getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiFunctionHasPackForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_pack'         => new sfWidgetFormInputHidden(),
      'id_ref_pack'     => new sfWidgetFormInputHidden(),
      'id_function'     => new sfWidgetFormInputHidden(),
      'id_ref_function' => new sfWidgetFormInputHidden(),
      'id_pack_cible'   => new sfWidgetFormInputHidden(),
      'ref_pack_cible'  => new sfWidgetFormInputHidden(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id_pack'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_pack')), 'empty_value' => $this->getObject()->get('id_pack'), 'required' => false)),
      'id_ref_pack'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_ref_pack')), 'empty_value' => $this->getObject()->get('id_ref_pack'), 'required' => false)),
      'id_function'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_function')), 'empty_value' => $this->getObject()->get('id_function'), 'required' => false)),
      'id_ref_function' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_ref_function')), 'empty_value' => $this->getObject()->get('id_ref_function'), 'required' => false)),
      'id_pack_cible'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_pack_cible')), 'empty_value' => $this->getObject()->get('id_pack_cible'), 'required' => false)),
      'ref_pack_cible'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_pack_cible')), 'empty_value' => $this->getObject()->get('ref_pack_cible'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_function_has_pack[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiFunctionHasPack';
  }

}
