<?php

/**
 * EiResourcesDeviceParamsDriver form base class.
 *
 * @method EiResourcesDeviceParamsDriver getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiResourcesDeviceParamsDriverForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'device_params_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParams'), 'add_empty' => false)),
      'driver_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDriver'), 'add_empty' => false)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'device_params_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParams'))),
      'driver_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDriver'))),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_resources_device_params_driver[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiResourcesDeviceParamsDriver';
  }

}
