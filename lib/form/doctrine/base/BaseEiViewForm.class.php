<?php

/**
 * EiView form base class.
 *
 * @method EiView getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiViewForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'view_id'     => new sfWidgetFormInputHidden(),
      'view_ref'    => new sfWidgetFormInputHidden(),
      'project_id'  => new sfWidgetFormInputText(),
      'project_ref' => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'is_active'   => new sfWidgetFormInputCheckbox(),
      'delta'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'view_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('view_id')), 'empty_value' => $this->getObject()->get('view_id'), 'required' => false)),
      'view_ref'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('view_ref')), 'empty_value' => $this->getObject()->get('view_ref'), 'required' => false)),
      'project_id'  => new sfValidatorInteger(),
      'project_ref' => new sfValidatorInteger(),
      'description' => new sfValidatorString(array('required' => false)),
      'is_active'   => new sfValidatorBoolean(),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_view[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiView';
  }

}
