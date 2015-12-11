<?php

/**
 * EiFunctionHasCommande form base class.
 *
 * @method EiFunctionHasCommande getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiFunctionHasCommandeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'function_ref'   => new sfWidgetFormInputText(),
      'function_id'    => new sfWidgetFormInputText(),
      'script_id'      => new sfWidgetFormInputText(),
      'command_id'     => new sfWidgetFormInputText(),
      'name'           => new sfWidgetFormInputText(),
      'position'       => new sfWidgetFormInputText(),
      'num_version'    => new sfWidgetFormInputText(),
      'selenium_ref'   => new sfWidgetFormInputText(),
      'command_target' => new sfWidgetFormTextarea(),
      'command_value'  => new sfWidgetFormTextarea(),
      'delta'          => new sfWidgetFormInputText(),
      'deltaf'         => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'function_ref'   => new sfValidatorInteger(),
      'function_id'    => new sfValidatorInteger(),
      'script_id'      => new sfValidatorInteger(),
      'command_id'     => new sfValidatorInteger(),
      'name'           => new sfValidatorString(array('max_length' => 255)),
      'position'       => new sfValidatorInteger(),
      'num_version'    => new sfValidatorInteger(),
      'selenium_ref'   => new sfValidatorInteger(),
      'command_target' => new sfValidatorString(),
      'command_value'  => new sfValidatorString(),
      'delta'          => new sfValidatorInteger(array('required' => false)),
      'deltaf'         => new sfValidatorInteger(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_function_has_commande[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiFunctionHasCommande';
  }

}
