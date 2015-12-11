<?php

/**
 * EiNodeOpenedBy form base class.
 *
 * @method EiNodeOpenedBy getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiNodeOpenedByForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ei_node_id' => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormInputHidden(),
      'ref_id'     => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ei_node_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ei_node_id')), 'empty_value' => $this->getObject()->get('ei_node_id'), 'required' => false)),
      'user_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'ref_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ref_id')), 'empty_value' => $this->getObject()->get('ref_id'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_node_opened_by[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiNodeOpenedBy';
  }

}
