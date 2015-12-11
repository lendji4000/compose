<?php

/**
 * EiSubjectType form base class.
 *
 * @method EiSubjectType getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiSubjectTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'name'                 => new sfWidgetFormInputText(),
      'display_in_home_page' => new sfWidgetFormInputCheckbox(),
      'display_in_search'    => new sfWidgetFormInputCheckbox(),
      'display_in_todolist'  => new sfWidgetFormInputCheckbox(),
      'project_id'           => new sfWidgetFormInputText(),
      'project_ref'          => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                 => new sfValidatorString(array('max_length' => 255)),
      'display_in_home_page' => new sfValidatorBoolean(array('required' => false)),
      'display_in_search'    => new sfValidatorBoolean(array('required' => false)),
      'display_in_todolist'  => new sfValidatorBoolean(array('required' => false)),
      'project_id'           => new sfValidatorInteger(),
      'project_ref'          => new sfValidatorInteger(),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_subject_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiSubjectType';
  }

}
