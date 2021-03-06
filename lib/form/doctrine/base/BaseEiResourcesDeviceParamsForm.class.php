<?php

/**
 * EiResourcesDeviceParams form base class.
 *
 * @method EiResourcesDeviceParams getObject() Returns the current form's model object
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEiResourcesDeviceParamsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'device_identifier'     => new sfWidgetFormInputText(),
      'device_params_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParamsType'), 'add_empty' => false)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'device_identifier'     => new sfValidatorString(array('max_length' => 255)),
      'device_params_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EiResourcesDeviceParamsType'))),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ei_resources_device_params[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EiResourcesDeviceParams';
  }

}
