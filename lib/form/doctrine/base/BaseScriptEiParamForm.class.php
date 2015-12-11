<?php

/**
 * ScriptEiParam form base class.
 *
 * @method ScriptEiParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ref_function'    => new sfWidgetFormInputHidden(),
      'id_function'     => new sfWidgetFormInputHidden(),
      'num_param'       => new sfWidgetFormInputHidden(),
      'type_param'      => new sfWidgetFormInputText(),
      'nom_param'       => new sfWidgetFormInputText(),
      'desc_param'      => new sfWidgetFormTextarea(),
      'valeur_defaut'   => new sfWidgetFormTextarea(),
      'est_obligatoire' => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ref_function'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_function')), 'empty_value' => $this->getObject()->get('ref_function'), 'required' => false)),
      'id_function'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_function')), 'empty_value' => $this->getObject()->get('id_function'), 'required' => false)),
      'num_param'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('num_param')), 'empty_value' => $this->getObject()->get('num_param'), 'required' => false)),
      'type_param'      => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'nom_param'       => new sfValidatorString(array('max_length' => 45)),
      'desc_param'      => new sfValidatorString(array('required' => false)),
      'valeur_defaut'   => new sfValidatorString(array('required' => false)),
      'est_obligatoire' => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiParam';
  }

}
