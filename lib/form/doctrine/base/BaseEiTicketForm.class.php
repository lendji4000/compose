<?php

/**
 * EiTicket form base class.
 *
 * @method EiTicket getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiTicketForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ticket_id'   => new sfWidgetFormInputHidden(),
      'ticket_ref'  => new sfWidgetFormInputHidden(),
      'project_ref' => new sfWidgetFormInputText(),
      'project_id'  => new sfWidgetFormInputText(),
      'name'        => new sfWidgetFormInputText(),
      'state'       => new sfWidgetFormInputText(),
      'is_active'   => new sfWidgetFormInputCheckbox(),
      'creator_id'  => new sfWidgetFormInputText(),
      'creator_ref' => new sfWidgetFormInputText(),
      'delta'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ticket_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ticket_id')), 'empty_value' => $this->getObject()->get('ticket_id'), 'required' => false)),
      'ticket_ref'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ticket_ref')), 'empty_value' => $this->getObject()->get('ticket_ref'), 'required' => false)),
      'project_ref' => new sfValidatorInteger(),
      'project_id'  => new sfValidatorInteger(),
      'name'        => new sfValidatorString(array('max_length' => 45)),
      'state'       => new sfValidatorString(array('max_length' => 45)),
      'is_active'   => new sfValidatorBoolean(),
      'creator_id'  => new sfValidatorInteger(),
      'creator_ref' => new sfValidatorInteger(),
      'delta'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_ticket[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiTicket';
  }

}
