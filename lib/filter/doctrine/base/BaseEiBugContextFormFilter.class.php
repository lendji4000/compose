<?php

/**
 * EiBugContext filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiBugContextFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextAuthor'), 'add_empty' => true)),
      'delivery_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextDelivery'), 'add_empty' => true)),
      'campaign_graph_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaignStep'), 'add_empty' => true)),
      'campaign_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextCampaign'), 'add_empty' => true)),
      'subject_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextSubject'), 'add_empty' => true)),
      'scenario_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextScenario'), 'add_empty' => true)),
      'ei_fonction_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextFunction'), 'add_empty' => true)),
      'ei_test_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextTestSet'), 'add_empty' => true)),
      'ei_data_set_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('bugContextJdd'), 'add_empty' => true)),
      'profile_id'        => new sfWidgetFormFilterInput(),
      'profile_ref'       => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'author_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextAuthor'), 'column' => 'id')),
      'delivery_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextDelivery'), 'column' => 'id')),
      'campaign_graph_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextCampaignStep'), 'column' => 'id')),
      'campaign_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextCampaign'), 'column' => 'id')),
      'subject_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextSubject'), 'column' => 'id')),
      'scenario_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextScenario'), 'column' => 'id')),
      'ei_fonction_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextFunction'), 'column' => 'id')),
      'ei_test_set_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextTestSet'), 'column' => 'id')),
      'ei_data_set_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('bugContextJdd'), 'column' => 'id')),
      'profile_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_bug_context_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiBugContext';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'author_id'         => 'ForeignKey',
      'delivery_id'       => 'ForeignKey',
      'campaign_graph_id' => 'ForeignKey',
      'campaign_id'       => 'ForeignKey',
      'subject_id'        => 'ForeignKey',
      'scenario_id'       => 'ForeignKey',
      'ei_fonction_id'    => 'ForeignKey',
      'ei_test_set_id'    => 'ForeignKey',
      'ei_data_set_id'    => 'ForeignKey',
      'profile_id'        => 'Number',
      'profile_ref'       => 'Number',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
