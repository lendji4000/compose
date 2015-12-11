<?php

/**
 * KalParam form base class.
 *
 * @method KalParam getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseKalParamForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'code_fonction'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('KalFonction'), 'add_empty' => false)),
      'type_param'      => new sfWidgetFormInputText(),
      'nom_param'       => new sfWidgetFormInputText(),
      'desc_param'      => new sfWidgetFormTextarea(),
      'valeur_defaut'   => new sfWidgetFormInputText(),
      'est_obligatoire' => new sfWidgetFormInputCheckbox(),
      'cn_param_kal'    => new sfWidgetFormInputText(),
      'cf_id_kal'       => new sfWidgetFormInputText(),
      'cf_ref_kal'      => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code_fonction'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('KalFonction'))),
      'type_param'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'nom_param'       => new sfValidatorString(array('max_length' => 255)),
      'desc_param'      => new sfValidatorString(),
      'valeur_defaut'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'est_obligatoire' => new sfValidatorBoolean(),
      'cn_param_kal'    => new sfValidatorInteger(array('required' => false)),
      'cf_id_kal'       => new sfValidatorInteger(array('required' => false)),
      'cf_ref_kal'      => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('kal_param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'KalParam';
  }

}
