<?php

/**
 * EiNoticeProfil form base class.
 *
 * @method EiNoticeProfil getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiNoticeProfilForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'version_notice_id' => new sfWidgetFormInputHidden(),
      'notice_id'         => new sfWidgetFormInputHidden(),
      'notice_ref'        => new sfWidgetFormInputHidden(),
      'profile_id'        => new sfWidgetFormInputHidden(),
      'profile_ref'       => new sfWidgetFormInputHidden(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'version_notice_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('version_notice_id')), 'empty_value' => $this->getObject()->get('version_notice_id'), 'required' => false)),
      'notice_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_id')), 'empty_value' => $this->getObject()->get('notice_id'), 'required' => false)),
      'notice_ref'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('notice_ref')), 'empty_value' => $this->getObject()->get('notice_ref'), 'required' => false)),
      'profile_id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'profile_ref'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_ref')), 'empty_value' => $this->getObject()->get('profile_ref'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_notice_profil[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiNoticeProfil';
  }

}
