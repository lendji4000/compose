<?php

/**
 * EiExcelRequests form base class.
 *
 * @method EiExcelRequests getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiExcelRequestsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'project_id'              => new sfWidgetFormInputText(),
      'project_ref'             => new sfWidgetFormInputText(),
      'profile_id'              => new sfWidgetFormInputText(),
      'profile_ref'             => new sfWidgetFormInputText(),
      'user_id'                 => new sfWidgetFormInputText(),
      'user_ref'                => new sfWidgetFormInputText(),
      'ei_test_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'add_empty' => true)),
      'ei_data_set_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'add_empty' => true)),
      'ei_data_set_template_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetTemplate'), 'add_empty' => true)),
      'state'                   => new sfWidgetFormInputCheckbox(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'project_id'              => new sfValidatorInteger(array('required' => false)),
      'project_ref'             => new sfValidatorInteger(array('required' => false)),
      'profile_id'              => new sfValidatorInteger(array('required' => false)),
      'profile_ref'             => new sfValidatorInteger(array('required' => false)),
      'user_id'                 => new sfValidatorInteger(array('required' => false)),
      'user_ref'                => new sfValidatorInteger(array('required' => false)),
      'ei_test_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiTestSet'), 'required' => false)),
      'ei_data_set_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSet'), 'required' => false)),
      'ei_data_set_template_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDataSetTemplate'), 'required' => false)),
      'state'                   => new sfValidatorBoolean(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_excel_requests[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiExcelRequests';
  }

}
