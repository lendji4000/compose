<?php

/**
 * ScriptEiImgNotice form base class.
 *
 * @method ScriptEiImgNotice getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiImgNoticeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_img_notice'     => new sfWidgetFormInputHidden(),
      'ei_version_id_notice' => new sfWidgetFormInputHidden(),
      'id_notice'         => new sfWidgetFormInputHidden(),
      'ref_notice'        => new sfWidgetFormInputHidden(),
      'filename'          => new sfWidgetFormInputText(),
      'caption'           => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'defaut'            => new sfWidgetFormInputCheckbox(),
      'id_pertinence'     => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id_img_notice'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_img_notice')), 'empty_value' => $this->getObject()->get('id_img_notice'), 'required' => false)),
      'ei_version_id_notice' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_version_id_notice')), 'empty_value' => $this->getObject()->get('ei_version_id_notice'), 'required' => false)),
      'id_notice'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id_notice')), 'empty_value' => $this->getObject()->get('id_notice'), 'required' => false)),
      'ref_notice'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_notice')), 'empty_value' => $this->getObject()->get('ref_notice'), 'required' => false)),
      'filename'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'caption'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('required' => false)),
      'defaut'            => new sfValidatorBoolean(array('required' => false)),
      'id_pertinence'     => new sfValidatorPass(),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_img_notice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiImgNotice';
  }

}
