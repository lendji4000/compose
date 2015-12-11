<?php

/**
 * EiTaskAuthorHistory filter form base class.
 *
 * @package    kalifastRobot
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEiTaskAuthorHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_of_change' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GuardAuthorOfChange'), 'add_empty' => true)),
      'last_author'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GuardLastAuthor'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'author_of_change' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GuardAuthorOfChange'), 'column' => 'id')),
      'last_author'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GuardLastAuthor'), 'column' => 'id')),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ei_task_author_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTaskAuthorHistory';
  }

  public function getFields()
  {
    return array(
      'task_id'          => 'Number',
      'new_author'       => 'Number',
      'date'             => 'Date',
      'author_of_change' => 'ForeignKey',
      'last_author'      => 'ForeignKey',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
