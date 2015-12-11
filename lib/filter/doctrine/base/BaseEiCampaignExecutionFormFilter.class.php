<?php

/**
 * EiCampaignExecution filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiCampaignExecutionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_id'  => new sfWidgetFormFilterInput(),
      'profile_ref' => new sfWidgetFormFilterInput(),
      'project_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'project_ref' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'author_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'campaign_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiCampaign'), 'add_empty' => true)),
      'termine'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'on_error'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiBlockType'), 'add_empty' => true)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'profile_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_ref' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'project_ref' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'author_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'campaign_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiCampaign'), 'column' => 'id')),
      'termine'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'on_error'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EiBlockType'), 'column' => 'id')),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_campaign_execution_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiCampaignExecution';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'profile_id'  => 'Number',
      'profile_ref' => 'Number',
      'project_id'  => 'Number',
      'project_ref' => 'Number',
      'author_id'   => 'Number',
      'campaign_id' => 'ForeignKey',
      'termine'     => 'Boolean',
      'on_error'    => 'ForeignKey',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
