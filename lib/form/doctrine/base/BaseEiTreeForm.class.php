<?php

/**
 * EiTree form base class.
 *
 * @method EiTree getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTreeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'type'        => new sfWidgetFormInputText(),
      'obj_id'      => new sfWidgetFormInputText(),
      'ref_obj'     => new sfWidgetFormInputText(),
      'is_root'     => new sfWidgetFormInputCheckbox(),
      'project_id'  => new sfWidgetFormInputText(),
      'project_ref' => new sfWidgetFormInputText(),
      'position'    => new sfWidgetFormInputText(),
      'path'        => new sfWidgetFormTextarea(),
      'root_id'     => new sfWidgetFormInputText(),
      'lft'         => new sfWidgetFormInputText(),
      'rgt'         => new sfWidgetFormInputText(),
      'level'       => new sfWidgetFormInputText(),
      'delta'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 45)),
      'type'        => new sfValidatorString(array('max_length' => 45)),
      'obj_id'      => new sfValidatorInteger(),
      'ref_obj'     => new sfValidatorInteger(),
      'is_root'     => new sfValidatorBoolean(array('required' => false)),
      'project_id'  => new sfValidatorInteger(),
      'project_ref' => new sfValidatorInteger(),
      'position'    => new sfValidatorInteger(),
      'path'        => new sfValidatorString(array('required' => false)),
      'root_id'     => new sfValidatorInteger(),
      'lft'         => new sfValidatorInteger(array('required' => false)),
      'rgt'         => new sfValidatorInteger(array('required' => false)),
      'level'       => new sfValidatorInteger(array('required' => false)),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_tree[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTree';
  }

}
