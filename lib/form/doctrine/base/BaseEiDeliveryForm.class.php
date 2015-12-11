<?php

/**
 * EiDelivery form base class.
 *
 * @method EiDelivery getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiDeliveryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'author_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'delivery_state_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiDeliveryState'), 'add_empty' => false)),
      'project_id'        => new sfWidgetFormInputText(),
      'project_ref'       => new sfWidgetFormInputText(),
      'name'              => new sfWidgetFormInputText(),
      'delivery_date'     => new sfWidgetFormDateTime(),
      'description'       => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'delivery_state_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiDeliveryState'))),
      'project_id'        => new sfValidatorInteger(),
      'project_ref'       => new sfValidatorInteger(),
      'name'              => new sfValidatorString(array('max_length' => 255)),
      'delivery_date'     => new sfValidatorDateTime(),
      'description'       => new sfValidatorString(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_delivery[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiDelivery';
  }

}
