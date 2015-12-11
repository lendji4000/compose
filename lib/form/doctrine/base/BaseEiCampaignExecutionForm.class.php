<?php

/**
 * EiCampaignExecution form base class.
 *
 * @method EiCampaignExecution getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignExecutionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'profile_id'  => new sfWidgetFormInputText(),
      'profile_ref' => new sfWidgetFormInputText(),
      'project_id'  => new sfWidgetFormInputText(),
      'project_ref' => new sfWidgetFormInputText(),
      'author_id'   => new sfWidgetFormInputText(),
      'campaign_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => false)),
      'termine'     => new sfWidgetFormInputCheckbox(),
      'on_error'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockType'), 'add_empty' => true)),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'profile_id'  => new sfValidatorInteger(array('required' => false)),
      'profile_ref' => new sfValidatorInteger(array('required' => false)),
      'project_id'  => new sfValidatorInteger(),
      'project_ref' => new sfValidatorInteger(),
      'author_id'   => new sfValidatorInteger(),
      'campaign_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'))),
      'termine'     => new sfValidatorBoolean(array('required' => false)),
      'on_error'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockType'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_execution[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignExecution';
  }

}
