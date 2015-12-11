<?php

/**
 * EiBugContext form base class.
 *
 * @method EiBugContext getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiBugContextForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'author_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextAuthor'), 'add_empty' => false)),
      'delivery_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextDelivery'), 'add_empty' => true)),
      'campaign_graph_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaignStep'), 'add_empty' => true)),
      'campaign_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaign'), 'add_empty' => true)),
      'subject_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextSubject'), 'add_empty' => false)),
      'scenario_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextScenario'), 'add_empty' => true)),
      'ei_fonction_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextFunction'), 'add_empty' => true)),
      'ei_test_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextTestSet'), 'add_empty' => true)),
      'ei_data_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextJdd'), 'add_empty' => true)),
      'profile_id'        => new sfWidgetFormInputText(),
      'profile_ref'       => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextAuthor'))),
      'delivery_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextDelivery'), 'required' => false)),
      'campaign_graph_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaignStep'), 'required' => false)),
      'campaign_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaign'), 'required' => false)),
      'subject_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextSubject'))),
      'scenario_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextScenario'), 'required' => false)),
      'ei_fonction_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextFunction'), 'required' => false)),
      'ei_test_set_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextTestSet'), 'required' => false)),
      'ei_data_set_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextJdd'), 'required' => false)),
      'profile_id'        => new sfValidatorInteger(array('required' => false)),
      'profile_ref'       => new sfValidatorInteger(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_bug_context[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiBugContext';
  }

}
