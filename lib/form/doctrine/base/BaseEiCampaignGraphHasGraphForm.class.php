<?php

/**
 * EiCampaignGraphHasGraph form base class.
 *
 * @method EiCampaignGraphHasGraph getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignGraphHasGraphForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_id'   => new sfWidgetFormInputHidden(),
      'child_id'    => new sfWidgetFormInputHidden(),
      'campaign_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => false)),
      'position'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'parent_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('parent_id')), 'empty_value' => $this->getObject()->get('parent_id'), 'required' => false)),
      'child_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('child_id')), 'empty_value' => $this->getObject()->get('child_id'), 'required' => false)),
      'campaign_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'))),
      'position'    => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_graph_has_graph[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignGraphHasGraph';
  }

}
