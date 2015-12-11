<?php

/**
 * ScriptEiUserTicket form base class.
 *
 * @method ScriptEiUserTicket getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseScriptEiUserTicketForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ticket_id'  => new sfWidgetFormInputHidden(),
      'ticket_ref' => new sfWidgetFormInputHidden(),
      'user_ref'   => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormInputHidden(),
      'state'      => new sfWidgetFormInputText(),
      'delta'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'ticket_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ticket_id')), 'empty_value' => $this->getObject()->get('ticket_id'), 'required' => false)),
      'ticket_ref' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('ticket_ref')), 'empty_value' => $this->getObject()->get('ticket_ref'), 'required' => false)),
      'user_ref'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'user_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'state'      => new sfValidatorString(array('max_length' => 45)),
      'delta'      => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('script_ei_user_ticket[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ScriptEiUserTicket';
  }

}
