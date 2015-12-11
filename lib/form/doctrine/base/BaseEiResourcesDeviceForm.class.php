<?php

/**
 * EiResourcesDevice form base class.
 *
 * @method EiResourcesDevice getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiResourcesDeviceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'device_params_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParams'), 'add_empty' => false)),
      'name'                 => new sfWidgetFormInputText(),
      'owner'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'device_visibility_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceVisibility'), 'add_empty' => false)),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'device_params_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParams'))),
      'name'                 => new sfValidatorString(array('max_length' => 255)),
      'owner'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'device_visibility_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceVisibility'))),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_resources_device[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiResourcesDevice';
  }

}
